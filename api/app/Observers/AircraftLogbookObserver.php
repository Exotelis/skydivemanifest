<?php

namespace App\Observers;

use App\Models\AircraftLogbook;
use Illuminate\Support\Facades\Log;

/**
 * Class AircraftLogbookObserver
 * @package App\Observers
 */
class AircraftLogbookObserver extends BaseObserver
{
    /**
     * Handle the AircraftLogbook "created" event.
     *
     * @param  AircraftLogbook $aircraftLogbook
     * @return void
     */
    public function created(AircraftLogbook $aircraftLogbook): void
    {
        Log::info("[Aircraft Logbook] '{$aircraftLogbook->logString()}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the AircraftLogbook "updated" event.
     *
     * @param  AircraftLogbook $aircraftLogbook
     * @return void
     */
    public function updated(AircraftLogbook $aircraftLogbook): void
    {
        $diff = $aircraftLogbook->getDiff();
        Log::info("[Aircraft Logbook] '{$aircraftLogbook->logString()}' has been updated by " .
            "'{$this->executedBy}' ($diff)");
    }
}
