<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LockAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLockAccount
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
     * @param  LockAccount  $event
     * @return void
     */
    public function handle(LockAccount $event)
    {
        //
    }
}
