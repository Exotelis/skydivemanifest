<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'color'     => $faker->hexColor,
        'deletable' => true,
        'editable'  => true,
        'name'      => $faker->unique()->word,
    ];
});

$factory->state(Role::class, 'notDeletable', [
    'deletable' => false,
]);

$factory->state(Role::class, 'notEditable', [
    'editable' => false,
]);
