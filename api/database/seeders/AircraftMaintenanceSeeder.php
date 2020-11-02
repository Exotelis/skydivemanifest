<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use App\Models\AircraftMaintenance;
use Illuminate\Database\Seeder;

/**
 * Class AircraftMaintenanceSeeder
 * @package Database\Seeders
 */
class AircraftMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AircraftMaintenance::factory()->count(3)->for(Aircraft::factory())->notMaintained()->create();
    }
}
