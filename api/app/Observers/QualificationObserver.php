<?php

namespace App\Observers;

use App\Models\Qualification;

class QualificationObserver
{
    /**
     * Handle the qualification "created" event.
     *
     * @param  \App\Models\Qualification  $qualification
     * @return void
     */
    public function created(Qualification $qualification)
    {
        //
    }

    /**
     * Handle the qualification "updated" event.
     *
     * @param  \App\Models\Qualification  $qualification
     * @return void
     */
    public function updated(Qualification $qualification)
    {
        //
    }

    /**
     * Handle the qualification "deleted" event.
     *
     * @param  \App\Models\Qualification  $qualification
     * @return void
     */
    public function deleted(Qualification $qualification)
    {
        //
    }

    /**
     * Handle the qualification "restored" event.
     *
     * @param  \App\Models\Qualification  $qualification
     * @return void
     */
    public function restored(Qualification $qualification)
    {
        //
    }

    /**
     * Handle the qualification "force deleted" event.
     *
     * @param  \App\Models\Qualification  $qualification
     * @return void
     */
    public function forceDeleted(Qualification $qualification)
    {
        //
    }
}
