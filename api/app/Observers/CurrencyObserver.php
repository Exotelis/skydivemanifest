<?php

namespace App\Observers;

use App\Models\Currency;

class CurrencyObserver
{
    /**
     * Handle the currency "created" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function created(Currency $currency)
    {
        //
    }

    /**
     * Handle the currency "updated" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function updated(Currency $currency)
    {
        //
    }

    /**
     * Handle the currency "deleted" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function deleted(Currency $currency)
    {
        //
    }

    /**
     * Handle the currency "restored" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function restored(Currency $currency)
    {
        //
    }

    /**
     * Handle the currency "force deleted" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function forceDeleted(Currency $currency)
    {
        //
    }
}
