<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class QualificationFilters
 * @package App\Filters
 */
class QualificationFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::partial('color'),
            AllowedFilter::partial('qualification'),
            AllowedFilter::partial('slug'),
        ];
    }

    /**
     * Return sorting array.
     *
     * @return array
     */
    public static function sorting()
    {
        return [
            'color',
            'qualification',
            'slug',
        ];
    }
}
