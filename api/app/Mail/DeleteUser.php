<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * Class DeleteUser
 * @package App\Mail
 */
class DeleteUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The email address of the deleted user.
     *
     * @var string
     */
    public $email;

    /**
     * The firstName of the deleted user.
     *
     * @var string
     */
    public $firstName;

    /**
     * The locale of the deleted user.
     *
     * @var string
     */
    public $locale;

    /**
     * Create a new message instance.
     *
     * @param  string  $email
     * @param  string  $firstName
     * @param  string  $locale
     * @return void
     */
    public function __construct($email, $firstName, $locale = null)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lang = $this->locale ?? App::getLocale();
        $subject = __('mails.subject_delete_user');

        Log::info("[Mail] 'App\Mail\DeleteUser' has been sent to '{$this->email}'");

        return $this->view('mails.user.delete')
            ->text('mails.user.delete_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'email' => $this->email, 'firstname' => $this->firstName,
            ]);
    }
}
