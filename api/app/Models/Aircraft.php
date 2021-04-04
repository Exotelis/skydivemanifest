<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Aircraft
 * @package App\Models
 *
 * @property string $registration
 * @property Carbon|null $dom Manufacturing date
 * @property string $model
 * @property int $seats
 * @property Carbon $created_at
 * @property Carbon|null $put_out_of_service_at Put out of service
 * @property Carbon $updated_at
 * @property-read int $operation_time
 * @property-read string $operation_time_hours
 * @property-read AircraftLogbook $logbook
 * @property-read \Illuminate\Database\Eloquent\Collection|AircraftLogbookItem[] $logbookItems
 * @property-read int|null $logbookItems_count
 * @property-read \Illuminate\Database\Eloquent\Collection|AircraftMaintenance[] $maintenance
 * @property-read int|null $maintenance_count
 * @method static Builder|Aircraft newModelQuery()
 * @method static Builder|Aircraft newQuery()
 * @method static Builder|Aircraft query()
 * @method static Builder|Aircraft whereCreatedAt($value)
 * @method static Builder|Aircraft wherePutOutOfServiceAt($value)
 * @method static Builder|Aircraft whereDom($value)
 * @method static Builder|Aircraft whereModel($value)
 * @method static Builder|Aircraft whereRegistration($value)
 * @method static Builder|Aircraft whereSeats($value)
 * @method static Builder|Aircraft whereUpdatedAt($value)
 * @mixin Builder
 */
class Aircraft extends Model implements Logable
{
    use HasFactory, ModelDiff, SoftDeletes;

    const DELETED_AT = 'put_out_of_service_at';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'registration';

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'operation_time',
        'operation_time_hours',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'put_out_of_service_at' => null,
        'dom'                   => null,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dom'         => 'date:Y-m-d',
        'seats'       => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'put_out_of_service_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dom',
        'model',
        'registration',
        'seats',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'logbook',
        'logbookItems',
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
    protected $table = 'aircraft';

    /**
     * Converts the operation time to hours in the format HH:ii.
     *
     * @return string
     */
    public function getOperationTimeHoursAttribute(): string
    {
        $operationTime = $this->operation_time;
        $hours = \floor($operationTime / 60);
        $minutes = $operationTime % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Calculate the actual operation time of the aircraft according to the log book entries.
     *
     * @return int
     */
    public function getOperationTimeAttribute(): int
    {
        if (\is_null($this->logbook)) {
            return 0;
        }

        return $this->logbook->transfer + $this->logbookItems->sum('block_time');
    }

    /**
     * Get the logbook associated with the aircraft.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function logbook(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AircraftLogbook::class);
    }

    /**
     * Get the items of the logbook associated with the aircraft.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function logbookItems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(AircraftLogbookItem::class ,AircraftLogbook::class);
    }

    /**
     * Get the maintenance(s) of the aircraft.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AircraftMaintenance::class);
    }

    /**
     * Scope a query to filter for the dom.
     *
     * @param  Builder $query
     * @param  string  $dom
     * @return Builder|Aircraft
     */
    public function scopeDom(Builder $query, string $dom)
    {
        return $query->where('dom', '=', Carbon::make($dom)->toDateString());
    }

    /**
     * Scope a query to filter for the manufactured after date.
     *
     * @param  Builder $query
     * @param  string  $dom
     * @return Builder|Aircraft
     */
    public function scopeDomAfter(Builder $query, string $dom)
    {
        return $query->where('dom', '>=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter for the manufactured before date.
     *
     * @param  Builder $query
     * @param  string  $dom
     * @return Builder|Aircraft
     */
    public function scopeDomBefore(Builder $query, string $dom)
    {
        return $query->where('dom', '<=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter aircraft(s) that have been put out of service.
     *
     * @param  Builder $query
     * @param  string  $outOfService
     * @return Builder|Aircraft
     */
    public function scopeOutOfService(Builder $query, string $outOfService)
    {
        if ($outOfService === '1' || $outOfService === 'true' || $outOfService === true) {
            return $query->where('put_out_of_service_at', '<>', null);
        }

        return $query->where('put_out_of_service_at', '=', null);
    }

    /**
     * Scope a query to filter aircraft(s) with or with less than x seats.
     *
     * @param  Builder $query
     * @param  string  $seats
     * @return Builder|Aircraft
     */
    public function scopeSeatsLessThan(Builder $query, string $seats)
    {
        return $query->where('seats', '<=', $seats);
    }

    /**
     * Scope a query to filter aircraft(s) with or with more than x seats.
     *
     * @param  Builder $query
     * @param  string  $seats
     * @return Builder|Aircraft
     */
    public function scopeSeatsMoreThan(Builder $query, string $seats)
    {
        return $query->where('seats', '>=', $seats);
    }

    /**
     * Set the date of manufacturing and convert it to Carbon.
     *
     * @param  string|null $value
     * @return void
     */
    public function setDomAttribute(?string $value)
    {
        if (\is_null($value)) {
            $this->attributes['dom'] = null;
        } else {
            $this->attributes['dom'] = Carbon::make($value)->toDateString();
        }
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString(): string
    {
        return "{$this->registration}|{$this->model}";
    }
}
