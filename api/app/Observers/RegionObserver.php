<?php

namespace App\Observers;

use App\Models\Region;
use Illuminate\Support\Facades\Log;

/**
 * Class RegionObserver
 * @package App\Observers
 */
class RegionObserver extends BaseObserver
{
    /**
     * Handle the region "created" event.
     *
     * @param  Region  $region
     * @return void
     */
    public function created(Region $region)
    {
        $country = "{$region->country->id}|{$region->country->country}|{$region->country->code}";
        Log::info("[Region] '{$region->id}|{$region->region}' has been created for country '{$country}' by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the region "updated" event.
     *
     * @param  Region  $region
     * @return void
     */
    public function updated(Region $region)
    {
        $diff = getModelDiff($region, [], true);
        $country = "{$region->country->id}|{$region->country->country}|{$region->country->code}";
        Log::info("[Region] '{$region->id}|{$region->region}' of country '{$country}' has been updated by " .
            "'{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the region "deleted" event.
     *
     * @param  Region  $region
     * @return void
     */
    public function deleted(Region $region)
    {
        $country = "{$region->country->id}|{$region->country->country}|{$region->country->code}";
        Log::info("[Region] '{$region->id}|{$region->region}' of country '{$country}' has been deleted by " .
            "'{$this->executedBy}'");
    }
}
