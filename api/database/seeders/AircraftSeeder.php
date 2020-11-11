<?php

namespace Database\Seeders;

use App\Models\Aircraft;
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
        Aircraft::factory()->count(3)->hasMaintenance(\rand(1, 5))->create();
    }
}
