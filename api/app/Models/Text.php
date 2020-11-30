<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Text
 * @package App\Models
 *
 * @property int $id
 * @property Carbon $created_at
 * @property string $language_code
 * @property int $position
 * @property string $text
 * @property int $textable_id
 * @property string $textable_type
 * @property string|null $title
 * @property Carbon $updated_at
 * @property-read Collection|Waiver[] $textable
 * @method static Builder|Text newModelQuery()
 * @method static Builder|Text newQuery()
 * @method static Builder|Text query()
 * @method static Builder|Text whereCreatedAt($value)
 * @method static Builder|Text whereId($value)
 * @method static Builder|Text whereLanguageCode($value)
 * @method static Builder|Text wherePosition($value)
 * @method static Builder|Text whereText($value)
 * @method static Builder|Text whereTextableId($value)
 * @method static Builder|Text whereTextableType($value)
 * @method static Builder|Text whereTitle($value)
 * @method static Builder|Text whereUpdatedAt($value)
 * @method static reposition($id, $textableId, $textableType, $languageCode)
 * @mixin Builder
 */
class Text extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'title' => null,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'position'    => 'integer',
        'textable_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_code',
        'position',
        'text',
        'title',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the owning textable model.
     */
    public function textable()
    {
        return $this->morphTo();
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

    /**
     * Scope a query to only include active users.
     *
     * @param  Builder  $query
     * @param  int      $id
     * @param  int      $textableId
     * @param  string   $textableType
     * @param  string   $languageCode
     * @return Builder
     */
    public function scopeReposition($query, $id, $textableId, $textableType, $languageCode)
    {
        return $query->where('textable_id', $textableId)
            ->where('textable_type', $textableType)
            ->where('language_code', $languageCode)
            ->whereKeyNot($id)
            ->orderBy('position');
    }
}
