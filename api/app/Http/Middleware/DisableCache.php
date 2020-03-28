<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class DisableCache
 * @package App\Http\Middleware
 */
class DisableCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0, private',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
            'Pragma' => 'no-cache',
        ]);
    }
}
