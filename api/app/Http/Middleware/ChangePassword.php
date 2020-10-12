<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\MustChangePassword;
use App\Traits\ExceptRoute;
use Closure;

/**
 * Class ChangePassword
 * @package App\Http\Middleware
 */
class ChangePassword
{
    use ExceptRoute;

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

        if (! \is_null($user) &&
            $user instanceof MustChangePassword &&
            $user->mustChangePassword() &&
            ! $this->inExceptArray($request)
        ) {
            abort(403, __('error.change_password'));
        }

        return $next($request);
    }
}
