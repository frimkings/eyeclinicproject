<?php

namespace App\Console;

use App\Services\LicenseService;
use App\Support\Feature;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('system:health-heartbeat')->everyMinute()->withoutOverlapping();

        if (LicenseService::has(Feature::SCHEDULED_BACKUPS)) {
            // DB-only snapshot every 5 minutes (fast, small)
            $schedule->command('backup:run --only-db')->everyFiveMinutes();

            // Full backup twice daily: DB + uploaded files + .env
            $schedule->command('backup:run')->dailyAt('11:30');
            $schedule->command('backup:run')->dailyAt('16:30');

            // Copy latest backup to any configured external drives (runs 30 min after each full backup)
            $schedule->command('backup:copy-to-drives')->dailyAt('12:00');
            $schedule->command('backup:copy-to-drives')->dailyAt('17:00');

            // Prune old backups per retention tiers (today/week/month/year)
            $schedule->command('backup:prune-custom')->hourly();

            // Health check: alert via email if backups are missing or too old
            $schedule->command('backup:monitor')->dailyAt('08:00');
        }

        if (LicenseService::has(Feature::SMS_CAMPAIGNS)) {
            // Birthday SMS — sends to patients whose DOB month/day matches today
            $schedule->command('sms:birthday-wishes')->dailyAt('08:00')->withoutOverlapping();

            // Appointment reminders — fires every hour, catches appointments ~24h out
            $schedule->command('sms:appointment-reminders')->hourly()->withoutOverlapping();
        }

        if (LicenseService::has(Feature::SPECTACLES_PRO)) {
            // Spectacle renewal reminders — daily, finds Collected orders whose renewal_date is X days away
            $schedule->command('sms:spectacle-renewal-reminders')->dailyAt('09:00')->withoutOverlapping();
        }

        // Prune unbounded log tables to keep working set small
        $schedule->command('logs:prune')->dailyAt('03:00')->withoutOverlapping();

        // Clean up abandoned carts (stale prescription items with no purchase)
        $schedule->call(fn () => \App\Models\Cart::cleanupAbandonedCarts())->name('cleanup-abandoned-carts')->dailyAt('03:30')->withoutOverlapping();

        // Patient recall — daily, applies admin-configured inactivity threshold
        try {
            $recallSettings = \App\Models\Setting::getSettings();
            if (!empty($recallSettings->recall_sms_enabled)) {
                $schedule->command('sms:recall-patients')->dailyAt('09:00')->withoutOverlapping();
            }
        } catch (\Throwable) {}

        // Financial report delivery — schedule driven by admin settings (PRO only)
        try {
            $reportSettings = \App\Models\Setting::getSettings();
            if (LicenseService::has(Feature::REPORT_DELIVERY)) {
                $schedule->command('report:retry-financial')->everyMinute()->withoutOverlapping();

                if ($reportSettings->report_enabled && !empty($reportSettings->report_recipients)) {
                    $cmd = $schedule->command('report:send-financial');
                    if ($reportSettings->report_frequency === 'weekly') {
                        $cmd->weeklyOn((int) $reportSettings->report_day, $reportSettings->report_time ?? '08:00')
                            ->withoutOverlapping();
                    } else {
                        $cmd->dailyAt($reportSettings->report_time ?? '08:00')
                            ->withoutOverlapping();
                    }
                }
            }
        } catch (\Throwable) {}
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
