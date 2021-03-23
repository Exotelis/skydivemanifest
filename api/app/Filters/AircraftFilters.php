<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class AircraftFilters
 * @package App\Filters
 */
class AircraftFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters(): array
    {
        return [
            AllowedFilter::scope('dom'),
            AllowedFilter::scope('dom_at_after', 'dom_after'),
            AllowedFilter::scope('dom_at_before', 'dom_before'),
            AllowedFilter::partial('model'),
            AllowedFilter::scope('oos', 'out_of_service'),
            AllowedFilter::partial('registration'),
            AllowedFilter::exact('seats'),
            AllowedFilter::scope('seats_elt', 'seats_less_than'),
            AllowedFilter::scope('seats_emt', 'seats_more_than'),
        ];
    }

    /**
     * Return sorting array.
     *
     * @return array
     */
    public static function sorting(): array
    {
        return [
            'dom',
            'model',
            'registration',
            'seats',
        ];
    }
}
