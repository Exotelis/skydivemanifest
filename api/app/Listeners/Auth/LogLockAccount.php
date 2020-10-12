<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LockAccount;
use Illuminate\Support\Facades\Log;

/**
 * Class LogLockAccount
 * @package App\Listeners\Auth
 */
class LogLockAccount
{
    /**
     * Handle the event.
     *
     * @param  LockAccount  $event
     * @return void
     */
    public function handle(LockAccount $event)
    {
        $user = "{$event->user->id}|{$event->user->email}";
        Log::info("[User] Account of user '{$user}' has been locked temporarily.");
    }
}
