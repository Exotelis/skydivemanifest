<?php

namespace App\Jobs\AircraftMaintenance;

use App\Mail\AircraftMaintenance as Mailable;
use App\Models\AircraftMaintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendMaintenanceNotification
 * @package App\Jobs\AircraftMaintenance
 */
class SendMaintenanceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
       $maintenance = AircraftMaintenance::with('aircraft')
            ->whereDom(null)
            ->whereNotified(false)
            ->whereNotNull('notify_at')
            ->get();
        $notificationsMail = config('mail.notifications_mail');

       foreach ($maintenance as $m) {
           // Skip if notification threshold is not reached
           if ($m->notify_at > $m->aircraft->operation_time) {
               continue;
           }

           if (! \is_null($notificationsMail)) {
               // Send mail
               $mail = (new Mailable($notificationsMail, $m))->onQueue('mail');
               Mail::to($notificationsMail)->send($mail);
           } else {
               Log::warning("[Config] No notifications email has been configured. Please add the"
                   . " 'MAIL_NOTIFICATIONS_ADDRESS' key to your '.env' file");
           }

           // Update notification state
           try {
               DB::beginTransaction();

               $m->notified = true;
               $m->saveOrFail();

               DB::commit();
           } catch (\Throwable $exception) {
               Log::error("[Job] Could not update 'notified' state in job 'SendMaintenanceNotification'");
           }
       }
    }
}
