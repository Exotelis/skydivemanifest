<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class ServerHeader
 * @package App\Http\Middleware
 */
class ServerHeader
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
        $response = $next($request);

        $version = config('app.version');
        $name = config('app.name');

        $response->header('Server', sprintf('%s (%s)', $name, $version));
        $response->header('X-Powered-By', sprintf('%s (%s)', $name, $version));

        return $response;
    }
}
