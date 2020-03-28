<?php

namespace App\Traits;

use App\Notifications\LockAccount as LockAccountNotification;
use Illuminate\Support\Carbon;

/**
 * Trait CanBeLockedTemporarily
 * @package App\Traits
 */
trait CanBeLockedTemporarily
{
    /**
     * Increase the failed login counter.
     *
     * @return void
     */
    public function failedLoginAttempt()
    {
        if ($this->isLocked()) {
            return;
        }

        $this->failed_logins++;

        if ($this->failed_logins >= lockAccountAfter()) {
            $this->lock_expires = Carbon::now()->addMinutes(lockAccountFor());
            $this->failed_logins = 0;
            $this->save();

            event(new \App\Events\Auth\LockAccount($this));
            $this->sendLockNotification();
        }

        $this->save();
    }

    /**
     * Determine if the user is locked.
     *
     * @return bool
     */
    public function isLocked()
    {
        return ! is_null($this->lock_expires) && Carbon::parse($this->lock_expires)->isFuture();
    }

    /**
     * Return the time when the log expire.
     *
     * @return Carbon|null
     */
    public function lockExpires()
    {
        return $this->lock_expires;
    }

    /**
     * Reset the failed login attempts to 0.
     *
     * @return bool
     */
    public function resetLoginAttempts()
    {
        $this->failed_logins = 0;

        return $this->save();
    }

    /**
     * Send a notification to inform the user that the account has been locked.
     *
     * @return void
     */
    public function sendLockNotification()
    {
        $this->notify((new LockAccountNotification())->onQueue('mail'));
    }
}
