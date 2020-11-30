<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\DuplicateWithRelations;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Waiver
 * @package App\Models
 *
 * @property int $id
 * @property Carbon $created_at
 * @property bool $is_active
 * @property string $title
 * @property Carbon $updated_at
 * @property-read Collection|Text[] $texts
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @property-read Collection|UnassignedWaiver[] $unassignedWaivers
 * @property-read int|null $unassignedWaivers_count
 * @method static Builder|Waiver newModelQuery()
 * @method static Builder|Waiver newQuery()
 * @method static Builder|Waiver query()
 * @method static Builder|Waiver whereCreatedAt($value)
 * @method static Builder|Waiver whereId($value)
 * @method static Builder|Waiver whereIsActive($value)
 * @method static Builder|Waiver whereTitle($value)
 * @method static Builder|Waiver whereUpdatedAt($value)
 * @mixin Builder
 */
class Waiver extends Model implements Logable
{
    use DuplicateWithRelations, HasFactory, ModelDiff;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active',
        'title',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get all of the waivers texts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function texts()
    {
        return $this->morphMany('App\Models\Text', 'textable');
    }

    /**
     * Get the unassigned waivers for the waiver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unassignedWaivers()
    {
        return $this->hasMany('App\Models\UnassignedWaiver');
    }

    /**
     * Get all users that signed this waiver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')
            ->as('signature')
            ->withTimestamps();
    }

    /**
     * Scope a query to filter all texts with a given language code.
     *
     * @param  Builder $query
     * @param  string $languageCode
     * @return Builder|Waiver
     */
    public function scopeTextLanguageCode($query, $languageCode)
    {
        return $query->with(['texts' => function ($q) use ($languageCode) {
            $q->where('language_code', $languageCode)->orderBy('position');
        }]);
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString()
    {
        return "{$this->id}|{$this->title}";
    }
}
