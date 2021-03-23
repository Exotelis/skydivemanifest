<?php

namespace Database\Factories;

use App\Models\Aircraft;
use App\Models\AircraftLogbook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AircraftLogbookFactory
 * @package Database\Factories
 *
 * @method Factory hasItems($int)
 */
class AircraftLogbookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AircraftLogbook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'aircraft_registration' => Aircraft::factory(),
            'transfer'              => $this->faker->numberBetween(0, 30000),
        ];
    }

    /**
     * High transfer.
     *
     * @return Factory
     */
    public function highTransfer(): Factory
    {
        return $this->state([
            'transfer' => $this->faker->numberBetween(10000, 50000),
        ]);
    }
}
