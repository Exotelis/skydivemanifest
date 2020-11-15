<?php

namespace Database\Seeders;

use App\Models\Waiver;
use Illuminate\Database\Seeder;

/**
 * Class WaiverSeeder
 * @package Database\Seeders
 */
class WaiverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Waiver::factory()->count(3)->hasTexts(\rand(1, 10))->create();
    }
}
