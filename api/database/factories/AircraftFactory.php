<?php

namespace Database\Factories;

use App\Models\Aircraft;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AircraftFactory
 * @package Database\Factories
 *
 * @method Factory hasLogbookItems($int)
 * @method Factory hasMaintenance($int)
 */
class AircraftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Aircraft::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'dom'                   => $this->faker->optional(90)->date('Y-m-d', '-1 week'),
            'model'                 => $this->faker->randomElement(['Cessna', 'Porter']),
            'put_out_of_service_at' => $this->faker->optional(10)
                ->dateTimeBetween('-10 years', 'now', 'UTC'),
            'registration'          => $this->faker->unique()->regexify('D-[EG][A-Z]{3}'),
            'seats'                 => $this->faker->numberBetween(1, 20),
        ];
    }

    /**
     * Put the aircraft out of service.
     *
     * @return Factory
     */
    public function putOutOfService(): Factory
    {
        return $this->state([
            'put_out_of_service_at' => $this->faker
                ->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = 'UTC'),
        ]);
    }

    /**
     * Put the aircraft back into service.
     *
     * @return Factory
     */
    public function putBackIntoService(): Factory
    {
        return $this->state(['put_out_of_service_at' => null]);
    }
}
