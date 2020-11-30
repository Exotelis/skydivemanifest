<?php

namespace App\Observers;

use App\Models\Text;
use Illuminate\Support\Facades\Log;

/**
 * Class TextObserver
 * @package App\Observers
 */
class TextObserver extends BaseObserver
{
    /**
     * Handle the text "created" event.
     *
     * @param  Text  $text
     * @return void
     */
    public function created(Text $text)
    {
        Log::info("[Text] '{$text->logString()}' has been created for model " .
            "'{$text->textable_type}|{$text->textable_id}' by '{$this->executedBy}'");
    }

    /**
     * Handle the text "updated" event.
     *
     * @param  Text  $text
     * @return void
     */
    public function updated(Text $text)
    {
        $diff = $text->getDiff();
        Log::info("[Text] '{$text->logString()}' of model '{$text->textable_type}|{$text->textable_id}' has " .
            "been updated by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the text "deleted" event.
     *
     * @param  Text  $text
     * @return void
     */
    public function deleted(Text $text)
    {
        Log::info("[Text] '{$text->logString()}' of model '{$text->textable_type}|{$text->textable_id}' has " .
            "been deleted by '{$this->executedBy}'");
    }
}
