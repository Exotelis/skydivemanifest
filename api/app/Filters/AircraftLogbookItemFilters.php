<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class AircraftLogbookItemFilters
 * @package App\Filters
 */
class AircraftLogbookItemFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters(): array
    {
        return [
            AllowedFilter::scope('arrival'),
            AllowedFilter::scope('arrival_at_after', 'arrival_after'),
            AllowedFilter::scope('arrival_at_before', 'arrival_before'),
            AllowedFilter::exact('block_time'),
            AllowedFilter::scope('block_time_elt', 'block_time_less_than'),
            AllowedFilter::scope('block_time_emt', 'block_time_more_than'),
            AllowedFilter::exact('crew'),
            AllowedFilter::scope('crew_elt', 'crew_less_than'),
            AllowedFilter::scope('crew_emt', 'crew_more_than'),
            AllowedFilter::scope('departure'),
            AllowedFilter::scope('departure_at_after', 'departure_after'),
            AllowedFilter::scope('departure_at_before', 'departure_before'),
            AllowedFilter::partial('destination'),
            AllowedFilter::exact('id'),
            AllowedFilter::partial('origin'),
            AllowedFilter::exact('pax'),
            AllowedFilter::scope('pax_elt', 'pax_less_than'),
            AllowedFilter::scope('pax_emt', 'pax_more_than'),
            AllowedFilter::partial('pilot_firstname'),
            AllowedFilter::exact('pilot_id'),
            AllowedFilter::partial('pilot_lastname'),
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
            'arrival',
            'block_time',
            'crew',
            'departure',
            'destination',
            'id',
            'origin',
            'pax',
            'pilot_firstname',
            'pilot_id',
            'pilot_lastname',
        ];
    }
}
