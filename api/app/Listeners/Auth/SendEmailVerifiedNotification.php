<?php

namespace App\Listeners\Auth;

use App\Events\Auth\EmailVerified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerifiedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailVerified  $event
     * @return void
     */
    public function handle(EmailVerified $event)
    {
        //
    }
}
