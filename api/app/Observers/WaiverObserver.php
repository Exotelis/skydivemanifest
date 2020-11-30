<?php

namespace App\Observers;

use App\Models\Text;
use App\Models\UnassignedWaiver;
use App\Models\Waiver;
use Illuminate\Support\Facades\Log;

/**
 * Class WaiverObserver
 * @package App\Observers
 */
class WaiverObserver extends BaseObserver
{
    /**
     * Handle the waiver "created" event.
     *
     * @param  Waiver  $waiver
     * @return void
     */
    public function created(Waiver $waiver)
    {
        Log::info("[Waiver] '{$waiver->logString()}' has been created by '{$this->executedBy}'");
    }

    /**
     * Handle the waiver "updated" event.
     *
     * @param  Waiver  $waiver
     * @return void
     */
    public function updated(Waiver $waiver)
    {
        // If it has been deactivated
        if ($waiver->isDirty('is_active') && ! $waiver->is_active) {
            // Delete related unassigned signed waivers and all assigned signed waivers
            UnassignedWaiver::destroy($waiver->unassignedWaivers->pluck('id'));
            $waiver->users()->detach($waiver->users->pluck('id')->toArray());

            Log::info("[Waiver] '{$waiver->logString()}' has been deactivated by '{$this->executedBy}'. " .
                "All related signatures have been removed.");
        }

        $diff = $waiver->getDiff();
        Log::info("[Waiver] '{$waiver->logString()}' has been updated by '{$this->executedBy}' ($diff)");
    }

    /**
     * Handle the waiver "deleted" event.
     *
     * @param  Waiver  $waiver
     * @return void
     */
    public function deleted(Waiver $waiver)
    {
        // Delete related models as well
        Text::destroy($waiver->texts->pluck('id')); // Delete related texts
        UnassignedWaiver::destroy($waiver->unassignedWaivers->pluck('id')); // Delete related unassigned waivers
        $waiver->users()->detach($waiver->users->pluck('id')->toArray());

        Log::info("[Waiver] '{$waiver->logString()}' has been deleted by '{$this->executedBy}'");
    }
}
