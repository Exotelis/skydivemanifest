<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class LockAccount
 * @package App\Mail
 */
class LockAccount extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The notifiable object.
     *
     * @var mixed
     */
    protected $notifiable;

    /**
     * Create a new message instance.
     *
     * @param  mixed $notifiable
     * @return void
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lang = App::getLocale();
        $timezone = $this->notifiable->timezone ?? appTimezone();

        return $this->view('mails.user.lock')
            ->text('mails.user.lock_plain')
            ->locale($lang)
            ->subject(__('mails.subject_lock_account'))
            ->with([
                'expire'    => $this->notifiable->lockExpires()->timezone($timezone)->isoFormat('LLL'),
                'firstname' => $this->notifiable->firstname,
                'forgotUrl' => frontendUrl() . '/password-forgot?email=' . $this->notifiable->email,
                'timezone'  => $timezone,
            ]);
    }
}
