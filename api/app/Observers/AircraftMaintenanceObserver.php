<?php

namespace App\Observers;

use App\Models\AircraftMaintenance;
use Illuminate\Support\Facades\Log;

/**
 * Class AircraftMaintenanceObserver
 * @package App\Observers
 */
class AircraftMaintenanceObserver extends BaseObserver
{
    /**
     * Handle the aircraft maintenance "created" event.
     *
     * @param  AircraftMaintenance $aircraftMaintenance
     * @return void
     */
    public function created(AircraftMaintenance $aircraftMaintenance): void
    {
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->logString()}' has been created for " .
            "aircraft '{$aircraftMaintenance->aircraft->logString()}' by '{$this->executedBy}'");
    }

    /**
     * Handle the aircraft maintenance "updated" event.
     *
     * @param  AircraftMaintenance $aircraftMaintenance
     * @return void
     */
    public function updated(AircraftMaintenance $aircraftMaintenance): void
    {
        $diff = $aircraftMaintenance->getDiff();
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->logString()}' of aircraft " .
            "'{$aircraftMaintenance->aircraft->logString()}' has been updated by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the aircraft maintenance "deleted" event.
     *
     * @param  AircraftMaintenance $aircraftMaintenance
     * @return void
     */
    public function deleted(AircraftMaintenance $aircraftMaintenance): void
    {
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->logString()}' of aircraft " .
            "'{$aircraftMaintenance->aircraft->logString()}' has been deleted by '{$this->executedBy}'");
    }
}
