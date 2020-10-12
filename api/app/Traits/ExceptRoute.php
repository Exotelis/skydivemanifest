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
        'api.recover-user-with-token',
        'api.timezones',
        'api.tos',
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
