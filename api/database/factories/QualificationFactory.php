<?php

namespace Database\Factories;

use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class QualificationFactory
 * @package Database\Factories
 */
class QualificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Qualification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $word = $this->faker->unique()->word;

        return [
            'color'         => $this->faker->hexColor,
            'qualification' => \ucfirst($word),
            'slug'          => \strtolower($word),
        ];
    }
}
