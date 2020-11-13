<?php

namespace App\Observers;

use App\Models\Waiver;

class WaiverObserver
{
    /**
     * Handle the waiver "created" event.
     *
     * @param  \App\Models\Waiver  $waiver
     * @return void
     */
    public function created(Waiver $waiver)
    {
        //
    }

    /**
     * Handle the waiver "updated" event.
     *
     * @param  \App\Models\Waiver  $waiver
     * @return void
     */
    public function updated(Waiver $waiver)
    {
        //
    }

    /**
     * Handle the waiver "deleted" event.
     *
     * @param  \App\Models\Waiver  $waiver
     * @return void
     */
    public function deleted(Waiver $waiver)
    {
        //
    }

    /**
     * Handle the waiver "restored" event.
     *
     * @param  \App\Models\Waiver  $waiver
     * @return void
     */
    public function restored(Waiver $waiver)
    {
        //
    }

    /**
     * Handle the waiver "force deleted" event.
     *
     * @param  \App\Models\Waiver  $waiver
     * @return void
     */
    public function forceDeleted(Waiver $waiver)
    {
        //
    }
}
