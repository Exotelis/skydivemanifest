<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class ContentSecurityPolicyHeaders
 * @package App\Http\Middleware
 */
class ContentSecurityPolicyHeaders
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

        $response->header(
            'Content-Security-Policy',
            "default-src 'none', connect-src 'self', 'upgrade-insecure-requests';"
        );

        return $response;
    }
}
