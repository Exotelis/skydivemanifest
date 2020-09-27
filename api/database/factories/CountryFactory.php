<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class CountryFactory
 * @package Database\Factories
 */
class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country' => $this->faker->unique()->country,
            'code'    => $this->faker->unique()->countryCode,
        ];
    }
}
