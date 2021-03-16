<?php

namespace App\Jobs\Auth;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ClearPasswordResets
 * @package App\Jobs\Auth
 */
class ClearPasswordResets implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(config('auth.passwords') as $config) {
            try {
                DB::table($config['table'])
                    ->where('created_at', '<', Carbon::now()->subMinutes($config['expire']))
                    ->delete();
            } catch (\Exception $exception) {
                Log::error('Failed to delete password resets: ' . $exception->getMessage());
            }
        }
    }
}
