<?php

namespace App\Models;

use App\Contracts\Logable;
use App\Traits\ModelDiff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Currency
 * @package App\Models
 *
 * @property string $code
 * @property string $currency
 * @property string $symbol
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|Currency newModelQuery()
 * @method static Builder|Currency newQuery()
 * @method static Builder|Currency query()
 * @method static Builder|Currency whereCode($value)
 * @method static Builder|Currency whereCreatedAt($value)
 * @method static Builder|Currency whereCurrency($value)
 * @method static Builder|Currency whereSymbol($value)
 * @method static Builder|Currency whereUpdatedAt($value)
 * @mixin Builder
 */
class Currency extends Model implements Logable
{
    use HasFactory, ModelDiff;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'code';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'currency',
        'symbol',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString()
    {
        return "{$this->code}|{$this->currency}";
    }
}
