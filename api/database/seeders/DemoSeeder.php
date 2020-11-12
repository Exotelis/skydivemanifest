<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed the demo database.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user if it doesn't exist
        if (\App\Models\User::whereUsername('admin')->count() === 0) {
            \App\Models\User::factory()->createAdmin()->create();
        }

        // Create user if it doesn't exist
        if (\App\Models\User::whereUsername('user')->count() === 0) {
            \App\Models\User::factory()->createUser()->create();
        }

        $this->call([
            CountrySeeder::class,
            RegionSeeder::class,
            CurrencySeeder::class,

            QualificationSeeder::class,

            AircraftSeeder::class,
            UserSeeder::class,
        ]);
    }
}
