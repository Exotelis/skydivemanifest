<?php

namespace App\Observers;

use App\Models\Text;
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
        Text::destroy($waiver->texts->pluck('id')); // Delete related texts
        Log::info("[Waiver] '{$waiver->logString()}' has been deleted by '{$this->executedBy}'");
    }
}
