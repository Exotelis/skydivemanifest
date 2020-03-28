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
        Log::info("Password of user '{$event->user->id}|{$event->user->email}' has been reset.");
    }
}
