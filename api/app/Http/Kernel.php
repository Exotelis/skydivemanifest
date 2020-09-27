<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\Localization::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            //\App\Http\Middleware\EncryptCookies::class,
            //\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            //\Illuminate\Session\Middleware\StartSession::class,
            //\Illuminate\Session\Middleware\AuthenticateSession::class,
            //\Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
            //\Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'account.active',
            'csp.headers',
            'disable.cache',
            'log.requests',
            'password.change',
            'security.headers',
            'server.headers',
            'throttle:60,1',
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'account.active'   => \App\Http\Middleware\EnsureAccountIsActive::class,
        'auth'             => \App\Http\Middleware\Authenticate::class,
        'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'csp.headers'      => \App\Http\Middleware\ContentSecurityPolicyHeaders::class,
        'can'              => \Illuminate\Auth\Middleware\Authorize::class,
        'disable.cache'    => \App\Http\Middleware\DisableCache::class,
        'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'log.requests'     => \App\Http\Middleware\LogRequests::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'password.change'  => \App\Http\Middleware\ChangePassword::class,
        'scope'            => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'scopes'           => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        'server.headers'   => \App\Http\Middleware\ServerHeader::class,
        'signed'           => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'         => \App\Http\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * Forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        //\Illuminate\Session\Middleware\StartSession::class,
        //\Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\Authenticate::class,
        \App\Http\Middleware\EnsureEmailIsVerified::class,
        \App\Http\Middleware\EnsureAccountIsActive::class,
        \App\Http\Middleware\ChangePassword::class,
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\ServerHeader::class,
        \App\Http\Middleware\ContentSecurityPolicyHeaders::class,
        \App\Http\Middleware\DisableCache::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        //\Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        //\Illuminate\Auth\Middleware\Authorize::class,
    ];
}
