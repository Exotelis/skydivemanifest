<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * Class UserWaiver
 * @package App\Models
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|UserWaiver newModelQuery()
 * @method static Builder|UserWaiver newQuery()
 * @method static Builder|UserWaiver query()
 * @method static Builder|UserWaiver whereCreatedAt($value)
 * @method static Builder|UserWaiver whereUpdatedAt($value)
 * @mixin Builder
 */
class UserWaiver extends Pivot
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ['user_id', 'waiver_id'];
}
