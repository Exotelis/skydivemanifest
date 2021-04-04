<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use App\Models\AircraftLogbook;
use Illuminate\Database\Seeder;

/**
 * Class AircraftSeeder
 * @package Database\Seeders
 */
class AircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Aircraft::factory()
            ->count(3)
            ->has(AircraftLogbook::factory()->hasItems(\rand(10, 15)), 'logbook')
            ->hasMaintenance(\rand(1, 5))
            ->create();
    }
}
