<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('database.default_string_length') !== 255) {
            Schema::defaultStringLength(191);
        }

        // Add validation rules
        Validator::extendImplicit('not_present', function ($attribute, $value, $parameters, $validator) {
            return ! Arr::exists($validator->getData(), $attribute);
        });
    }
}
