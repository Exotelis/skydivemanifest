<?php

namespace App\Jobs\Auth;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ClearRestoreUsers
 * @package App\Jobs\Auth
 */
class ClearRestoreUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::table('restore_users')
                ->where('created_at', '<', Carbon::now()->subDays(recoverUsers()))
                ->delete();
        } catch (\Exception $exception) {
            Log::error('Failed to delete password resets: ' . $exception->getMessage());
        }
    }
}
