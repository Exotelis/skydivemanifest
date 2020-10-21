<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Region
 * @package App\Models
 *
 * @property int $id
 * @property string $region
 * @property int $country_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Country $country
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region query()
 * @method static Builder|Region whereCountryId($value)
 * @method static Builder|Region whereCreatedAt($value)
 * @method static Builder|Region whereId($value)
 * @method static Builder|Region whereRegion($value)
 * @method static Builder|Region whereUpdatedAt($value)
 * @mixin Builder
 */
class Region extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'region',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'country_id',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the country of the region.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
