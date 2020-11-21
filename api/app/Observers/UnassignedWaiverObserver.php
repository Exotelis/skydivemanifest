<?php

namespace App\Observers;

use App\Models\UnassignedWaiver;

class UnassignedWaiverObserver
{
    /**
     * Handle the unassigned waiver "created" event.
     *
     * @param  \App\Models\UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function created(UnassignedWaiver $unassignedWaiver)
    {
        //
    }

    /**
     * Handle the unassigned waiver "updated" event.
     *
     * @param  \App\Models\UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function updated(UnassignedWaiver $unassignedWaiver)
    {
        //
    }

    /**
     * Handle the unassigned waiver "deleted" event.
     *
     * @param  \App\Models\UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function deleted(UnassignedWaiver $unassignedWaiver)
    {
        //
    }

    /**
     * Handle the unassigned waiver "restored" event.
     *
     * @param  \App\Models\UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function restored(UnassignedWaiver $unassignedWaiver)
    {
        //
    }

    /**
     * Handle the unassigned waiver "force deleted" event.
     *
     * @param  \App\Models\UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function forceDeleted(UnassignedWaiver $unassignedWaiver)
    {
        //
    }
}
