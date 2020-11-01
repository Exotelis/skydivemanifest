<?php

namespace App\Mail;

use App\Models\AircraftMaintenance as AircraftMaintenanceModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * Class AircraftMaintenance
 * @package App\Mail
 */
class AircraftMaintenance extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The email address where the email should be send to.
     *
     * @var string
     */
    public $email;

    /**
     * The aircraft maintenance model.
     *
     * @var AircraftMaintenanceModel
     */
    public $maintenance;
    /**
     * Create a new message instance.
     *
     * @param  string                   $email
     * @param  AircraftMaintenanceModel $maintenance
     * @return void
     */
    public function __construct(string $email, AircraftMaintenanceModel $maintenance)
    {
        $this->email = $email;
        $this->maintenance = $maintenance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lang = App::getLocale();
        $subject = __('mails.subject_aircraft_maintenance');

        $diff = $this->maintenance->maintenance_at - $this->maintenance->aircraft->flight_time;
        $hours = $diff > 0 ? \floor($diff / 60) : 0;
        $minutes = $diff > 0 ? $diff % 60 : 0;

        Log::info("[Mail] 'App\Mail\AircraftMaintenance' has been sent to '{$this->email}'");

        return $this->view('mails.aircraft_maintenance.aircraft_maintenance')
            ->text('mails.aircraft_maintenance.aircraft_maintenance_plain')
            ->locale($lang)
            ->subject($subject)
            ->with([
                'hours'        => $hours,
                'id'           => $this->maintenance->id,
                'minutes'      => $minutes,
                'registration' => $this->maintenance->aircraft->registration,
            ]);
    }
}
