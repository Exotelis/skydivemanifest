<?php

namespace Database\Factories;

use App\Models\Text;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class TextFactory
 * @package Database\Factories
 */
class TextFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Text::class;

    /**
     * Array of sortNumberGenerators. Keys are the valid locales.
     *
     * @var \Generator[]
     */
    protected $positionGenerators = [];

    /**
     * Create a new factory instance.
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());

        foreach (validLocales() as $locale) {
            $this->positionGenerators[$locale] = $this->positionGenerator();
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lang = $this->faker->randomElement(validLocales());
        $this->positionGenerators[$lang]->next();

        return [
            'language_code' => $lang,
            'position'      => $this->positionGenerators[$lang]->current(),
            'text'          => \array_reduce(
                $this->faker->paragraphs($nb = \rand(1,4)),
                [$this, 'toParagraphTags']
            ),
            'title'         => $this->faker->optional(80)->words(\rand(2,6), true),
        ];
    }

    /**
     * Sort number generator.
     *
     * @return \Generator
     */
    protected function positionGenerator()
    {
        for ($i = 0; $i < 9999; $i++) {
            yield $i;
        }
    }

    private function toParagraphTags($carry, $item)
    {
        $carry .= "<p>{$item}</p>";
        return $carry;
    }
}
