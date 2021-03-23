<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class InadmissibleIf
 * @package App\Rules
 *
 * Passes if none of the fields is present
 *
 * Example:
 * <code>
 * new InadmissibleIf($this->all(),'field1', 'field2')
 * </code>
 */
class InadmissibleIf implements Rule
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $duplicates = [];

    /**
     * Create a new rule instance.
     *
     * @param array $data
     * @param mixed ...$fields
     * @return void
     */
    public function __construct(array $data, ...$fields)
    {
        $this->data = $data;
        $this->fields = $fields;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->duplicates = \array_intersect($this->fields, \array_keys($this->data));

        return empty($this->duplicates);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $values = $this->parseValues();
        return __('validation.inadmissible_if', ['values' => $values]);
    }

    /**
     * Try to translate and implode the defined values.
     *
     * @return string
     */
    protected function parseValues(): string
    {
        $values = $this->duplicates;

        foreach ($values as &$value) {
            $translationPath = 'validation.attributes.' . $value;

            if (! trans()->has($translationPath)) {
                continue;
            }

            $value = __($translationPath);
        }

        return implode(', ', $values);
    }
}
