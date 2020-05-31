<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

/**
 * Class EmailVerified
 * @package App\Events\Auth
 */
class EmailVerified
{
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Contracts\User\MustVerifyEmail
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Contracts\User\MustVerifyEmail $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
