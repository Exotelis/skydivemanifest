<?php

namespace App\Notifications;

use App\Mail\EmailVerified as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class EmailVerified
 * @package App\Notifications
 */
class EmailVerified extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Number of attempts.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Retry after x seconds.
     *
     * @var int
     */
    public $backoff = 90;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => 'mail'
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return Mailable
     */
    public function toMail($notifiable)
    {
        return (new Mailable($notifiable))->to($notifiable);
    }
}
