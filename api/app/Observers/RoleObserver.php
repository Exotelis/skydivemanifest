<?php

namespace App\Observers;

use App\Models\Role;
use Illuminate\Support\Facades\Log;

/**
 * Class RoleObserver
 * @package App\Observers
 */
class RoleObserver extends BaseObserver
{
    /**
     * Handle the role "created" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        Log::info("[Role] '{$role->id}|{$role->name}' has been created by '{$this->executedBy}'");
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        $diff = getModelDiff($role, [], true);
        Log::info("[Role] '{$role->id}|{$role->name}' has been updated by '{$this->executedBy}' ({$diff})");
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        Log::info("[Role] '{$role->id}|{$role->name}' has been deleted by '{$this->executedBy}'");
    }
}
