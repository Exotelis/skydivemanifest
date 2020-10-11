<?php

namespace App\Traits;

use App\Http\Requests\Password\ForgotRequest;
use App\Http\Requests\Password\ResetRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

/**
 * Trait ResetPassword
 * @package App\Traits
 */
trait ResetPassword
{
    /**
     * Send a reset link to the given user.
     *
     * @param ForgotRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postEmail(ForgotRequest $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param ForgotRequest $request
     * @return \Symfony\Component\HttpFoundation\Response|void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function sendResetLinkEmail(ForgotRequest $request)
    {
        $broker = $this->getBroker();
        $validator = Validator::make($request->only('username'), [
            'username' => 'email'
        ]);

        if ($validator->fails()) {
            $credentials = $request->only('username');
        } else {
            $credentials = ['email' => $request->only('username')['username']];
        }

        $response = Password::broker($broker)->sendResetLink($credentials);

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse();

            case 'passwords.throttled':
                $this->getSendResetLinkEmailThrottleResponse();

            case Password::INVALID_USER:
            default:
                $this->getSendResetLinkEmailFailureResponse();
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return $this->subject ?? __('mails.subject_reset_password');
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse()
    {
        return response()->json(['message' => __('passwords.sent')]);
    }

    /**
     * Get the response for after the reset link cannot be requested so soon again.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function getSendResetLinkEmailThrottleResponse()
    {
        $throttle = config('auth.passwords.' . $this->broker . '.throttle');
        $time =  Carbon::now()->subSeconds($throttle)->longAbsoluteDiffForHumans();

        abort(429, __('passwords.throttled', ['time' => $time]));
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getSendResetLinkEmailFailureResponse()
    {
        abort(400, __('passwords.failed'));
    }

    /**
     * Reset the given user's password.
     *
     * @param ResetRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postReset(ResetRequest $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param ResetRequest $request
     * @return \Symfony\Component\HttpFoundation\Response|void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function reset(ResetRequest $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse();

            default:
                $this->getResetFailureResponse();
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword|\App\Models\User $user
     * @param string $password
     *
     * @return bool
     */
    protected function resetPassword($user, $password)
    {
        event(new \App\Events\Auth\PasswordReset($user));
        $user->password = $password;
        $user->password_change = false;
        $user->lock_expires = null;

        return $user->save();
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse()
    {
        return response()->json(['message' => __('passwords.reset')]);
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function getResetFailureResponse()
    {
        abort(400, __('passwords.token'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }
}
