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
        Log::info("Account of user '{$event->user->id}|{$event->user->email}' has been locked temporarily.");
    }
}
