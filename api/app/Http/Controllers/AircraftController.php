<?php

namespace App\Http\Controllers;

use App\Http\Requests\Aircraft\CreateRequest;
use App\Http\Requests\Aircraft\UpdateRequest;
use App\Models\Aircraft;
use App\Traits\Paginate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class AircraftController
 * @package App\Http\Controllers
 */
class AircraftController extends Controller
{
    use Paginate;

    /**
     * Get a list of all aircraft(s).
     *
     * @param  Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request): JsonResponse
    {
        $this->validatePagination($request->only('limit', 'page'));

        $aircraft = QueryBuilder::for(Aircraft::withTrashed())
            ->allowedAppends(['operation_time'])
            ->allowedFilters(\App\Filters\AircraftFilters::filters())
            ->allowedSorts(\App\Filters\AircraftFilters::sorting())
            ->defaultSort('registration')
            ->paginate($request->input('limit'));

        return response()->json($aircraft);
    }

    /**
     * Create a new aircraft.
     *
     * @param  CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $aircraft = null;

        try {
            DB::beginTransaction();

            $aircraft = Aircraft::create($request->validated());
            $aircraft->logbook()->create();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('messages.aircraft_created_failed'));
        }

        return response()->json(['message' => __('messages.aircraft_created'), 'data' => $aircraft], 201);
    }

    /**
     * Return a single aircraft.
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     */
    public function get(Request $request, Aircraft $aircraft): JsonResponse
    {
        return response()->json($aircraft);
    }

    /**
     * Put the aircraft back into service.
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     */
    public function putBackIntoService(Request $request, Aircraft $aircraft): JsonResponse
    {
        if (\is_null($aircraft->put_out_of_service_at)) {
            abort(400, __('error.aircraft_still_in_service'));
        }

        try {
            $aircraft->restore();
        } catch (\Throwable $exception) {
            abort(500, __('error.could_not_put_back_into_service'));
        }

        return response()->json(['message' => __('messages.aircraft_put_back_into_service')], 200);
    }

    /**
     * Put the aircraft out of service.
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     */
    public function putOutOfService(Request $request, Aircraft $aircraft): JsonResponse
    {
        if (! \is_null($aircraft->put_out_of_service_at)) {
            abort(400, __('error.aircraft_already_out_of_service'));
        }

        try {
            $aircraft->delete();
        } catch (\Throwable $exception) {
            abort(500, __('error.could_not_put_out_of_service'));
        }

        return response()->json(['message' => __('messages.aircraft_put_out_of_service')], 200);
    }

    /**
     * Update an aircraft.
     *
     * @param  UpdateRequest $request
     * @param  Aircraft      $aircraft
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Aircraft $aircraft): JsonResponse
    {
        try {
            $aircraft->update($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($aircraft);
    }
}
