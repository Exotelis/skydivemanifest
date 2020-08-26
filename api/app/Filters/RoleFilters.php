<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * Class RoleFilters
 * @package App\Filters
 */
class RoleFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::exact('deletable'),
            AllowedFilter::exact('editable'),
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
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
            'deletable',
            'editable',
            'id',
            'name',
        ];
    }
}
