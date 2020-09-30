<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AddressFactory
 * @package Database\Factories
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
        $countryId = array_rand(array_flip(Country::all()->getQueueableIds()));
        $regionId = array_rand(array_flip(Region::where('country_id', $countryId)->get()->getQueueableIds()));

        return [
            'city'       => $this->faker->city,
            'company'    => $this->faker->optional(25)->company,
            'country_id' => $countryId,
            'firstname'  => $this->faker->firstName,
            'lastname'   => $this->faker->lastName,
            'middlename' => $this->faker->optional(25)->firstName,
            'postal'     => $this->faker->postcode,
            'region_id'  => $regionId,
            'street'     => $this->faker->streetAddress,
        ];
    }
}
