<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

/**
 * Class QualificationSeeder
 * @package Database\Seeders
 */
class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Qualification::insert($this->getQualifications());
    }

    /**
     * Return list of countries.
     *
     * @return array
     */
    protected function getQualifications()
    {
        return [
            ['slug' => 'aff-instructor',      'color' => '#04b1d6', 'qualification' => 'AFF Instructor'],
            ['slug' => 'coach',               'color' => '#308ea2', 'qualification' => 'Coach'],
            ['slug' => 'dzso',                'color' => '#00c281', 'qualification' => 'Drop Zone Safety Officer'],
            ['slug' => 'gco',                 'color' => '#d60461', 'qualification' => 'Ground Control Officer'],
            ['slug' => 'lo',                  'color' => '#d6b104', 'qualification' => 'Load Organizer'],
            ['slug' => 'packer',              'color' => '#04d6c5', 'qualification' => 'Packer'],
            ['slug' => 'manifest',            'color' => '#1d2530', 'qualification' => 'Manifest'],
            ['slug' => 'pilot',               'color' => '#b2c0d4', 'qualification' => 'Pilot'],
            ['slug' => 'radio-operator',      'color' => '#476f78', 'qualification' => 'Radio Operator'],
            ['slug' => 'rigger',              'color' => '#000000', 'qualification' => 'Rigger'],
            ['slug' => 'tandem-instructor',   'color' => '#e6ce03', 'qualification' => 'Tandem Instructor'],
            ['slug' => 'tandem-master',       'color' => '#ebd411', 'qualification' => 'Tandem Master'],
            ['slug' => 'videographer',        'color' => '#7806d8', 'qualification' => 'Videographer'],
            ['slug' => 'wingsuit-instructor', 'color' => '#d68504', 'qualification' => 'Wingsuit Instructor'],
        ];
    }
}
