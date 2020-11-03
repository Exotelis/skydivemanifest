<?php

namespace App\Http\Middleware;

use App\Contracts\User\MustVerifyEmail;
use App\Traits\ExceptRoute;
use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as EnsureEmailIsVerifiedIlluminate;
use Illuminate\Support\Facades\Redirect;

/**
 * Class EnsureEmailIsVerified
 * @package App\Http\Middleware
 */
class EnsureEmailIsVerified extends EnsureEmailIsVerifiedIlluminate
{
    use ExceptRoute;

    /**
     * Create a new EnsureEmailIsVerified instance.
     */
    public function __construct()
    {
        // Remove route from expect
        $pos = \array_search('api.auth.delete-email-request', $this->except);
        if ($pos !== false) {
            unset($this->except[$pos]);
        }
    }

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
}
