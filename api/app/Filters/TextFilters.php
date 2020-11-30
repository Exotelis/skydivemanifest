<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class TextFilters
 * @package App\Filters
 */
class TextFilters
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
            AllowedFilter::exact('language_code'),
            AllowedFilter::partial('text'),
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
            'language_code',
            'position',
            'text',
            'title',
        ];
    }
}
