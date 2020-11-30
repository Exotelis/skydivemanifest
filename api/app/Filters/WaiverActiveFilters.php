<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Sorts\Sort;

/**
 * Class WaiverActiveFilters
 * @package App\Filters
 */
class WaiverActiveFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::scope('language_code', 'textLanguageCode'),
            AllowedFilter::partial('title'),
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
            'title',
        ];
    }
}
