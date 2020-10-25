<?php

namespace App\Observers;

use App\Models\Aircraft;

class AircraftObserver
{
    /**
     * Handle the aircraft "created" event.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return void
     */
    public function created(Aircraft $aircraft)
    {
        //
    }

    /**
     * Handle the aircraft "updated" event.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return void
     */
    public function updated(Aircraft $aircraft)
    {
        //
    }

    /**
     * Handle the aircraft "deleted" event.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return void
     */
    public function deleted(Aircraft $aircraft)
    {
        //
    }

    /**
     * Handle the aircraft "restored" event.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return void
     */
    public function restored(Aircraft $aircraft)
    {
        //
    }

    /**
     * Handle the aircraft "force deleted" event.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return void
     */
    public function forceDeleted(Aircraft $aircraft)
    {
        //
    }
}
