<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

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
    public static function filters()
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
            AllowedFilter::exact('time', 'flight_time'),
            AllowedFilter::scope('time_elt', 'time_less_than'),
            AllowedFilter::scope('time_emt', 'time_more_than'),
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
            'dom',
            'model',
            'registration',
            'seats',
            AllowedSort::field('time', 'flight_time'),
        ];
    }
}
