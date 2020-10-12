<?php

namespace App\Traits;

use App\Facades\VerifyEmail;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Carbon;

/**
 * Trait
 * @package App\Traits
 */
trait MustVerifyEmail
{
    /**
     * Delete an existing email address change request.
     *
     * @return bool
     */
    public function deleteExistingEmailChangeRequest()
    {
        return VerifyEmail::config($this->getEmailConfiguration())->delete($this->email);
    }

    /**
     * Determine if the user has a pending email verification request.
     *
     * @return bool
     */
    public function hasPendingEmailVerification()
    {
        return VerifyEmail::config($this->getEmailConfiguration())->exists($this->email);
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! \is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified when validation was successful.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function markEmailAsVerified($token)
    {
        if (VerifyEmail::verify($token, $this)) {
            event(new \App\Events\Auth\EmailVerified($this));
            return response()->json(['message' => __('messages.email_verified')]);
        }

        abort(500, __('error.500'));
    }

    /**
     * Resend the email verification notification.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendEmailVerificationNotification()
    {
        $request = VerifyEmail::get($this->email);

        if (\is_null($request)) {
            abort('400', __('error.email_token_not_found'));
        }

        if (VerifyEmail::createdRecently($this->email)) {
            $time =  Carbon::now()->subSeconds(VerifyEmail::throttle())->longAbsoluteDiffForHumans();
            abort(429, __('error.email_token_throttled', ['time' => $time]));
        }

        $token = VerifyEmail::create($this->email, $request->new_email);

        $this->notify(new VerifyEmailNotification($request->new_email, $token));

        return response()->json(['message' => __('messages.email_sent')]);
    }

    /**
     * Send the email verification notification.
     *
     * @param  string $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailVerificationNotification($email)
    {
        $token = VerifyEmail::create($this->email, $email);

        $delay = Carbon::now()->addSeconds(15);
        $this->notify((new VerifyEmailNotification($email, $token))->delay($delay));

        return response()->json(['message' => __('messages.email_sent')]);
    }

    /**
     * Get the configuration to be used during email changes.
     *
     * @return string|null
     */
    protected function getEmailConfiguration()
    {
        return property_exists($this, 'emailConfiguration') ? $this->emailConfiguration : null;
    }
}
