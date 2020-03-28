<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * Class Authenticate
 * @package App\Http\Middleware
 */
class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string[] ...$guards
     * @return mixed
     *
     * @throws AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        /**
         * There are basically two ways to get authenticated.
         * Either with the authorization header or with the two cookie approach.
         * See the README.md for more details.
         */
        if (! $request->hasHeader('authorization') &&
            $request->hasHeader('X-XSRF-TOKEN') &&
            $request->hasCookie('XSRF-TOKEN') &&
            $request->hasCookie('AUTH-TOKEN')
        ) {
            $xsrfToken = $request->header('X-XSRF-TOKEN');

            if ($xsrfToken === $request->cookie('XSRF-TOKEN')) {
                $token = $xsrfToken . '.' . $request->cookie('AUTH-TOKEN');
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(__('error.401'), $guards, $this->redirectTo($request));
    }
}
