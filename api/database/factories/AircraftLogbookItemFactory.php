<?php

namespace Database\Factories;

use App\Models\AircraftLogbook;
use App\Models\AircraftLogbookItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * Class AircraftLogbookItemFactory
 * @package Database\Factories
 */
class AircraftLogbookItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AircraftLogbookItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'aircraft_logbook_id' => AircraftLogbook::factory(),
            'departure'           => function ($attributes) {
                $aircraft = AircraftLogbook::find($attributes['aircraft_logbook_id'])->aircraft;
                return $this->faker
                    ->dateTimeBetween($aircraft->dom ?? Carbon::minValue(), '-3 hours', 'UTC');
            },
            'arrival'             => function ($attributes) {
                $departure = Carbon::parse($attributes['departure']);
                return $this->faker->dateTimeBetween(
                    $departure->addMinutes(10)->toDateTimeString(),
                    $departure->addHour()->toDateTimeString(),
                    'UTC'
                );
            },
            'block_time'          => function ($attributes) {
                $departure = Carbon::parse($attributes['departure']);
                $arrival = Carbon::parse($attributes['arrival']);
                return $arrival->diffInMinutes($departure);
            },
            'crew'                => $this->faker->numberBetween(1, 2),
            'destination'         => 'EQDN',
            'notes'               => $this->faker->optional(50)->text(\rand(50, 150)),
            'origin'              => 'EQDN',
            'pax'                 => function ($attributes) {
                $aircraft = AircraftLogbook::find($attributes['aircraft_logbook_id'])->aircraft;
                return $this->faker->numberBetween(0, $aircraft->seats);
            },
            'pilot_firstname'     => $this->faker->firstName,
            'pilot_lastname'      => $this->faker->lastName,
        ];
    }
}
