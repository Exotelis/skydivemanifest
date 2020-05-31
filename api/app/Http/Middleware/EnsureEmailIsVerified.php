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
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail && ! $request->user()->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, __('error.email_not_verified'))
                : Redirect::route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }
}
