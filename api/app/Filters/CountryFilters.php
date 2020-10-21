<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class CountryFilters
 * @package App\Filters
 */
class CountryFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::partial('code'),
            AllowedFilter::partial('country'),
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
            'id',
            'code',
            'country',
        ];
    }
}
