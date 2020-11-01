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
     * @param  AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function created(AircraftMaintenance $aircraftMaintenance)
    {
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->id}' has been created for aircraft " .
            "'{$aircraftMaintenance->aircraft_registration}' by '{$this->executedBy}'");
    }

    /**
     * Handle the aircraft maintenance "updated" event.
     *
     * @param  AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function updated(AircraftMaintenance $aircraftMaintenance)
    {
        $diff = getModelDiff($aircraftMaintenance, [], true);
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->id}' of aircraft " .
            "'{$aircraftMaintenance->aircraft_registration}' has been updated by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the aircraft maintenance "deleted" event.
     *
     * @param  AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function deleted(AircraftMaintenance $aircraftMaintenance)
    {
        Log::info("[Aircraft Maintenance] '{$aircraftMaintenance->id}' of aircraft " .
            "'{$aircraftMaintenance->aircraft_registration}' has been deleted by '{$this->executedBy}'");
    }
}
