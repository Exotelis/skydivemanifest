<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class EmailVerified
 * @package App\Mail
 */
class EmailVerified extends Mailable implements ShouldQueue
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
        $subject = __('mails.subject_email_verified');

        return $this->view('mails.user.verified')
            ->text('mails.user.verified_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'firstname' => $this->notifiable->firstname,
                'frontend'  => frontendUrl(),
            ]);
    }
}
