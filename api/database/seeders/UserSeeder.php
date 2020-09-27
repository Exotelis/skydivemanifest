<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fill countries
        if (\App\Models\Country::all()->count() === 0) {
            (new CountrySeeder())->run();
        }

        // Fill regions
        if (\App\Models\Region::all()->count() === 0) {
            (new RegionSeeder())->run();
        }

        User::factory()->count(50)->create()->each(function ($user) {
            $addresses = Address::factory()->count(rand(0,4))->create(['user_id' => $user->id]);
            $addresses = $addresses->toArray();

            // Select default invoice and default shipping address
            if (count($addresses) > 0) {
                $user->default_invoice = $addresses[array_rand($addresses)]['id'];
                $user->default_shipping = $addresses[array_rand($addresses)]['id'];
                $user->save();
            }
        });
    }
}
