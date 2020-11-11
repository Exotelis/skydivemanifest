<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * Trait ModelDiff
 * @package App\Traits
 */
trait ModelDiff
{
    /**
     * Get the diff of the model.
     *
     * @param array|null    $filter
     * @param bool          $asString
     * @return array|string
     */
    public function getDiff(array $filter = null, bool $asString = true)
    {
        $model = $this;

        // Return of no eloquent model
        if (! ($model instanceof \Illuminate\Database\Eloquent\Model)) {
            return $asString ? '' : [];
        }

        // Is clean
        if ($model->isClean()) {
            return $asString ? '' : [];
        }

        // Filter attributes
        $dirty = Arr::except($model->getDirty(), $filter);

        $diff = [];
        foreach ($dirty as $key => $value) {
            $diff[$key] = [
                'old' => $model->getOriginal($key),
                'new' => $value,
            ];
        }

        // Return string
        if ($asString) {
            return $this->diffToString($diff);
        }

        return $diff;
    }

    /**
     * Converts a diff array to a string.
     *
     * @param array $diff
     * @return string
     */
    protected function diffToString(array $diff)
    {
        $diffString = '';

        foreach ($diff as $key => $attribute) {
            $diffString .= "{$key}:[-]{$attribute['old']}[+]{$attribute['new']} ";
        }

        return \rtrim($diffString);
    }
}
