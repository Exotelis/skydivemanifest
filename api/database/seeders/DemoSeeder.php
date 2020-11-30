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
            /**
             * Location seeders
             */
            CountrySeeder::class,
            CurrencySeeder::class,
            RegionSeeder::class,

            /**
             * Seeders that are interesting for the demo
             */
            AircraftSeeder::class,
            QualificationSeeder::class,
            WaiverSeeder::class,

            /**
             * User seeder at the end, because it depends on many other items
             */
            UserSeeder::class,
        ]);
    }
}
