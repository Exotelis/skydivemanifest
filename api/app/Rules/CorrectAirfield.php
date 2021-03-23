<?php

namespace App\Rules;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class CorrectAirfield
 * @package App\Rules
 */
class CorrectAirfield implements Rule
{
    /**
     * @var Collection|null
     */
    protected $items;

    /**
     * @var string
     */
    protected $dateColumn;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $counterpart;

    /**
     * @var bool
     */
    protected $isOrigin;

    /**
     * @var Model
     */
    protected $result;

    /**
     * Create a new rule instance.
     *
     * @param  Collection|null $items
     * @param  string          $dateColumn
     * @param  string          $date
     * @param  string          $counterpart
     * @param  bool            $isOrigin
     * @return void
     */
    public function __construct(
        ?Collection $items,
        string $dateColumn,
        string $date,
        string $counterpart,
        bool $isOrigin = true
    )
    {
        $this->items = $items;
        $this->dateColumn = $dateColumn;
        $this->date = $date;
        $this->counterpart = $counterpart;
        $this->isOrigin = $isOrigin;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return $this->validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.correct_airfield', ['value' =>  $this->result->{$this->counterpart}]);
    }

    /**
     * Validate the given value.
     *
     * @param  string $value
     * @return bool
     * @throws \Exception
     */
    protected function validate(string $value): bool
    {
        if ($this->items->count() === 0) {
            return true;
        }

        // Try to convert to datetime string
        try {
            $date = Carbon::parse($this->date)->toDateTimeString();
        } catch (InvalidFormatException $exception) {
            throw new \Exception("The given date is not valid.");
        }

        // Verify if attributes exist
        $attributes = $this->items->first()->getAttributes();
        $className = $this->items->getQueueableClass();

        if (! \array_key_exists($this->dateColumn, $attributes)) {
            throw new \Exception("Attribute {$this->dateColumn} does not exist on model {$className}");
        }
        if (! \array_key_exists($this->counterpart, $attributes)) {
            throw new \Exception("Attribute {$this->counterpart} does not exist on model {$className}");
        }

        // Get last or next entry
        if ($this->isOrigin) {
            $this->result = $this->items
                ->where($this->dateColumn, '<', $date)
                ->sortByDesc($this->dateColumn)
                ->first();
        } else {
            $this->result = $this->items
                ->where($this->dateColumn, '>', $date)
                ->sortBy($this->dateColumn)
                ->first();
        }

        return ! (! \is_null($this->result) && $value !== $this->result->{$this->counterpart});
    }
}
