<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class WaiverActiveGetFilters
 * @package App\Filters
 */
class WaiverActiveGetFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::scope('language_code', 'textLanguageCode'),
        ];
    }
}
