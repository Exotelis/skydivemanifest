<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class EnsureTosAreAccepted
 * @package App\Http\Middleware
 */
class EnsureTosAreAccepted
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
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (! \is_null($user) &&
            $user instanceof \App\Models\User &&
            ! $user->tosAccepted() &&
            ! $this->inExceptArray($request)
        ) {
            abort(403, __('error.tos_not_accepted'));
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through tos accepted verification.
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
