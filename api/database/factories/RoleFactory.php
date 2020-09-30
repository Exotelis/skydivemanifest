<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class RoleFactory
 * @package Database\Factories
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color'     => $this->faker->hexColor,
            'deletable' => true,
            'editable'  => true,
            'name'      => $this->faker->unique()->word,
        ];
    }

    /**
     * Indicate that the role is not deletable
     *
     * @return Factory
     */
    public function notDeletable()
    {
        return $this->state([
            'deletable' => false,
        ]);
    }

    /**
     * Indicate that the role is not editable
     *
     * @return Factory
     */
    public function notEditable()
    {
        return $this->state([
            'editable' => false,
        ]);
    }
}
