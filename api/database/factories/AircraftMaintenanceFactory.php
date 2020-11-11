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
            'dom'                   => $this->faker->optional(50)->date($format = 'Y-m-d', $max = 'now'),
            'maintenance_at'        => function (array $attributes) {
                $flightTime = (Aircraft::withTrashed()->find($attributes['aircraft_registration']))->flight_time;
                if (! \is_null($attributes['dom'])) {
                    return $flightTime >= 1000 ? \round($flightTime - 100, -2) : 0;
                }
                return $flightTime >= 1000 ? + \round($flightTime * (\rand(2, 4)), -2) : 1000;
            },
            'name'                  => $this->faker->word,
            'notes'                 => $this->faker->text,
            'notified'              => false,
            'notify_at'             => function (array $attributes) {
                $notify = \rand(1, 3) * 60 * 10;
                return $attributes['maintenance_at'] <= $notify ? null : $attributes['maintenance_at'] - $notify;
            },
            'repetition_interval'   => \round($this->faker->optional(50)->numberBetween(3000, 6000), -2),
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

    /**
     * Make the maintenance repetitive.
     *
     * @return Factory
     */
    public function repetitive()
    {
        return $this->state(function () {
            return ['repetition_interval' => \round($this->faker->numberBetween(3000, 6000), -2)];
        });
    }
}
