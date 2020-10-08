<?php

namespace App\Listeners\User;

use App\Events\User\Create as Event;
use App\Notifications\CreateUser as CreateUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class Created
 * @package App\Listeners\User
 */
class Created implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $cb = $event->createdBy;
        $user = $event->user;

        if ($user instanceof \App\Models\User) {
            $user->notify(new CreateUserNotification($event->password));
            Log::info("New user: '{$user->id}|{$user->email}' has been created by: '{$cb->id}|{$cb->email}'");
        }

        if ($user instanceof \App\Contracts\User\MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification($user->email);
        }
    }
}
