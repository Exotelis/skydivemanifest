<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class UserFactory
 * @package Database\Factories
 *
 * @method Factory hasAddresses($int)
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // If user has addresses
            if (\count($user->addresses) > 0) {
                // Set default_invoice
                if ($this->faker->boolean(80)) {
                    $user->defaultInvoice()->associate($user->addresses->random());
                }

                // Set default_shipping
                if ($this->faker->boolean(80)) {
                    $user->defaultShipping()->associate($user->addresses->random());
                }

                $user->save();
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'default_invoice'   => null,
            'default_shipping'  => null,
            'dob'               => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'email'             => $this->faker->unique()->freeEmail,
            'email_verified_at' => $this->faker->optional(80)->dateTime($max = 'now', $timezone = 'UTC'),
            'failed_logins'     => 0,
            'firstname'         => $this->faker->firstName,
            'gender'            => $this->faker->randomElement(validGender()),
            'is_active'         => $this->faker->boolean(80),
            'last_logged_in'    => $this->faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = 'UTC'),
            'lastname'          => $this->faker->lastName,
            'locale'            => 'en',
            'lock_expires'      => null,
            'middlename'        => $this->faker->optional(25)->firstName,
            'mobile'            => $this->faker->optional()->phoneNumber,
            'password'          => 'secret',
            'password_change'   => $this->faker->boolean(20),
            'phone'             => $this->faker->optional()->e164PhoneNumber,
            'role_id'           => defaultRole(),
            'username'          => $this->faker->unique()->regexify('[A-Za-z0-9]{5,20}'),
            'timezone'          => $this->faker->timezone,
            'tos'               => $this->faker->boolean(90),
        ];
    }

    /**
     * Create an admin user.
     *
     * @return Factory
     */
    public function createAdmin()
    {
        return $this->state([
            'email_verified_at' => '1970-01-01 12:00:00',
            'is_active'         => true,
            'password'          => 'admin',
            'password_change'   => false,
            'role_id'           => adminRole(),
            'username'          => 'admin',
            'tos'               => true,
        ]);
    }

    /**
     * Create an user.
     *
     * @return Factory
     */
    public function createUser()
    {
        return $this->state([
            'email_verified_at' => '1970-01-01 12:00:00',
            'is_active'         => true,
            'password'          => 'user',
            'password_change'   => false,
            'role_id'           => userRole(),
            'username'          => 'user',
            'tos'               => true,
        ]);
    }

    /**
     * Create an active user without any username.
     *
     * @return Factory
     */
    public function createActiveUser()
    {
        return $this->state([
            'email_verified_at' => '1970-01-01 12:00:00',
            'is_active'         => true,
            'password_change'   => false,
            'role_id'           => userRole(),
            'tos'               => true,
        ]);
    }

    /**
     * Indicate that the user is active.
     *
     * @return Factory
     */
    public function isActive()
    {
        return $this->state([
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is admin and got all permissions.
     *
     * @return Factory
     */
    public function isAdmin()
    {
        return $this->state([
            'role_id' => adminRole(),
        ]);
    }

    /**
     * Indicate that the user is inactive.
     *
     * @return Factory
     */
    public function isInactive()
    {
        return $this->state([
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user account is locked.
     *
     * @return Factory
     */
    public function isLocked()
    {
        return $this->state([
            'lock_expires' => now()->addMinutes(10),
        ]);
    }

    /**
     * Indicate that the users email address is not yet verified.
     *
     * @return Factory
     */
    public function isNotVerified()
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user has only the default permissions.
     *
     * @return Factory
     */
    public function isUser()
    {
        return $this->state([
            'role_id' => defaultRole(),
        ]);
    }

    /**
     * Indicate that the users email address is verified.
     *
     * @return Factory
     */
    public function isVerified()
    {
        return $this->state([
            'email_verified_at' => '1970-01-01 12:00:00',
        ]);
    }

    /**
     * Indicate that the user don't need to change the password.
     *
     * @return Factory
     */
    public function noPasswordChange()
    {
        return $this->state([
            'password_change' => false,
        ]);
    }

    /**
     * Indicate that the user need to change the password.
     *
     * @return Factory
     */
    public function passwordChange()
    {
        return $this->state([
            'password_change' => true,
        ]);
    }

    /**
     * Indicate that the user accepted the terms of service.
     *
     * @return Factory
     */
    public function tosAccepted()
    {
        return $this->state([
            'tos' => true,
        ]);
    }

    /**
     * Indicate that the user hasn't accepted the terms of service.
     *
     * @return Factory
     */
    public function tosNotAccepted()
    {
        return $this->state([
            'tos' => false,
        ]);
    }
}
