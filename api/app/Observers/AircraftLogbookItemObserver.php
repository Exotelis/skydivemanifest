<?php

namespace App\Observers;

use App\Models\AircraftLogbookItem;

class AircraftLogbookItemObserver
{
    /**
     * Handle the AircraftLogbookItem "created" event.
     *
     * @param  \App\Models\AircraftLogbookItem  $aircraftLogbookItem
     * @return void
     */
    public function created(AircraftLogbookItem $aircraftLogbookItem)
    {
        //
    }

    /**
     * Handle the AircraftLogbookItem "updated" event.
     *
     * @param  \App\Models\AircraftLogbookItem  $aircraftLogbookItem
     * @return void
     */
    public function updated(AircraftLogbookItem $aircraftLogbookItem)
    {
        //
    }

    /**
     * Handle the AircraftLogbookItem "deleted" event.
     *
     * @param  \App\Models\AircraftLogbookItem  $aircraftLogbookItem
     * @return void
     */
    public function deleted(AircraftLogbookItem $aircraftLogbookItem)
    {
        //
    }

    /**
     * Handle the AircraftLogbookItem "restored" event.
     *
     * @param  \App\Models\AircraftLogbookItem  $aircraftLogbookItem
     * @return void
     */
    public function restored(AircraftLogbookItem $aircraftLogbookItem)
    {
        //
    }

    /**
     * Handle the AircraftLogbookItem "force deleted" event.
     *
     * @param  \App\Models\AircraftLogbookItem  $aircraftLogbookItem
     * @return void
     */
    public function forceDeleted(AircraftLogbookItem $aircraftLogbookItem)
    {
        //
    }
}
