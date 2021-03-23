<?php

namespace App\Http\Controllers;

use App\Http\Requests\AircraftLogbookItem\CreateRequest;
use App\Http\Requests\AircraftLogbookItem\UpdateRequest;
use App\Http\Requests\IntIdRequest;
use App\Models\Aircraft;
use App\Models\AircraftLogbookItem;
use App\Models\User;
use App\Traits\Paginate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class AircraftLogbookItemController
 * @package App\Http\Controllers
 */
class AircraftLogbookItemController extends Controller
{
    use Paginate;

    /**
     * Get a list of all aircraft(s).
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, Aircraft $aircraft): JsonResponse
    {
        $logbook = $aircraft->logbook;

        // Paginate, filter and sort items of the logbook
        $this->validatePagination($request->only('limit', 'page'));

        $items = QueryBuilder::for($logbook->items())
            ->allowedFilters(\App\Filters\AircraftLogbookItemFilters::filters())
            ->allowedSorts(\App\Filters\AircraftLogbookItemFilters::sorting())
            ->defaultSort('departure')
            ->paginate($request->input('limit'));

        return response()->json($items);
    }

    /**
     * Create a new entry for a specific logbook.
     *
     * @param CreateRequest $request
     * @param Aircraft      $aircraft
     * @return JsonResponse
     */
    public function create(CreateRequest $request, Aircraft $aircraft): JsonResponse
    {
        $item = null;

        $validated = $request->validated();

        // Parse dates
        $departure = Carbon::parse($validated['departure']);
        $arrival = Carbon::parse($validated['arrival']);

        // Check if in the time range of the flight another entry exists already - If yes abort
        $count = $aircraft->logbook->items()
            ->where('departure', '>=', $departure->toDateTimeString())
            ->where('arrival', '<=', $arrival->toDateTimeString())
            ->count();

        if ($count > 0) {
            abort(400, __('error.aircraft_logbook_item_date_range'));
        }

        // Calculate the block time of the flight in minutes
        $validated['block_time'] = $arrival->diffInMinutes($departure);

        // Get user and add first and last name
        $pilot = User::findOrFail($validated['pilot_id']);
        $validated['pilot_firstname'] = $pilot->firstname;
        $validated['pilot_lastname'] = $pilot->lastname;

        try {
            $item = $aircraft->logbook->items()->create($validated);
        } catch (\Throwable $exception) {
            abort(500, __('messages.aircraft_logbook_item_created_failed'));
        }

        return response()
            ->json(['message' => __('messages.aircraft_logbook_item_created'), 'data' => $item], 201);
    }

    /**
     * Delete a single entry of a logbook.
     *
     * @param  Request             $request
     * @param  Aircraft            $aircraft
     * @param  AircraftLogbookItem $item
     * @return JsonResponse
     */
    public function delete(Request $request, Aircraft $aircraft, AircraftLogbookItem $item): JsonResponse
    {
        try {
            $item->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_logbook_item', 1)]);
    }

    /**
     * Delete one or more entries of a logbook.
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

        foreach ($aircraft->logbook->items as $item) {
            if (\in_array($item->id, $input['ids']) && $item->delete()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_logbook_item', $count)
        ]);
    }

    /**
     * Return a single logbook entry.
     *
     * @param  Request             $request
     * @param  Aircraft            $aircraft
     * @param  AircraftLogbookItem $item
     * @return JsonResponse
     */
    public function get(Request $request, Aircraft $aircraft, AircraftLogbookItem $item): JsonResponse
    {
        return response()->json($item);
    }

    /**
     * Update an entry of the logbook of the aircraft.
     *
     * @param  UpdateRequest       $request
     * @param  Aircraft            $aircraft
     * @param  AircraftLogbookItem $item
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Aircraft $aircraft, AircraftLogbookItem $item): JsonResponse
    {
        $validated = $request->validated();

        // Parse dates
        $departure = Carbon::parse($validated['departure']);
        $arrival = Carbon::parse($validated['arrival']);

        // Calculate the block time of the flight in minutes
        $validated['block_time'] = $arrival->diffInMinutes($departure);

        // Get user and add first and last name if pilot changed
        if (\array_key_exists('pilot_id', $validated) &&
            $item->pilot_id !== (int) $validated['pilot_id'] &&
            ! \is_null($validated['pilot_id'])
        ) {
            $pilot = User::findOrFail($validated['pilot_id']);
            $validated['pilot_firstname'] = $pilot->firstname;
            $validated['pilot_lastname'] = $pilot->lastname;

            if (!\array_key_exists('pilot_signature', $validated)) {
                $validated['pilot_signature'] = null;
            }
        }

        if (! $item->update($validated)) {
            abort(500, __('error.500'));
        }

        return response()->json($item);
    }
}
