<?php

namespace App\Jobs\Waiver;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class DeleteExpiredWaivers
 * @package App\Jobs\Waiver
 */
class DeleteExpiredWaivers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $offsetWaivers = config('setting.waivers.keep');
        $offsetUnassignedWaivers = config('setting.waivers.keep_unassigned');

        // Delete expired signed waivers
        if ($offsetWaivers > 0) {
            $offsetWaivers = Carbon::now()->subMonths($offsetWaivers);
            $builder = \App\Models\UserWaiver::where('updated_at', '<=', $offsetWaivers);
            $count = $builder->count();
            $builder->delete();

            // Log deletion of signed waivers. Because of the multiple primary keys on the user_waiver table it's not
            // possible to delete the models directly.
            if ($count > 0) {
                Log::info("[User-Waiver] '{$count}' expired waivers have been deleted successfully");
            }
        }

        // Delete expired signed unassigned waivers
        if ($offsetUnassignedWaivers > 0) {
            $offsetUnassignedWaivers = Carbon::now()->subMonths($offsetUnassignedWaivers);
            $models = \App\Models\UnassignedWaiver::where('updated_at', '<=', $offsetUnassignedWaivers)
                ->get();

            foreach ($models as $model) {
                $model->delete();
            }
        }
    }
}
