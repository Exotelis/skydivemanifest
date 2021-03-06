<?php

namespace App\Http\Controllers;

use App\Http\Requests\AircraftMaintenance\CompleteRequest;
use App\Http\Requests\AircraftMaintenance\CreateRequest;
use App\Http\Requests\AircraftMaintenance\UpdateRequest;
use App\Http\Requests\IntIdRequest;
use App\Models\Aircraft;
use App\Models\AircraftMaintenance;
use App\Traits\Paginate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class AircraftMaintenanceController
 * @package App\Http\Controllers
 */
class AircraftMaintenanceController extends Controller
{
    use Paginate;

    /**
     * Get a list of all aircraft maintenance(s).
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     * @throws ValidationException
     */
    public function all(Request $request, Aircraft $aircraft): JsonResponse
    {
        $this->validatePagination($request->only('limit', 'page'));

        $maintenance = QueryBuilder::for($aircraft->maintenance())
            ->with(['aircraft'])
            ->allowedFilters(\App\Filters\AircraftMaintenanceFilters::filters())
            ->allowedSorts(\App\Filters\AircraftMaintenanceFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($maintenance);
    }

    /**
     * Complete a maintenance of a specific aircraft.
     *
     * @param  CompleteRequest     $request
     * @param  Aircraft            $aircraft
     * @param  AircraftMaintenance $maintenance
     * @return JsonResponse
     */
    public function complete(CompleteRequest $request, Aircraft $aircraft, AircraftMaintenance $maintenance): JsonResponse
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create new maintenance when repetition interval was set
            if (! \is_null($maintenance->repetition_interval)) {
                $maintenanceAt = $validated['maintenance_at'] + $maintenance->repetition_interval;
                $notifyAt = null;

                // If notification threshold was defined and if it's bigger than operation time
                if (! \is_null($maintenance->notify_at)) {
                    $notifyAt = $maintenanceAt - ($maintenance->maintenance_at - $maintenance->notify_at);

                    if ($aircraft->operation_time > $notifyAt) {
                        $notifyAt = null;
                    }
                }

                // Create new maintenance
                $aircraft->maintenance()->create([
                    'maintenance_at'      => $maintenanceAt,
                    'name'                => $maintenance->name,
                    'notes'               => $maintenance->notes,
                    'notify_at'           => $notifyAt,
                    'repetition_interval' => $maintenance->repetition_interval,
                ]);
            }

            // Complete current maintenance
            $maintenance->dom = $validated['dom'];
            $maintenance->maintenance_at = $validated['maintenance_at'];
            $maintenance->notify_at = null;
            $maintenance->saveOrFail();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('messages.aircraft_maintenance_complete_failed'));
        }

        return response()->json([
            'message' => __('messages.aircraft_maintenance_complete'),
            'data' => $maintenance->load('aircraft')
        ]);
    }

    /**
     * Create a new maintenance for a specific aircraft.
     *
     * @param  CreateRequest $request
     * @param  Aircraft      $aircraft
     * @return JsonResponse
     */
    public function create(CreateRequest $request, Aircraft $aircraft): JsonResponse
    {
        $maintenance = null;

        try {
            $maintenance = $aircraft->maintenance()->create($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('messages.aircraft_maintenance_created_failed'));
        }

        return response()->json([
            'message' => __('messages.aircraft_maintenance_created'),
            'data' => $maintenance->load('aircraft')
        ], 201);
    }

    /**
     * Delete a single maintenance of an specific aircraft.
     *
     * @param  Request             $request
     * @param  Aircraft            $aircraft
     * @param  AircraftMaintenance $maintenance
     * @return JsonResponse
     */
    public function delete(Request $request, Aircraft $aircraft, AircraftMaintenance $maintenance): JsonResponse
    {
        try {
            $maintenance->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_maintenance', 1)]);
    }

    /**
     * Delete one or more maintenance of a specific aircraft.
     *
     * @param  IntIdRequest $request
     * @param  Aircraft     $aircraft
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteBulk(IntIdRequest $request, Aircraft $aircraft): JsonResponse
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($aircraft->maintenance as $maintenance) {
            if (\in_array($maintenance->id, $input['ids']) && $maintenance->delete()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_maintenance', $count)
        ]);
    }

    /**
     * Return a single maintenance of a specific aircraft.
     *
     * @param  Request             $request
     * @param  Aircraft            $aircraft
     * @param  AircraftMaintenance $maintenance
     * @return JsonResponse
     */
    public function get(Request $request, Aircraft $aircraft, AircraftMaintenance $maintenance): JsonResponse
    {
        return response()->json($maintenance->load('aircraft'));
    }

    /**
     * Update a maintenance of a specific aircraft.
     *
     * @param  UpdateRequest       $request
     * @param  Aircraft            $aircraft
     * @param  AircraftMaintenance $maintenance
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Aircraft $aircraft, AircraftMaintenance $maintenance): JsonResponse
    {
        if (! $maintenance->update($request->validated())) {
            abort(500, __('error.500'));
        }

        return response()->json($maintenance->load('aircraft'));
    }
}
