<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class AircraftMaintenance
 * @package App\Models
 *
 * @property int $id
 * @property string $aircraft_registration
 * @property Carbon|null $dom Maintenance date
 * @property int $maintenance_at in minutes
 * @property string|null $name
 * @property string|null $notes
 * @property bool $notified
 * @property int|null $notify_at in minutes
 * @property int|null $repetition_interval Restart period automatically after maintenance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Aircraft $aircraft
 * @property-read string $maintenance_at_hours
 * @property-read string $notification_interval_hours
 * @property-read string $notify_at_hours
 * @method static Builder|AircraftMaintenance newModelQuery()
 * @method static Builder|AircraftMaintenance newQuery()
 * @method static Builder|AircraftMaintenance query()
 * @method static Builder|AircraftMaintenance whereAircraftRegistration($value)
 * @method static Builder|AircraftMaintenance whereCreatedAt($value)
 * @method static Builder|AircraftMaintenance whereDom($value)
 * @method static Builder|AircraftMaintenance whereId($value)
 * @method static Builder|AircraftMaintenance whereMaintenanceAt($value)
 * @method static Builder|AircraftMaintenance whereName($value)
 * @method static Builder|AircraftMaintenance whereNotes($value)
 * @method static Builder|AircraftMaintenance whereNotified($value)
 * @method static Builder|AircraftMaintenance whereNotifyAt($value)
 * @method static Builder|AircraftMaintenance whereRepetitionInterval($value)
 * @method static Builder|AircraftMaintenance whereUpdatedAt($value)
 * @mixin Builder
 */
class AircraftMaintenance extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'maintenance_at_hours',
        'notify_at_hours',
        'repetition_interval_hours',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'dom'                 => null,
        'name'                => null,
        'notes'               => null,
        'notified'            => false,
        'notify_at'           => null,
        'repetition_interval' => null,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dom'                 => 'date:Y-m-d',
        'maintenance_at'      => 'integer',
        'notified'            => 'boolean',
        'notify_at'           => 'integer',
        'repetition_interval' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dom',
        'maintenance_at',
        'name',
        'notes',
        'notified',
        'notify_at',
        'repetition_interval',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'aircraft_registration',
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
    protected $table = 'aircraft_maintenance';

    /**
     * Converts the maintenance_at to hours in the format HH:ii.
     *
     * @return string
     */
    public function getMaintenanceAtHoursAttribute(): string
    {
        $hours = \floor($this->maintenance_at / 60);
        $minutes = $this->maintenance_at % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Converts the repetition_interval time to hours in the format HH:ii.
     *
     * @return string
     */
    public function getRepetitionIntervalHoursAttribute(): string
    {
        $hours = \floor($this->repetition_interval / 60);
        $minutes = $this->repetition_interval % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Converts the notify_at time to hours in the format HH:ii.
     *
     * @return string
     */
    public function getNotifyAtHoursAttribute(): string
    {
        $hours = \floor($this->notify_at / 60);
        $minutes = $this->notify_at % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Get the aircraft of the maintenance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraft(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Aircraft')->withTrashed();
    }

    /**
     * Determine if the maintenance is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return ! \is_null($this->dom);
    }

    /**
     * Scope a query to filter for the dom.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftMaintenance
     */
    public function scopeDom(Builder $query, string $date)
    {
        return $query->where('dom', '=', Carbon::make($date)->toDateString());
    }

    /**
     * Scope a query to filter for the maintained after date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftMaintenance
     */
    public function scopeDomAfter(Builder $query, string $date)
    {
        return $query->where('dom', '>=', Carbon::parse($date));
    }

    /**
     * Scope a query to filter for the maintained before date.
     *
     * @param  Builder $query
     * @param  string  $date
     * @return Builder|AircraftMaintenance
     */
    public function scopeDomBefore(Builder $query, string $date)
    {
        return $query->where('dom', '<=', Carbon::parse($date));
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with less than x minutes in maintenance_at.
     *
     * @param  Builder $query
     * @param  string  $maintenanceAt
     * @return Builder|AircraftMaintenance
     */
    public function scopeMaintenanceAtLessThan(Builder $query, string $maintenanceAt)
    {
        return $query->where('maintenance_at', '<=', $maintenanceAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with more than x minutes in maintenance_at.
     *
     * @param  Builder $query
     * @param  string  $maintenanceAt
     * @return Builder|AircraftMaintenance
     */
    public function scopeMaintenanceAtMoreThan(Builder $query, string $maintenanceAt)
    {
        return $query->where('maintenance_at', '>=', $maintenanceAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with less than x minutes in notify_at.
     *
     * @param  Builder $query
     * @param  string  $notifyAt
     * @return Builder|AircraftMaintenance
     */
    public function scopeNotifyAtLessThan(Builder $query, string $notifyAt)
    {
        return $query->where('notify_at', '<=', $notifyAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with more than x minutes in notify_at.
     *
     * @param  Builder $query
     * @param  string  $notifyAt
     * @return Builder|AircraftMaintenance
     */
    public function scopeNotifyAtMoreThan(Builder $query, string $notifyAt)
    {
        return $query->where('notify_at', '>=', $notifyAt);
    }

    /**
     * Set the date of maintenance and convert it to Carbon.
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
        return "{$this->id}";
    }
}
