<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * Class Address
 * @package App\Models
 *
 * @property int $id
 * @property string $city
 * @property string|null $company
 * @property int $country_id
 * @property string $firstname
 * @property string $lastname
 * @property string|null $middlename
 * @property string $postal
 * @property int $region_id
 * @property string $street
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Country $country
 * @property-read Region $region
 * @property-read User $user
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereCity($value)
 * @method static Builder|Address whereCompany($value)
 * @method static Builder|Address whereCountryId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereFirstname($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereLastname($value)
 * @method static Builder|Address whereMiddlename($value)
 * @method static Builder|Address wherePostal($value)
 * @method static Builder|Address whereRegionId($value)
 * @method static Builder|Address whereStreet($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static Builder|Address whereUserId($value)
 * @mixin Builder
 */
class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'company',
        'country_id',
        'firstname',
        'lastname',
        'middlename',
        'postal',
        'region_id',
        'street',
        'user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'country_id',
        'region_id',
        'user_id',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the country of the address.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Get the region of the address.
     */
    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    /**
     * Get the user of the address.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
