<?php

namespace App\Http\Middleware;

use App\Traits\ExceptRoute;
use Closure;

/**
 * Class EnsureTosAreAccepted
 * @package App\Http\Middleware
 */
class EnsureTosAreAccepted
{
    use ExceptRoute;

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
}
