<?php

namespace App\Listeners\Auth;

use App\Events\Auth\EmailVerified;
use Illuminate\Support\Facades\Log;

/**
 * Class LogEmailVerified
 * @package App\Listeners\Auth
 */
class LogEmailVerified
{
    /**
     * Handle the event.
     *
     * @param  EmailVerified  $event
     * @return void
     */
    public function handle(EmailVerified $event)
    {
        Log::info("Email address of user '{$event->user->id}|{$event->user->email}' has been verified.");
    }
}
