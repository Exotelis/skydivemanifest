<?php

namespace Database\Factories;

use App\Models\Waiver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class WaiverFactory
 * @package Database\Factories
 *
 * @method Factory hasTexts($int)
 */
class WaiverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Waiver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'is_active' => $this->faker->boolean(50),
            'title'     => $this->faker->words(\rand(2,6), true),
        ];
    }

    /**
     * Indicate that the waiver is active.
     *
     * @return Factory
     */
    public function isActive()
    {
        return $this->state([
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the waiver is not active.
     *
     * @return Factory
     */
    public function isNotActive()
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
}
