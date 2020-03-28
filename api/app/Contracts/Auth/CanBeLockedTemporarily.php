<?php

namespace App\Contracts\Auth;

/**
 * Interface CanBeLockedTemporarily
 * @package App\Contracts\Auth
 */
interface CanBeLockedTemporarily
{
    /**
     * Increase the failed login counter.
     *
     * @return void
     */
    public function failedLoginAttempt();

    /**
     * Determine if the user is locked.
     *
     * @return bool
     */
    public function isLocked();

    /**
     * Return the time when the log expire.
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function lockExpires();

    /**
     * Reset the failed login attempts to 0.
     *
     * @return bool
     */
    public function resetLoginAttempts();

    /**
     * Send a notification to inform the user that the account has been locked.
     *
     * @return void
     */
    public function sendLockNotification();
}
