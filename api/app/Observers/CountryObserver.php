<?php

namespace App\Observers;

use App\Models\Country;
use Illuminate\Support\Facades\Log;

/**
 * Class CountryObserver
 * @package App\Observers
 */
class CountryObserver extends BaseObserver
{
    /**
     * Handle the country "created" event.
     *
     * @param  Country  $country
     * @return void
     */
    public function created(Country $country)
    {
        Log::info("[Country] '{$country->id}|{$country->country}|{$country->code}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the country "updated" event.
     *
     * @param  Country  $country
     * @return void
     */
    public function updated(Country $country)
    {
        $diff = getModelDiff($country, [], true);
        Log::info("[Country] '{$country->id}|{$country->country}|{$country->code}' has been updated by " .
            "'{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the country "deleted" event.
     *
     * @param  Country  $country
     * @return void
     */
    public function deleted(Country $country)
    {
        Log::info("[Country] '{$country->id}|{$country->country}|{$country->code}' has been deleted by " .
            "'{$this->executedBy}'");
    }
}
