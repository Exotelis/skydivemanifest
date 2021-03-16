<?php

namespace App\Observers;

use App\Models\AircraftLogbook;

class AircraftLogbookObserver
{
    /**
     * Handle the AircraftLogbook "created" event.
     *
     * @param  \App\Models\AircraftLogbook  $aircraftLogbook
     * @return void
     */
    public function created(AircraftLogbook $aircraftLogbook)
    {
        //
    }

    /**
     * Handle the AircraftLogbook "updated" event.
     *
     * @param  \App\Models\AircraftLogbook  $aircraftLogbook
     * @return void
     */
    public function updated(AircraftLogbook $aircraftLogbook)
    {
        //
    }

    /**
     * Handle the AircraftLogbook "deleted" event.
     *
     * @param  \App\Models\AircraftLogbook  $aircraftLogbook
     * @return void
     */
    public function deleted(AircraftLogbook $aircraftLogbook)
    {
        //
    }

    /**
     * Handle the AircraftLogbook "restored" event.
     *
     * @param  \App\Models\AircraftLogbook  $aircraftLogbook
     * @return void
     */
    public function restored(AircraftLogbook $aircraftLogbook)
    {
        //
    }

    /**
     * Handle the AircraftLogbook "force deleted" event.
     *
     * @param  \App\Models\AircraftLogbook  $aircraftLogbook
     * @return void
     */
    public function forceDeleted(AircraftLogbook $aircraftLogbook)
    {
        //
    }
}
