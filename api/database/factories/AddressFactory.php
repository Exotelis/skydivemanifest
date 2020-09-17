<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use App\Models\Country;
use App\Models\Region;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    $countryId = array_rand(array_flip(Country::all()->getQueueableIds()));
    $regionId = array_rand(array_flip(Region::where('country_id', $countryId)->get()->getQueueableIds()));

    return [
        'city'       => $faker->city,
        'company'    => $faker->optional(25)->company,
        'country_id' => $countryId,
        'firstname'  => $faker->firstName,
        'lastname'   => $faker->lastName,
        'middlename' => $faker->optional(25)->firstName,
        'postal'     => $faker->postcode,
        'region_id'  => $regionId,
        'street'     => $faker->streetAddress,
    ];
});
