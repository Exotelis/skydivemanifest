<?php

namespace App\Notifications;

use App\Mail\ResetPassword as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class ResetPassword
 * @package App\Notifications
 */
class ResetPassword extends Notification implements ShouldQueue
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
     * Reset password token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

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
        return (new Mailable($notifiable, $this->token))->to($notifiable);
    }
}
