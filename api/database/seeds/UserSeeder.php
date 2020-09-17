<?php

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

        factory(App\Models\User::class, 80)->create()->each(function ($user) {
            // Generate 0 to 4 addresses per user
            $addresses = factory(App\Models\Address::class, rand(0,4))->create(['user_id' => $user->id]);
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
