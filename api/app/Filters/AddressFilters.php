<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Sorts\Sort;

/**
 * Class AddressFilters
 * @package App\Filters
 */
class AddressFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::partial('city'),
            AllowedFilter::partial('company'),
            AllowedFilter::partial('country', 'country.country'),
            AllowedFilter::partial('firstname'),
            AllowedFilter::partial('lastname'),
            AllowedFilter::partial('middlename'),
            AllowedFilter::partial('postal'),
            AllowedFilter::partial('region', 'region.region'),
            AllowedFilter::partial('street'),
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
            'city',
            'company',
            'firstname',
            'lastname',
            'middlename',
            'postal',
            'street',
            AllowedSort::custom('country', new class implements Sort
            {
                public function __invoke(Builder $query, $descending, string $property) : Builder
                {
                    return $query
                        ->select('*')
                        ->selectSub(
                            \App\Models\Country::select('country')
                                ->whereRaw('countries.id = addresses.country_id'),
                            'country_name'
                        )
                        ->orderBy('country_name', $descending ? 'desc' : 'asc');
                }
            }),
            AllowedSort::custom('region', new class implements Sort
            {
                public function __invoke(Builder $query, $descending, string $property) : Builder
                {
                    return $query
                        ->select('*')
                        ->selectSub(
                            \App\Models\Region::select('region')
                                ->whereRaw('regions.id = addresses.region_id'),
                            'region_name'
                        )
                        ->orderBy('region_name', $descending ? 'desc' : 'asc');
                }
            })
        ];
    }
}
