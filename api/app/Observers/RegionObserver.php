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
        Log::info("[Region] '{$region->logString()}' has been created for country " .
            "'{$region->country->logString()}' by '{$this->executedBy}'");
    }

    /**
     * Handle the region "updated" event.
     *
     * @param  Region  $region
     * @return void
     */
    public function updated(Region $region)
    {
        $diff = $region->getDiff();
        Log::info("[Region] '{$region->logString()}' of country '{$region->country->logString()}' has been " .
            "updated by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the region "deleted" event.
     *
     * @param  Region  $region
     * @return void
     */
    public function deleted(Region $region)
    {
        Log::info("[Region] '{$region->logString()}' of country '{$region->country->logString()}' has been " .
            "deleted by '{$this->executedBy}'");
    }
}
