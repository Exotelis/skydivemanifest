<?php

namespace App\Observers;

use App\Models\Aircraft;
use Illuminate\Support\Facades\Log;

/**
 * Class AircraftObserver
 * @package App\Observers
 */
class AircraftObserver extends BaseObserver
{
    /**
     * Handle the aircraft "created" event.
     *
     * @param  Aircraft  $aircraft
     * @return void
     */
    public function created(Aircraft $aircraft)
    {
        Log::info("[Aircraft] '{$aircraft->registration}|{$aircraft->model}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the aircraft "updated" event.
     *
     * @param  Aircraft  $aircraft
     * @return void
     */
    public function updated(Aircraft $aircraft)
    {
        $diff = getModelDiff($aircraft, [], true);
        Log::info("[Aircraft] '{$aircraft->registration}|{$aircraft->model}' has been updated by " .
            "'{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the aircraft "deleted" event.
     *
     * @param  Aircraft  $aircraft
     * @return void
     */
    public function deleted(Aircraft $aircraft)
    {
        Log::info("[Aircraft] '{$aircraft->registration}|{$aircraft->model}' has been put out of service by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the aircraft "restored" event.
     *
     * @param  Aircraft  $aircraft
     * @return void
     */
    public function restored(Aircraft $aircraft)
    {
        Log::info("[Aircraft] '{$aircraft->registration}|{$aircraft->model}' has been put back into service " .
            "by '{$this->executedBy}'");
    }
}
