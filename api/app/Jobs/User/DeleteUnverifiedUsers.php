<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class DeleteUnverifiedUsers
 * @package App\Jobs\User
 */
class DeleteUnverifiedUsers implements ShouldQueue, ShouldBeEncrypted
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
        $unverifiedUsers = $users
            ->withTrashed()
            ->where('email_verified_at', '=', null)
            ->where('created_at', '<', Carbon::now()->subDays(deleteUnverifiedUsers()))
            ->forceDelete();

        if ($unverifiedUsers) {
            Log::info("[Job] Deleted '{$unverifiedUsers}' unverified users permanently.");
        }
    }
}
