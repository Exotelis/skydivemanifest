<?php

namespace Database\Factories;

use App\Models\Aircraft;
use App\Models\AircraftMaintenance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AircraftMaintenanceFactory
 * @package Database\Factories
 */
class AircraftMaintenanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AircraftMaintenance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'aircraft_registration' => Aircraft::factory(),
            'dom'                   => $this->faker->optional(15)->date($format = 'Y-m-d', $max = 'now'),
            'maintenance_at'        => function (array $attributes) {
                $aircraft = Aircraft::withTrashed()->find($attributes['aircraft_registration']);
                return $aircraft->flight_time + (\rand(5, 10) * 10);
            },
            'name'                  => $this->faker->word,
            'notes'                 => $this->faker->text,
            'notified'              => false,
            'notify_at'             => function (array $attributes) {
                return $attributes['maintenance_at'] - (\rand(1, 3) * 10);
            },
            'repetition_interval'   => $this->faker->numberBetween(3000, 6000),
        ];
    }

    /**
     * Maintenance has been done.
     *
     * @return Factory
     */
    public function maintained()
    {
        return $this->state(function () {
            return ['dom' => $this->faker->date($format = 'Y-m-d', $max = 'now')];
        });
    }

    /**
     * Set notify level.
     *
     * @return Factory
     */
    public function noNotification()
    {
        return $this->state(function ()  {
            return ['notify_at' => null];
        });
    }

    /**
     * Maintenance has not been done.
     *
     * @return Factory
     */
    public function notMaintained()
    {
        return $this->state(function () {
            return ['dom' => null];
        });
    }

    /**
     * Make the maintenance not repetitive.
     *
     * @return Factory
     */
    public function noRepetitionInterval()
    {
        return $this->state(function () {
            return ['repetition_interval' => null];
        });
    }
}
