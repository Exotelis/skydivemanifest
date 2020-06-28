<?php

namespace App\Listeners\User;

use App\Notifications\CreateUser as CreateUserNotification;
use Illuminate\Auth\Events\Registered as Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class Registered
 * @package App\Listeners\User
 */
class Registered implements ShouldQueue
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
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = $event->user;

        if ($user instanceof \App\Models\User) {
            $user->notify(new CreateUserNotification());
            Log::info("New user has been registered: '{$user->id}|{$user->email}'");
        }

        if ($user instanceof \App\Contracts\User\MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification($user->email);
        }
    }
}
