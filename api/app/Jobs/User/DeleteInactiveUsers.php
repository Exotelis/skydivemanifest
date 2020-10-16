<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Class DeleteInactiveUsers
 * @package App\Jobs\User
 */
class DeleteInactiveUsers implements ShouldQueue
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
        // TODO Should not soft deleted users that have valid tickets or invoices assigned.

        // Soft delete users that haven't been updated (inactive) for x months, beginning at the end of the year.
        // Admin users will be ignored and not soft deleted automatically.
        $users = $users->where('role_id', '!=', adminRole())
            ->where('updated_at', '<', Carbon::now()->subMonths(deleteInactiveUsers())->startOfYear())
            ->get();

        // Must iterate over users to call delete event which will send the notification.
        foreach ($users as $user) {
            $user->delete();
        }
    }
}
