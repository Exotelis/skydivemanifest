<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'is_default' => false,
        'name'       => $faker->word,
        'slug'       => $faker->slug,
    ];
});

$factory->state(Permission::class, 'all', [
    'name' => 'All',
    'slug' => '*',
]);

$factory->state(Permission::class, 'default', [
    'is_default' => true,
]);
