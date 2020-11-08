<?php

namespace App\Observers;

use App\Models\Qualification;
use Illuminate\Support\Facades\Log;

/**
 * Class QualificationObserver
 * @package App\Observers
 */
class QualificationObserver extends BaseObserver
{
    /**
     * Handle the qualification "created" event.
     *
     * @param  Qualification  $qualification
     * @return void
     */
    public function created(Qualification $qualification)
    {
        Log::info("[Qualification] '{$qualification->slug}|{$qualification->qualification}' has been created " .
            "by '{$this->executedBy}'");
    }

    /**
     * Handle the qualification "updated" event.
     *
     * @param  Qualification  $qualification
     * @return void
     */
    public function updated(Qualification $qualification)
    {
        $diff = getModelDiff($qualification, [], true);
        Log::info("[Qualification] '{$qualification->slug}|{$qualification->qualification}' has been updated " .
            "by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the qualification "deleted" event.
     *
     * @param  Qualification  $qualification
     * @return void
     */
    public function deleted(Qualification $qualification)
    {
        Log::info("[Qualification] '{$qualification->slug}|{$qualification->qualification}' has been deleted " .
            "by '{$this->executedBy}'");
    }
}
