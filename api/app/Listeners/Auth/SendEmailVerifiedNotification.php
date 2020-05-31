<?php

namespace App\Listeners\Auth;

use App\Events\Auth\EmailVerified;

/**
 * Class SendEmailVerifiedNotification
 * @package App\Listeners\Auth
 */
class SendEmailVerifiedNotification
{
    /**
     * Handle the event.
     *
     * @param  EmailVerified $event
     * @return void
     */
    public function handle(EmailVerified $event)
    {
        if (method_exists($event->user, 'notify')) {
            $event->user->notify((new \App\Notifications\EmailVerified())->onQueue('mail'));
        }
    }
}
