<?php

namespace App\Console;

use App\Console\Commands\CreateAdminUserCommand;
use App\Console\Commands\FilterMakeCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateAdminUserCommand::class,
        FilterMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        /**
         * Artisan commands
         */

        $schedule->command('passport:purge')->daily();

        /**
         * Jobs
         */

        // Aircraft Maintenance
        $schedule->job((new \App\Jobs\AircraftMaintenance\SendMaintenanceNotification())
            ->onConnection('sync')
            ->onQueue('job')
        )->everyMinute();

        // Auth
        $schedule->job((new \App\Jobs\Auth\ClearEmailChanges())->onConnection('sync')->onQueue('job'))
            ->daily();
        $schedule->job((new \App\Jobs\Auth\ClearPasswordResets())->onConnection('sync')->onQueue('job'))
            ->daily();
        $schedule->job((new \App\Jobs\Auth\ClearRestoreUsers())->onConnection('sync')->onQueue('job'))
            ->daily();

        // User
        $schedule->job((new \App\Jobs\User\ClearUsers())->onConnection('sync')->onQueue('job'))
            ->daily();
        $schedule->job((new \App\Jobs\User\DeleteInactiveUsers())->onConnection('sync')->onQueue('job'))
            ->daily();
        $schedule->job((new \App\Jobs\User\DeleteUnverifiedUsers())->onConnection('sync')
            ->onQueue('job'))->daily();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
