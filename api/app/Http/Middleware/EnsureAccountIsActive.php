<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\CanBeDisabled;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class EnsureAccountIsActive
 *
 * @package App\Http\Middleware
 */
class EnsureAccountIsActive
{
    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    protected $except = [
        'api.logout',
        'api.timezones',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (! is_null($user) &&
            $user instanceof CanBeDisabled &&
            $user->isDisabled() &&
            ! $this->inExceptArray($request)
        ) {
            throw new AuthorizationException(__('error.account_disabled'), 401);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through disabled verification.
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
