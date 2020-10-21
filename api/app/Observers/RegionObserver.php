<?php

namespace App\Observers;

use App\Models\Region;

class RegionObserver
{
    /**
     * Handle the region "created" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function created(Region $region)
    {
        //
    }

    /**
     * Handle the region "updated" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function updated(Region $region)
    {
        //
    }

    /**
     * Handle the region "deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function deleted(Region $region)
    {
        //
    }

    /**
     * Handle the region "restored" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function restored(Region $region)
    {
        //
    }

    /**
     * Handle the region "force deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function forceDeleted(Region $region)
    {
        //
    }
}
