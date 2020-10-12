<?php

namespace App\Http\Controllers;

use App\Facades\VerifyEmail;
use App\Http\Requests\Email\ConfirmRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ConfirmEmailController
 * @package App\Http\Controllers
 */
class ConfirmEmailController extends Controller
{
    /**
     * Confirm the email address.
     *
     * @param ConfirmRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(ConfirmRequest $request)
    {
        $input = $request->only(['token']);

        $user = VerifyEmail::findUser($input['token']);

        if (\is_null($user)) {
            abort(400, __('error.email_token_invalid'));
        }

        /** @var \App\Models\User $user */
        return $user->markEmailAsVerified($input['token']);
    }

    /**
     * Delete a pending email change request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if (! $user->deleteExistingEmailChangeRequest()) {
            abort('400', __('error.email_token_not_found'));
        }

        return response()->json(['message' => __('messages.email_token_deleted')]);
    }

    /**
     * Resend the verification email.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend()
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        return $user->resendEmailVerificationNotification();
    }
}
