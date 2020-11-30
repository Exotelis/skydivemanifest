<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

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
        // Delete users that have been soft deleted but not recovered their accounts in time
        $users = $users->onlyTrashed()
            ->where('role_id', '!=', adminRole())
            ->where('deleted_at', '<', Carbon::now()->subDays(recoverUsers()))
            ->get();

        // Must iterate over users to call delete event which will send the notification.
        foreach ($users as $user) {
            $user->forceDelete();
        }
    }
}
