<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Sorts\Sort;

/**
 * Class WaiverFilters
 * @package App\Filters
 */
class WaiverFilters
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
            AllowedFilter::exact('is_active'),
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
            AllowedSort::custom('is_active', new class implements Sort
            {
                public function __invoke(Builder $query, $descending, string $property) : Builder
                {
                    return $query
                        ->orderBy('is_active', $descending ? 'asc' : 'desc');
                }
            }),
            'title',
        ];
    }
}
