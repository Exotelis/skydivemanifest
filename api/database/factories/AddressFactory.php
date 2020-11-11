<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AddressFactory
 * @package Database\Factories
 *
 * @method Factory forUser()
 */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Create some countries of none exist
        if (Country::all()->count() === 0) {
            Country::factory()->count(5)->hasRegions(\rand(3, 10))->create();
        }

        $country = Country::all()->random();

        return [
            'city'       => $this->faker->city,
            'company'    => $this->faker->optional(25)->company,
            'country_id' => $country->id,
            'firstname'  => $this->faker->firstName,
            'lastname'   => $this->faker->lastName,
            'middlename' => $this->faker->optional(25)->firstName,
            'postal'     => $this->faker->postcode,
            'region_id'  => $country->regions->random()->id,
            'street'     => $this->faker->streetAddress,
            'user_id'    => User::factory(),
        ];
    }
}
