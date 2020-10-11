<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class SoftDeleteUser
 * @package App\Mail
 */
class SoftDeleteUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The notifiable object.
     *
     * @var mixed
     */
    protected $notifiable;

    /**
     * The token to restore the user.
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
        $subject = __('mails.subject_soft_delete_user');

        return $this->view('mails.user.soft-delete')
            ->text('mails.user.soft-delete_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'days'       => recoverUsers(),
                'firstname' => $this->notifiable->firstname,
                'recoverUrl' => frontendUrl() . '?recover-token=' . $this->token,
            ]);
    }
}
