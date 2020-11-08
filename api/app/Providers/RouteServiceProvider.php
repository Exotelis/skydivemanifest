<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api/' . apiVersion())
                ->middleware('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        /**
         * Model bindings
         */
        Route::bind('address', function ($address, $route) {
            return $route->user->addresses()->findOrFail($address);
        });
        Route::bind('addressMe', function ($address, $route) {
            return $this->getUser()->addresses()->findOrFail($address);
        });
        Route::bind('aircraft', function ($registration) {
            return \App\Models\Aircraft::withTrashed()->findOrFail($registration);
        });
        Route::bind('aircraftMaintenance', function ($maintenance, $route) {
            return $route->aircraft->maintenance()->findOrFail($maintenance);
        });
        Route::model('country', \App\Models\Country::class);
        Route::model('currency', \App\Models\Currency::class);
        Route::model('qualification', \App\Models\Qualification::class);
        Route::bind('region', function ($region, $route) {
            return $route->country->regions()->findOrFail($region);
        });
        Route::model('role', \App\Models\Role::class);
        Route::model('user', \App\Models\User::class);
        Route::bind('userDeleted', function ($user, $route) {
            return \App\Models\User::withTrashed()->findOrFail($user);
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }

    /**
     * Get te current signed in user
     *
     * @return \App\Models\User
     */
    protected function getUser()
    {
        $user = Auth::user();
        if (! ($user instanceof \App\Models\User)) {
            abort(500);
        }

        return $user;
    }
}
