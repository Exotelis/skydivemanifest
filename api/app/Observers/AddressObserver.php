<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    /**
     * Handle the address "created" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function created(Address $address)
    {
        //
    }

    /**
     * Handle the address "updated" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function updated(Address $address)
    {
        //
    }

    /**
     * Handle the address "deleted" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function deleted(Address $address)
    {
        //
    }

    /**
     * Handle the address "restored" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function restored(Address $address)
    {
        //
    }

    /**
     * Handle the address "force deleted" event.
     *
     * @param  \App\Models\Address  $address
     * @return void
     */
    public function forceDeleted(Address $address)
    {
        //
    }
}
