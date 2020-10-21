<?php

namespace App\Observers;

use App\Models\Currency;
use Illuminate\Support\Facades\Log;

/**
 * Class CurrencyObserver
 * @package App\Observers
 */
class CurrencyObserver extends BaseObserver
{
    /**
     * Handle the currency "created" event.
     *
     * @param  Currency  $currency
     * @return void
     */
    public function created(Currency $currency)
    {
        Log::info("[Currency] '{$currency->code}|{$currency->currency}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the currency "updated" event.
     *
     * @param  Currency  $currency
     * @return void
     */
    public function updated(Currency $currency)
    {
        $diff = getModelDiff($currency, [], true);
        Log::info("[Currency] '{$currency->code}|{$currency->currency}' has been updated by " .
            "'{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the currency "deleted" event.
     *
     * @param  Currency  $currency
     * @return void
     */
    public function deleted(Currency $currency)
    {
        Log::info("[Currency] '{$currency->code}|{$currency->currency}' has been deleted by " .
            "'{$this->executedBy}'");
    }
}
