<?php

namespace App\Contracts\Auth;

/**
 * Interface MustChangePassword
 * @package App\Contracts\Auth
 */
interface MustChangePassword
{
    /**
     * Determine if the password must be changed.
     *
     * @return bool
     */
    public function mustChangePassword();
}
