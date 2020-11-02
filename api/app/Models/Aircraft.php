<?php

namespace App\Models;

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
 * @property int $flight_time in minutes
 * @property string $model
 * @property int $seats
 * @property Carbon $created_at
 * @property Carbon|null $put_out_of_service_at Put out of service
 * @property Carbon $updated_at
 * @property-read string $flight_hours
 * @property-read \Illuminate\Database\Eloquent\Collection|AircraftMaintenance[] $maintenance
 * @property-read int|null $maintenance_count
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft wherePutOutOfServiceAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereDom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereFlightTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Aircraft extends Model
{
    use HasFactory, SoftDeletes;

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
        'flight_hours',
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
        'flight_time' => 'integer',
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
        'flight_time',
        'model',
        'registration',
        'seats',
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
     * Converts the flight time to hours in the format HH:ii.
     *
     * @return string
     */
    public function getFlightHoursAttribute()
    {
        $hours = \floor($this->flight_time / 60);
        $minutes = $this->flight_time % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Get the maintenance(s) of the aircraft.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenance()
    {
        return $this->hasMany('App\Models\AircraftMaintenance');
    }

    /**
     * Scope a query to filter for the dom.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeDom($query, $dom)
    {
        return $query->where('dom', '=', Carbon::make($dom)->toDateString());
    }

    /**
     * Scope a query to filter for the manufactured after date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeDomAfter($query, $dom)
    {
        return $query->where('dom', '>=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter for the manufactured before date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeDomBefore($query, $dom)
    {
        return $query->where('dom', '<=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter aircraft(s) that have been put out of service.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $outOfService
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeOutOfService($query, $outOfService)
    {
        if ($outOfService === '1' || $outOfService === 'true' || $outOfService === true) {
            return $query->where('put_out_of_service_at', '<>', null);
        }

        return $query->where('put_out_of_service_at', '=', null);
    }

    /**
     * Scope a query to filter aircraft(s) with or with less than x seats.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $seats
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeSeatsLessThan($query, $seats)
    {
        return $query->where('seats', '<=', $seats);
    }

    /**
     * Scope a query to filter aircraft(s) with or with more than x seats.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $seats
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeSeatsMoreThan($query, $seats)
    {
        return $query->where('seats', '>=', $seats);
    }

    /**
     * Scope a query to filter aircraft(s) with or with less than x flight time.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $time
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeTimeLessThan($query, $time)
    {
        return $query->where('flight_time', '<=', $time);
    }

    /**
     * Scope a query to filter aircraft(s) with or with more than x flight time.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $time
     * @return \Illuminate\Database\Eloquent\Builder|Aircraft
     */
    public function scopeTimeMoreThan($query, $time)
    {
        return $query->where('flight_time', '>=', $time);
    }

    /**
     * Set the date of manufacturing and convert it to Carbon.
     *
     * @param  string  $value
     * @return void
     */
    public function setDomAttribute($value)
    {
        if (\is_null($value)) {
            $this->attributes['dom'] = null;
        } else {
            $this->attributes['dom'] = Carbon::make($value)->toDateString();
        }
    }
}
