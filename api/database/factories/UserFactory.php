<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'default_invoice'   => null,
        'default_shipping'  => null,
        'dob'               => $faker->date($format = 'Y-m-d', $max = 'now'),
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'failed_logins'     => 0,
        'firstname'         => $faker->firstName,
        'gender'            => $faker->randomElement(validGender()),
        'is_active'         => true,
        'last_logged_in'    => now(),
        'lastname'          => $faker->lastName,
        'locale'            => 'en',
        'lock_expires'      => null,
        'middlename'        => $faker->firstName,
        'mobile'            => null,
        'password'          => 'secret',
        'password_change'   => false,
        'phone'             => null,
        'role_id'           => adminRole(),
        'username'          => $faker->unique()->userName,
        'timezone'          => $faker->timezone,
    ];
});

$factory->state(User::class, 'allPermissions', [
    'role_id' => adminRole(),
]);

$factory->state(User::class, 'isInactive', [
    'is_active' => false,
]);

$factory->state(User::class, 'isLocked', [
    'lock_expires' => now()->addMinutes(10),
]);

$factory->state(User::class, 'isNotVerified', [
    'email_verified_at' => null,
]);

$factory->state(User::class, 'isUser', [
    'role_id' => defaultRole(),
]);

$factory->state(User::class, 'passwordChange', [
    'password_change' => true,
]);

$factory->afterCreatingState(User::class, 'allPermissions', function ($user) {
    $user->role->permissions()->detach();
    $user->role->permissions()->attach(factory(\App\Models\Permission::class)->state('all')->create());
});
