<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class AircraftLogbookItem
 * @package App\Models
 *
 * @property int $id
 * @property int $aircraft_logbook_id
 * @property Carbon|null $arrival
 * @property int $block_time Actual flight time in minutes
 * @property int|null $crew
 * @property Carbon|null $departure
 * @property string|null $destination
 * @property string|null $notes
 * @property string|null $origin
 * @property int|null $pax
 * @property string|null $pilot_firstname
 * @property int|null $pilot_id
 * @property string|null $pilot_lastname
 * @property string|null $pilot_signature
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read AircraftLogbook $logbook
 * @method static Builder|AircraftLogbookItem newModelQuery()
 * @method static Builder|AircraftLogbookItem newQuery()
 * @method static Builder|AircraftLogbookItem query()
 * @method static Builder|AircraftLogbookItem whereAircraftLogbookId($value)
 * @method static Builder|AircraftLogbookItem whereArrival($value)
 * @method static Builder|AircraftLogbookItem whereBlockTime($value)
 * @method static Builder|AircraftLogbookItem whereCreatedAt($value)
 * @method static Builder|AircraftLogbookItem whereCrew($value)
 * @method static Builder|AircraftLogbookItem whereDeparture($value)
 * @method static Builder|AircraftLogbookItem whereDestination($value)
 * @method static Builder|AircraftLogbookItem whereId($value)
 * @method static Builder|AircraftLogbookItem whereNotes($value)
 * @method static Builder|AircraftLogbookItem whereOrigin($value)
 * @method static Builder|AircraftLogbookItem wherePax($value)
 * @method static Builder|AircraftLogbookItem wherePilotFirstname($value)
 * @method static Builder|AircraftLogbookItem wherePilotId($value)
 * @method static Builder|AircraftLogbookItem wherePilotLastname($value)
 * @method static Builder|AircraftLogbookItem wherePilotSignature($value)
 * @method static Builder|AircraftLogbookItem whereUpdatedAt($value)
 * @mixin Builder
 */
class AircraftLogbookItem extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'notes'           => null,
        'pilot_id'        => null,
        'pilot_signature' => null,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'arrival'    => 'datetime:Y-m-d H:i:s',
        'block_time' => 'integer',
        'crew'       => 'integer',
        'departure'  => 'datetime:Y-m-d H:i:s',
        'pax'        => 'integer',
        'pilot_id'   => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'arrival',
        'departure',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'arrival',
        'block_time',
        'crew',
        'departure',
        'destination',
        'notes',
        'origin',
        'pax',
        'pilot_firstname',
        'pilot_id',
        'pilot_lastname',
        'pilot_signature',
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
        'aircraft_logbook_id',
        'logbook',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the logbook of the entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logbook(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AircraftLogbook::class, 'aircraft_logbook_id');
    }

    /**
     * Scope a query to filter for the arrival.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeArrival(Builder $query, string $date)
    {
        return $query->where('arrival', 'LIKE', Carbon::make($date)->toDateString() . '%');
    }

    /**
     * Scope a query to filter for the arrival after date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeArrivalAfter(Builder $query, string $date)
    {
        return $query->where('arrival', '>=', Carbon::parse($date));
    }

    /**
     * Scope a query to filter for the arrival before date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeArrivalBefore(Builder $query, string $date)
    {
        return $query->where('arrival', '<', Carbon::parse($date)->addDay());
    }

    /**
     * Scope a query to filter logbook entries with or with less than x block time.
     *
     * @param  Builder $query
     * @param  string  $blockTime
     * @return Builder|AircraftLogbookItem
     */
    public function scopeBlockTimeLessThan(Builder $query, string $blockTime)
    {
        return $query->where('block_time', '<=', $blockTime);
    }

    /**
     * Scope a query to filter logbook entries with or with more than x block time.
     *
     * @param  Builder $query
     * @param  string  $blockTime
     * @return Builder|AircraftLogbookItem
     */
    public function scopeBlockTimeMoreThan(Builder $query, string $blockTime)
    {
        return $query->where('block_time', '>=', $blockTime);
    }

    /**
     * Scope a query to filter logbook entries with or with less than x crew members.
     *
     * @param  Builder $query
     * @param  string  $crew
     * @return Builder|AircraftLogbookItem
     */
    public function scopeCrewLessThan(Builder $query, string $crew)
    {
        return $query->where('crew', '<=', $crew);
    }

    /**
     * Scope a query to filter logbook entries with or with more than x crew members.
     *
     * @param  Builder $query
     * @param  string  $crew
     * @return Builder|AircraftLogbookItem
     */
    public function scopeCrewMoreThan(Builder $query, string $crew)
    {
        return $query->where('crew', '>=', $crew);
    }

    /**
     * Scope a query to filter for the departure.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeDeparture(Builder $query, string $date)
    {
        return $query->where('departure', 'LIKE', Carbon::make($date)->toDateString() . '%');
    }

    /**
     * Scope a query to filter for the departure after date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeDepartureAfter(Builder $query, string $date)
    {
        return $query->where('departure', '>=', Carbon::parse($date));
    }

    /**
     * Scope a query to filter for the departure before date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftLogbookItem
     */
    public function scopeDepartureBefore(Builder $query, string $date)
    {
        return $query->where('departure', '<', Carbon::parse($date)->addDay());
    }

    /**
     * Scope a query to filter logbook entries with or with less than x passengers.
     *
     * @param  Builder $query
     * @param  string  $pax
     * @return Builder|AircraftLogbookItem
     */
    public function scopePaxLessThan(Builder $query, string $pax)
    {
        return $query->where('pax', '<=', $pax);
    }

    /**
     * Scope a query to filter logbook entries with or with more than x passengers.
     *
     * @param  Builder $query
     * @param  string  $pax
     * @return Builder|AircraftLogbookItem
     */
    public function scopePaxMoreThan(Builder $query, string $pax)
    {
        return $query->where('pax', '>=', $pax);
    }


    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString(): string
    {
        return "{$this->id}|{$this->aircraft_logbook_id}|{$this->block_time}";
    }
}
