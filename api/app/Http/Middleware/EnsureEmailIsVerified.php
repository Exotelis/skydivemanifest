<?php

namespace App\Http\Middleware;

use App\Contracts\User\MustVerifyEmail;
use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as EnsureEmailIsVerifiedIlluminate;
use Illuminate\Support\Facades\Redirect;

/**
 * Class EnsureEmailIsVerified
 * @package App\Http\Middleware
 */
class EnsureEmailIsVerified extends EnsureEmailIsVerifiedIlluminate
{
    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    protected $except = [
        'api.accept-tos',
        'api.change-password',
        'api.confirm-email',
        'api.delete-email-request',
        'api.forgot-password',
        'api.login',
        'api.logout',
        'api.refresh',
        'api.register',
        'api.resend-email-request',
        'api.reset-password',
        'api.timezones',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $user = $request->user();

        if (! \is_null($user) &&
            $user instanceof MustVerifyEmail &&
            ! $user->hasVerifiedEmail() &&
            ! $this->inExceptArray($request)
        ) {
            return $request->expectsJson()
                ? abort(403, __('error.email_not_verified'))
                : Redirect::route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through email verified verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except) || $request->routeIs($except)) {
                return true;
            }
        }

        return false;
    }
}
