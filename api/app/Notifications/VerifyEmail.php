<?php

namespace App\Notifications;

use App\Mail\VerifyEmail as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class VerifyEmail
 * @package App\Notifications
 */
class VerifyEmail extends Notification implements ShouldQueue
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
    public $retryAfter = 90;

    /**
     * Stop child process after x seconds.
     *
     * @var int
     */
    public $timeout = 75;

    /**
     * Email that should be verified
     *
     * @var string
     */
    protected $email;

    /**
     * Email verify token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param  string $email
     * @param  string $token
     * @return void
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return Mailable
     */
    public function toMail($notifiable)
    {
        return (new Mailable($notifiable, $this->token))->to($this->email, $notifiable->name);
    }
}
