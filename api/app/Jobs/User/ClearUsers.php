<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class ClearUsers
 * @package App\Jobs\User
 */
class ClearUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param  \App\Models\User $users
     * @return void
     */
    public function handle(\App\Models\User $users)
    {
        // TODO - Should not be deleted if pending billings etc. probably it would fail anyway because of the
        //        constraints -> Take care of this when implementing this feature!
        $configInactive = config('app.users.delete_inactive_after');
        $inactiveUsers = $users
            ->withTrashed()
            ->where('role_id', '!=', adminRole())
            ->where(function($query) use ($configInactive) {
                $query->where(function ($q) use ($configInactive) {
                    $q->where('last_logged_in', '!=', null)
                        ->where('last_logged_in', '<', Carbon::now()->subMonths($configInactive));
                });
                $query->orWhere(function ($q) use ($configInactive) {
                    $q->where('last_logged_in', '=', null)
                        ->where('updated_at', '<', Carbon::now()->subMonths($configInactive));
                });
            })->forceDelete();

        if ($inactiveUsers) {
            Log::info("Deleted '{$inactiveUsers}' inactive users.");
        }

        $configUnverified = config('app.users.delete_unverified_after');
        $unverifiedUsers = $users
            ->withTrashed()
            ->where('email_verified_at', '=', null)
            ->where('created_at', '<', Carbon::now()->subDays($configUnverified))
            ->forceDelete();

        if ($unverifiedUsers) {
            Log::info("Deleted '{$unverifiedUsers}' unverified users.");
        }

    }
}
