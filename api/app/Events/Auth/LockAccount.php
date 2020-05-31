<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

/**
 * Class LockAccount
 * @package App\Events\Auth
 */
class LockAccount
{
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Contracts\Auth\CanBeLockedTemporarily
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Contracts\Auth\CanBeLockedTemporarily $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
