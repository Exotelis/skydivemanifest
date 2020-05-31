<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

/**
 * Class LogNotification
 * @package App\Listeners
 */
class LogNotification
{
    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        $notification = get_class($event->notification);
        $user = $event->notifiable;

        Log::info("'{$notification}' notification has been sent to user '{$user->id}|{$user->email}'");
    }
}
