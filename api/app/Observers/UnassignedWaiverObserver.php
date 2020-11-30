<?php

namespace App\Observers;

use App\Models\UnassignedWaiver;
use Illuminate\Support\Facades\Log;

/**
 * Class UnassignedWaiverObserver
 * @package App\Observers
 */
class UnassignedWaiverObserver extends BaseObserver
{
    /**
     * Handle the unassigned waiver "created" event.
     *
     * @param  UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function created(UnassignedWaiver $unassignedWaiver)
    {
        Log::info("[Unassigned waiver] '{$unassignedWaiver->logString()}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the unassigned waiver "updated" event.
     *
     * @param  UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function updated(UnassignedWaiver $unassignedWaiver)
    {
        $diff = $unassignedWaiver->getDiff();
        Log::info("[Unassigned waiver] '{$unassignedWaiver->logString()}' has been updated by " .
            "'{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the unassigned waiver "deleted" event.
     *
     * @param  UnassignedWaiver  $unassignedWaiver
     * @return void
     */
    public function deleted(UnassignedWaiver $unassignedWaiver)
    {
        Log::info("[Unassigned waiver] '{$unassignedWaiver->logString()}' has been deleted by " .
            "'{$this->executedBy}'");
    }
}
