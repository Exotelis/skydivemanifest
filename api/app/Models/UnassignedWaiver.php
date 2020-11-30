<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class UnassignedWaiver
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $firstname
 * @property string $ip
 * @property string $lastname
 * @property string $signature
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Waiver|null $waiver
 * @method static Builder|UnassignedWaiver newModelQuery()
 * @method static Builder|UnassignedWaiver newQuery()
 * @method static Builder|UnassignedWaiver query()
 * @method static Builder|UnassignedWaiver whereCreatedAt($value)
 * @method static Builder|UnassignedWaiver whereEmail($value)
 * @method static Builder|UnassignedWaiver whereFirstname($value)
 * @method static Builder|UnassignedWaiver whereLastname($value)
 * @method static Builder|UnassignedWaiver whereSignature($value)
 * @method static Builder|UnassignedWaiver whereUpdatedAt($value)
 * @mixin Builder
 */
class UnassignedWaiver extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'firstname',
        'ip',
        'lastname',
        'signature',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the waiver that owns the unassigned_waiver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function waiver()
    {
        return $this->belongsTo('App\Models\Waiver');
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString()
    {
        return "{$this->id}|{$this->ip}";
    }
}
