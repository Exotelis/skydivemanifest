<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null, ['prefix' => 'api/'. apiVersion() . '/oauth']);
        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDays(2));
        Passport::personalAccessTokensExpireIn(now()->addYears(10));

        $this->defineScopes();
    }

    /**
     * Define scopes which represents user permissions.
     *
     * @return void
     */
    protected function defineScopes()
    {
        try {
            $validScopes = Permission::all()->pluck('name', 'slug')->all();
        } catch (\Exception $exception) {
            $validScopes = [];
        }

        $defaultScopes = [];
        Passport::tokensCan($validScopes);
        Passport::setDefaultScope($defaultScopes);
    }
}
