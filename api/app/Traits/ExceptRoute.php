<?php

namespace App\Traits;

/**
 * Trait ExceptRoute
 * @package App\Traits
 */
trait ExceptRoute
{
    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    protected $except = [
        'api.auth.accept-tos',
        'api.auth.change-password',
        'api.auth.confirm-email',
        'api.auth.delete-email-request',
        'api.auth.forgot-password',
        'api.auth.login',
        'api.auth.logout',
        'api.auth.refresh',
        'api.auth.register',
        'api.auth.resend-email-request',
        'api.auth.reset-password',
        'api.auth.recover-user-with-token',
        'api.auth.timezones',
        'api.auth.tos',
    ];

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
