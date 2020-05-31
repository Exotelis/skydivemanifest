<?php

namespace App\Contracts\Auth;

/**
 * Interface CanBeDisabled
 * @package App\Contracts\Auth
 */
interface CanBeDisabled
{
    /**
     * Determine if the user is disabled.
     *
     * @return bool
     */
    public function isDisabled();
}
