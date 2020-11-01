<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * Class AircraftMaintenanceFilters
 * @package App\Filters
 */
class AircraftMaintenanceFilters
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
            AllowedFilter::exact('maintenance_at'),
            AllowedFilter::scope('maintenance_at_elt', 'maintenance_at_less_than'),
            AllowedFilter::scope('maintenance_at_emt', 'maintenance_at_more_than'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('notes'),
            AllowedFilter::exact('notified'),
            AllowedFilter::exact('notify_at'),
            AllowedFilter::scope('notify_at_elt', 'notify_at_less_than'),
            AllowedFilter::scope('notify_at_emt', 'notify_at_more_than'),
            AllowedFilter::exact('repetition_interval'),
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
            'id',
            AllowedSort::field('maintenance', 'maintenance_at'),
            'name',
            'notified',
            AllowedSort::field('notify', 'notify_at'),
            'repetition_interval',
        ];
    }
}
