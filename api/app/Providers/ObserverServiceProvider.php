<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ObserverServiceProvider
 * @package App\Providers
 */
class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Address::observe(\App\Observers\AddressObserver::class);
        \App\Models\Aircraft::observe(\App\Observers\AircraftObserver::class);
        \App\Models\AircraftMaintenance::observe(\App\Observers\AircraftMaintenanceObserver::class);
        \App\Models\Country::observe(\App\Observers\CountryObserver::class);
        \App\Models\Currency::observe(\App\Observers\CurrencyObserver::class);
        \App\Models\Qualification::observe(\App\Observers\QualificationObserver::class);
        \App\Models\Region::observe(\App\Observers\RegionObserver::class);
        \App\Models\Role::observe(\App\Observers\RoleObserver::class);
        \App\Models\Text::observe(\App\Observers\TextObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Waiver::observe(\App\Observers\WaiverObserver::class);
    }
}
