<?php

namespace Database\Factories;

use App\Models\Aircraft;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AircraftFactory
 * @package Database\Factories
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
    public function definition()
    {
        return [
            'dom'                   => $this->faker->optional(80)->date($format = 'Y-m-d', $max = 'now'),
            'flight_time'           => $this->faker->numberBetween(0, 1000),
            'model'                 => $this->faker->randomElement(['Cessna', 'Porter']),
            'put_out_of_service_at' => $this->faker->optional(40)
                ->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = 'UTC'),
            'registration'          => $this->faker->unique()->regexify('D-[EG][A-Z]{3}'),
            'seats'                 => $this->faker->numberBetween(1, 20),
        ];
    }

    /**
     * Put the aircraft out of service.
     *
     * @return Factory
     */
    public function putOutOfService()
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
    public function putBackIntoService()
    {
        return $this->state(['put_out_of_service_at' => null]);
    }
}
