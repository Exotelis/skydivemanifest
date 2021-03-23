<?php

namespace App\Rules;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class NotBetweenDates
 * @package App\Rules
 *
 * Make sure that the value is a valid date. To do so, add bail and date in the rules before this rule.
 */
class NotBetweenDates implements Rule
{
    /**
     * @var Collection|null
     */
    protected $items;

    /**
     * @var string
     */
    protected $startColumn;

    /**
     * @var string
     */
    protected $endColumn;

    /**
     * @var Model
     */
    protected $conflict;

    /**
     * Create a new rule instance.
     *
     * @param  Collection|null $items
     * @param  string          $startColumn     Database column with DateTime
     * @param  string          $endColumn       Database column with DateTime
     * @return void
     */
    public function __construct(?Collection $items, string $startColumn, string $endColumn) {
        $this->items = $items;
        $this->startColumn = $startColumn;
        $this->endColumn = $endColumn;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return $this->validate($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $formatStartDate = $this->conflict->getAttribute($this->startColumn)->isoFormat('L LTS');
        $formatEndDate = $this->conflict->getAttribute($this->endColumn)->isoFormat('L LTS');

        return __('validation.not_between_date', ['startDate' => $formatStartDate, 'endDate' => $formatEndDate]);
    }

    /**
     * Validate the given value.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Exception
     */
    protected function validate(string $attribute, $value): bool
    {
        if ($this->items->count() === 0) {
            return true;
        }

        // Try to convert to datetime string
        try {
            $value = Carbon::parse($value)->toDateTimeString();
        } catch (InvalidFormatException $exception) {
            throw new \Exception("The value of the attribute '$attribute' is not a valid date.");
        }

        // Verify if attributes exist
        $attributes = $this->items->first()->getAttributes();
        $className = $this->items->getQueueableClass();

        if (! \array_key_exists($this->startColumn, $attributes)) {
            throw new \Exception("Attribute {$this->startColumn} does not exist on model {$className}");
        }
        if (! \array_key_exists($this->endColumn, $attributes)) {
            throw new \Exception("Attribute {$this->endColumn} does not exist on model {$className}");
        }

        // Check for conflicts
        $this->conflict = $this->items
            ->where($this->startColumn, '<=', $value)
            ->where($this->endColumn, '>', $value)
            ->first();

        return \is_null($this->conflict);
    }
}
