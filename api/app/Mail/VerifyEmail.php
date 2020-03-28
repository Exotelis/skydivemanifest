<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class VerifyEmail
 * @package App\Mail
 */
class VerifyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The notifiable object.
     *
     * @var mixed
     */
    protected $notifiable;

    /**
     * Email verify token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $notifiable
     * @param  string $token
     * @return void
     */
    public function __construct($notifiable, $token)
    {
        $this->notifiable = $notifiable;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lang = App::getLocale();
        $subject = __('mails.subject_verify_email');

        return $this->view('mails.user.verify')
            ->text('mails.user.verify_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'firstname' => $this->notifiable->firstname,
                'confirmUrl' => frontendUrl() . '/confirm-email?token=' . $this->token,
            ]);
    }
}
