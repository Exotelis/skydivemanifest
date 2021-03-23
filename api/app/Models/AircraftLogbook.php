<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class AircraftLogbook
 * @package App\Models
 *
 * @property int $id
 * @property string $aircraft_registration
 * @property int $transfer
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Aircraft $aircraft
 * @property-read \Illuminate\Database\Eloquent\Collection|AircraftLogbookItem[] $items
 * @property-read int|null $items_count
 * @property-read int $operation_time
 * @property-read string $operation_time_hours
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook query()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook whereAircraftRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftLogbook whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AircraftLogbook extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'operation_time',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'transfer' => 0,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'transfer' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transfer',
    ];

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'items',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aircraft_logbooks';

    /**
     * Sum all entries of the logbook and also the transferred time.
     *
     * @return int
     */
    public function getOperationTimeAttribute(): int
    {
        return $this->transfer + $this->items->sum('block_time');
    }

    /**
     * Get the aircraft associated with the logbook.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraft(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Aircraft::class)->withTrashed();
    }

    /**
     * Get the items of the logbook.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AircraftLogbookItem::class);
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString(): string
    {
        return "{$this->id}|{$this->aircraft_registration}";
    }
}
