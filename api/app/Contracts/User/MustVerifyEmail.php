<?php

namespace App\Contracts\User;

/**
 * Interface MustVerifyEmail
 * @package App\Contracts\User
 */
interface MustVerifyEmail
{
    /**
     * Delete an existing email address change request.
     *
     * @return bool
     */
    public function deleteExistingEmailChangeRequest();

    /**
     * Determine if the user has a pending email verification request.
     *
     * @return bool
     */
    public function hasPendingEmailVerification();

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail();

    /**
     * Mark email as verified when validation was successful.
     *
     * @param  $token
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function markEmailAsVerified($token);

    /**
     * Resend the email verification notification.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendEmailVerificationNotification();

    /**
     * Send the email verification notification.
     *
     * @param  string $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailVerificationNotification($email);
}
