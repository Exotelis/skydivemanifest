<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class CreateUser
 * @package App\Mail
 */
class CreateUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The notifiable object.
     *
     * @var mixed
     */
    protected $notifiable;

    /**
     * The auto generated password of the user.
     *
     * @var string|null
     */
    protected $newPassword;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $notifiable
     * @param  string $newPassword
     * @return void
     */
    public function __construct($notifiable, $newPassword = null)
    {
        $this->notifiable = $notifiable;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lang = App::getLocale();
        $subject = __('mails.subject_create_user');

        return $this->view('mails.user.create')
            ->text('mails.user.create_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'delete'    => deleteUnverifiedUsers(),
                'firstname' => $this->notifiable->firstname,
                'frontend'  => frontendUrl(),
                'password'  => $this->newPassword,
            ]);
    }
}
