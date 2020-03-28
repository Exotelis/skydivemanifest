<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

/**
 * Class PasswordReset
 * @package App\Events\Auth
 */
class PasswordReset
{
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var \Illuminate\Contracts\Auth\CanResetPassword
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
