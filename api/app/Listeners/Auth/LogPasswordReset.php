<?php

namespace App\Listeners\Auth;

use App\Events\Auth\PasswordReset;
use Illuminate\Support\Facades\Log;

/**
 * Class LogPasswordReset
 * @package App\Listeners\Auth
 */
class LogPasswordReset
{
    /**
     * Handle the event.
     *
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $user = "{$event->user->id}|{$event->user->email}";
        Log::info("[User] Password of user '{$user}' has been reset.");
    }
}
