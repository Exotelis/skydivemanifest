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
 * Class ClearEmailChanges
 * @package App\Jobs\Auth
 */
class ClearEmailChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(config('auth.email_changes') as $config) {
            try {
                DB::table($config['table'])
                    ->where('created_at', '<', Carbon::now()->subMinutes($config['expire']))
                    ->delete();
            } catch(\Exception $exception) {
                Log::error('Failed to delete email changes: ' . $exception->getMessage());
            }
        }
    }
}
