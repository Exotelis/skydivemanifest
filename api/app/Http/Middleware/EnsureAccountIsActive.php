<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\CanBeDisabled;
use App\Traits\ExceptRoute;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class EnsureAccountIsActive
 *
 * @package App\Http\Middleware
 */
class EnsureAccountIsActive
{
    use ExceptRoute;

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

        if (! \is_null($user) &&
            $user instanceof CanBeDisabled &&
            $user->isDisabled() &&
            ! $this->inExceptArray($request)
        ) {
            throw new AuthorizationException(__('error.account_disabled'), 401);
        }

        return $next($request);
    }
}
