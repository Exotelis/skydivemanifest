<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class VerifyEmailProvider
 * @package App\Providers
 */
class VerifyEmailProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('verifyemail', function() {
            return new \App\Helpers\VerifyEmail;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {}
}
