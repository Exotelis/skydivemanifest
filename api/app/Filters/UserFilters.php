<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Sorts\Sort;

/**
 * Class UserFilters
 * @package App\Filters
 */
class UserFilters
{
    /**
     * Return filter array.
     *
     * @return array
     */
    public static function filters()
    {
        return [
            AllowedFilter::scope('age'),
            AllowedFilter::scope('age_eot', 'older_than'),
            AllowedFilter::scope('age_eyt', 'younger_than'),
            AllowedFilter::partial('city', 'addresses.city'),
            AllowedFilter::partial('country', 'addresses.country.country'),
            AllowedFilter::partial('default_invoice_city', 'default_invoice.city'),
            AllowedFilter::partial('default_invoice_country', 'default_invoice.country.country'),
            AllowedFilter::partial('default_invoice_postal', 'default_invoice.postal'),
            AllowedFilter::partial('default_invoice_region', 'default_invoice.region.region'),
            AllowedFilter::partial('default_invoice_street', 'default_invoice.street'),
            AllowedFilter::partial('default_shipping_city', 'default_shipping.city'),
            AllowedFilter::partial('default_shipping_country', 'default_shipping.country.country'),
            AllowedFilter::partial('default_shipping_postal', 'default_shipping.postal'),
            AllowedFilter::partial('default_shipping_region', 'default_shipping.region.region'),
            AllowedFilter::partial('default_shipping_street', 'default_shipping.street'),
            AllowedFilter::scope('dob'),
            AllowedFilter::scope('dob_at_after', 'born_after'),
            AllowedFilter::scope('dob_at_before', 'born_before'),
            AllowedFilter::partial('email'),
            AllowedFilter::scope('email_verified'),
            AllowedFilter::partial('firstname'),
            AllowedFilter::exact('gender'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::partial('lastname'),
            AllowedFilter::partial('middlename'),
            AllowedFilter::partial('mobile'),
            AllowedFilter::scope('name', 'fullName'),
            AllowedFilter::partial('phone'),
            AllowedFilter::partial('postal', 'addresses.postal'),
            AllowedFilter::partial('region', 'addresses.region.region'),
            AllowedFilter::exact('role', 'role.name'),
            AllowedFilter::partial('street', 'addresses.street'),
            AllowedFilter::partial('username'),
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
            'dob',
            'email',
            'firstname',
            'gender',
            'id',
            'lastname',
            'locale',
            'middlename',
            'mobile',
            'phone',
            'timezone',
            'username',
            AllowedSort::custom('is_active', new class implements Sort
            {
                public function __invoke(Builder $query, $descending, string $property) : Builder
                {
                    return $query
                        ->orderBy('is_active', $descending ? 'asc' : 'desc');
                }
            }),
            AllowedSort::custom('role', new class implements Sort
            {
                public function __invoke(Builder $query, $descending, string $property) : Builder
                {
                    return $query
                        ->select('*')
                        ->selectSub(\App\Models\Role::select('name')
                            ->whereRaw('roles.id = users.role_id'), 'role_name')
                        ->orderBy('role_name', $descending ? 'desc' : 'asc');
                }
            })
        ];
    }
}
