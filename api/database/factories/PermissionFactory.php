<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PermissionFactory
 * @package Database\Factories
 */
class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'is_default' => false,
            'name'       => $this->faker->word,
            'slug'       => $this->faker->slug,
        ];
    }

    /**
     * Indicate that all permissions are assigned
     *
     * @return Factory
     */
    public function all()
    {
        return $this->state([
            'name' => 'All',
            'slug' => '*',
        ]);
    }

    /**
     * Indicate that the permission is default
     *
     * @return Factory
     */
    public function default()
    {
        return $this->state([
            'is_default' => true,
        ]);
    }
}
