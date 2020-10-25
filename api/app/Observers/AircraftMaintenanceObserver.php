<?php

namespace App\Observers;

use App\Models\AircraftMaintenance;

class AircraftMaintenanceObserver
{
    /**
     * Handle the aircraft maintenance "created" event.
     *
     * @param  \App\Models\AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function created(AircraftMaintenance $aircraftMaintenance)
    {
        //
    }

    /**
     * Handle the aircraft maintenance "updated" event.
     *
     * @param  \App\Models\AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function updated(AircraftMaintenance $aircraftMaintenance)
    {
        //
    }

    /**
     * Handle the aircraft maintenance "deleted" event.
     *
     * @param  \App\Models\AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function deleted(AircraftMaintenance $aircraftMaintenance)
    {
        //
    }

    /**
     * Handle the aircraft maintenance "restored" event.
     *
     * @param  \App\Models\AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function restored(AircraftMaintenance $aircraftMaintenance)
    {
        //
    }

    /**
     * Handle the aircraft maintenance "force deleted" event.
     *
     * @param  \App\Models\AircraftMaintenance  $aircraftMaintenance
     * @return void
     */
    public function forceDeleted(AircraftMaintenance $aircraftMaintenance)
    {
        //
    }
}
