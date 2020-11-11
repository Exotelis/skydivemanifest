<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class RegionFactory
 * @package Database\Factories
 *
 * @method Factory forCountry()
 */
class RegionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Region::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'region'     => $this->faker->unique(true)->stateAbbr . ' ' . $this->faker->unique(true)->state,
        ];
    }
}
