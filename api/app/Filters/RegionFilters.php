<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class RegionFilters
 * @package App\Filters
 */
class RegionFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::partial('region'),
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
            'region',
        ];
    }
}
