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
        'email_verified_at' => $faker->optional(80)->dateTime($max = 'now', $timezone = 'UTC'),
        'failed_logins'     => 0,
        'firstname'         => $faker->firstName,
        'gender'            => $faker->randomElement(validGender()),
        'is_active'         => $faker->boolean(80),
        'last_logged_in'    => $faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = 'UTC'),
        'lastname'          => $faker->lastName,
        'locale'            => 'en',
        'lock_expires'      => null,
        'middlename'        => $faker->optional(25)->firstName,
        'mobile'            => $faker->optional()->phoneNumber,
        'password'          => 'secret',
        'password_change'   => $faker->boolean(20),
        'phone'             => $faker->optional()->e164PhoneNumber,
        'role_id'           => $faker->randomElement([adminRole(), defaultRole()]),
        'username'          => $faker->unique()->userName,
        'timezone'          => $faker->timezone,
    ];
});

$factory->state(User::class, 'allPermissions', [
    'role_id' => adminRole(),
]);

$factory->state(User::class, 'isActive', [
    'is_active' => true,
]);

$factory->state(User::class, 'isAdmin', [
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

$factory->state(User::class, 'isVerified', [
    'email_verified_at' => '1970-01-01 12:00:00',
]);

$factory->state(User::class, 'noPasswordChange', [
    'password_change' => false,
]);

$factory->state(User::class, 'noPermissions', [
    'role_id' => defaultRole(),
]);

$factory->state(User::class, 'passwordChange', [
    'password_change' => true,
]);

$factory->afterCreatingState(User::class, 'allPermissions', function ($user) {
    $user->role->permissions()->detach();
    $user->role->permissions()->attach(factory(\App\Models\Permission::class)->state('all')->create());
});

$factory->afterCreatingState(User::class, 'noPermissions', function ($user) {
    $user->role->permissions()->detach();
});
