<?php

namespace App\Listeners\User;

use App\Events\User\Delete as Event;
use App\Mail\DeleteUser as Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class Deleted
 * @package App\Listeners\User
 */
class Deleted implements ShouldQueue
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
        $db = $event->deletedBy;

        $mail = (new Mailable($event->email, $event->firstname, $event->locale))->onQueue('mail');
        Mail::to($event->email)->send($mail);

        Log::info("User: '{$event->id}|{$event->email}' has been deleted by: '{$db->id}|{$db->email}'");
    }
}
