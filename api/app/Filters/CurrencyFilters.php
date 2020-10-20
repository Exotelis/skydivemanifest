<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class CurrencyFilters
 * @package App\Filters
 */
class CurrencyFilters
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
            AllowedFilter::partial('currency'),
            AllowedFilter::exact('symbol'),
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
            'code',
            'currency',
            'symbol',
        ];
    }
}
