<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Qualification
 * @package App\Models
 *
 * @property string $slug
 * @property string $color
 * @property string $qualification
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Qualification newModelQuery()
 * @method static Builder|Qualification newQuery()
 * @method static Builder|Qualification query()
 * @method static Builder|Qualification whereColor($value)
 * @method static Builder|Qualification whereCreatedAt($value)
 * @method static Builder|Qualification whereQualification($value)
 * @method static Builder|Qualification whereSlug($value)
 * @method static Builder|Qualification whereUpdatedAt($value)
 * @mixin Builder
 */
class Qualification extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'slug';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'color' => '#6c757d',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'color',
        'qualification',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;


    /**
     * The users that belong to the qualification.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString()
    {
        return "{$this->slug}|{$this->qualification}";
    }
}
