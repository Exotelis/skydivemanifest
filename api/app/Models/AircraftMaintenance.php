<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance query()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereAircraftRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereDom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereMaintenanceAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereNotifyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereRepetitionInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftMaintenance whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AircraftMaintenance extends Model
{
    use HasFactory;

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
    public function getMaintenanceAtHoursAttribute()
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
    public function getRepetitionIntervalHoursAttribute()
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
    public function getNotifyAtHoursAttribute()
    {
        $hours = \floor($this->notify_at / 60);
        $minutes = $this->notify_at % 60;

        return \sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Get the aircraft of the maintenance.
     */
    public function aircraft()
    {
        return $this->belongsTo('App\Models\Aircraft')->withTrashed();
    }

    /**
     * Determine if the maintenance is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return ! \is_null($this->dom);
    }

    /**
     * Scope a query to filter for the dom.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeDom($query, $dom)
    {
        return $query->where('dom', '=', Carbon::make($dom)->toDateString());
    }

    /**
     * Scope a query to filter for the maintained after date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeDomAfter($query, $dom)
    {
        return $query->where('dom', '>=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter for the maintained before date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dom
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeDomBefore($query, $dom)
    {
        return $query->where('dom', '<=', Carbon::parse($dom));
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with less than x minutes in maintenance_at.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $maintenanceAt
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeMaintenanceAtLessThan($query, $maintenanceAt)
    {
        return $query->where('maintenance_at', '<=', $maintenanceAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with more than x minutes in maintenance_at.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $maintenanceAt
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeMaintenanceAtMoreThan($query, $maintenanceAt)
    {
        return $query->where('maintenance_at', '>=', $maintenanceAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with less than x minutes in notify_at.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $notifyAt
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeNotifyAtLessThan($query, $notifyAt)
    {
        return $query->where('notify_at', '<=', $notifyAt);
    }

    /**
     * Scope a query to filter aircraft maintenance(s) with or with more than x minutes in notify_at.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $notifyAt
     * @return \Illuminate\Database\Eloquent\Builder|AircraftMaintenance
     */
    public function scopeNotifyAtMoreThan($query, $notifyAt)
    {
        return $query->where('notify_at', '>=', $notifyAt);
    }

    /**
     * Set the date of maintenance and convert it to Carbon.
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
