<?php

namespace App\Http\Controllers;

use App\Http\Requests\AircraftLogbook\UpdateRequest;
use App\Models\Aircraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AircraftLogbookController
 * @package App\Http\Controllers
 */
class AircraftLogbookController extends Controller
{
    /**
     * Return the logbook of the aircraft.
     *
     * @param  Request  $request
     * @param  Aircraft $aircraft
     * @return JsonResponse
     */
    public function get(Request $request, Aircraft $aircraft): JsonResponse
    {
        return response()->json($aircraft->logbook);
    }

    /**
     * Update the logbook of the aircraft.
     *
     * @param  UpdateRequest $request
     * @param  Aircraft      $aircraft
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Aircraft $aircraft): JsonResponse
    {
        $logbook = $aircraft->logbook;

        if (! $logbook->update($request->validated())) {
            abort(500, __('error.500'));
        }

        return response()->json($logbook);
    }
}
