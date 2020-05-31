<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        // 3rd party Events

        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\User\Registered',
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'App\Listeners\LogNotification',
        ],
        'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Listeners\Auth\RevokeOldTokens',
        ],

        // App Events

        'App\Events\Auth\EmailVerified' => [
            'App\Listeners\Auth\LogEmailVerified',
            'App\Listeners\Auth\SendEmailVerifiedNotification',
        ],
        'App\Events\Auth\LockAccount' => [
            'App\Listeners\Auth\LogLockAccount',
        ],
        'App\Events\Auth\PasswordReset' => [
            'App\Listeners\Auth\LogPasswordReset',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
