<?php

namespace App\Observers;

use App\Models\Text;

class TextObserver
{
    /**
     * Handle the text "created" event.
     *
     * @param  \App\Models\Text  $text
     * @return void
     */
    public function created(Text $text)
    {
        //
    }

    /**
     * Handle the text "updated" event.
     *
     * @param  \App\Models\Text  $text
     * @return void
     */
    public function updated(Text $text)
    {
        //
    }

    /**
     * Handle the text "deleted" event.
     *
     * @param  \App\Models\Text  $text
     * @return void
     */
    public function deleted(Text $text)
    {
        //
    }

    /**
     * Handle the text "restored" event.
     *
     * @param  \App\Models\Text  $text
     * @return void
     */
    public function restored(Text $text)
    {
        //
    }

    /**
     * Handle the text "force deleted" event.
     *
     * @param  \App\Models\Text  $text
     * @return void
     */
    public function forceDeleted(Text $text)
    {
        //
    }
}
