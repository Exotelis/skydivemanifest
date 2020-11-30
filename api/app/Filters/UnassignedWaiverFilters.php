<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class UnassignedWaiverFilters
 * @package App\Filters
 */
class UnassignedWaiverFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::partial('email'),
            AllowedFilter::partial('firstname'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('ip'),
            AllowedFilter::partial('lastname'),
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
            'email',
            'firstname',
            'id',
            'ip',
            'lastname',
        ];
    }
}
