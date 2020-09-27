<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Country
 * @package App\Models
 *
 * @property int $id
 * @property string $country
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereCode($value)
 * @method static Builder|Country whereCountry($value)
 * @method static Builder|Country whereCreatedAt($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereUpdatedAt($value)
 * @mixin Builder
 */
class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country',
        'code',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;
}
