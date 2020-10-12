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
        $user = "{$event->user->id}|{$event->user->email}";
        Log::info("[User] Email address of user '{$user}' has been verified.");
    }
}
