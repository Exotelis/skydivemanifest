<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'country' => $faker->unique()->country,
        'code'    => $faker->unique()->countryCode,
    ];
});
