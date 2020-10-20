<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ModelFactory
 * @package Database\Factories
 */
class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code'     => $this->faker->unique()->currencyCode,
            'currency' => 'Euro',
            'symbol'   => '€',
        ];
    }
}
