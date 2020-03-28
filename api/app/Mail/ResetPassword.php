<?php

namespace App\Mail;

use App\Traits\ResetPassword as ResetPasswordTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class ResetPassword
 * @package App\Mail
 */
class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, ResetPasswordTrait, SerializesModels;

    /**
     * The notifiable object.
     *
     * @var mixed
     */
    protected $notifiable;

    /**
     * The token to reset the password
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
        $subject = $this->getEmailSubject();

        return $this->view('mails.password.reset')
            ->text('mails.password.reset_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'firstname' => $this->notifiable->firstname,
                'resetUrl' => frontendUrl() . '/reset-password?token=' . $this->token,
            ]);
    }
}
