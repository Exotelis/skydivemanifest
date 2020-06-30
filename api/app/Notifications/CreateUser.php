<?php

namespace App\Notifications;

use App\Mail\CreateUser as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class CreateUser
 * @package App\Notifications
 */
class CreateUser extends Notification implements ShouldQueue
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
     * The auto generated password of the user.
     *
     * @var string|null
     */
    protected $newPassword;

    /**
     * Create a new notification instance.
     *
     * @param  string
     * @return void
     */
    public function __construct($newPassword = null)
    {
        $this->newPassword = $newPassword;
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
        return (new Mailable($notifiable, $this->newPassword))->to($notifiable);
    }
}
