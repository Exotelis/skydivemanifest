<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\MustChangePassword;
use Closure;

/**
 * Class ChangePassword
 * @package App\Http\Middleware
 */
class ChangePassword
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
        'api.tos',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (! is_null($user) &&
            $user instanceof MustChangePassword &&
            $user->mustChangePassword() &&
            ! $this->inExceptArray($request)
        ) {
            abort(403, __('error.change_password'));
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through password change verification.
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
