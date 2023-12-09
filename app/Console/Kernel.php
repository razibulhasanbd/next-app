<?php

namespace App\Console;

use App\Constants\CommandConstants;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Controller;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\ScheduledJobsController;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\NewsCalendar::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call('App\Http\Controllers\ScheduledJobsController@weeklyTradeCloseV3')->name("weeklyTradeClose")->withoutOverlapping()->cron('40 23 * * Fri');

        $schedule->command(CommandConstants::Weekly_Bulk_Trade_Close)->withoutOverlapping()->cron('40 23 * * Fri');
        $schedule->command(CommandConstants::ScheduleJobs_TradeSyncJob)->withoutOverlapping()->cron('0 */4 * * *');
        $schedule->command(CommandConstants::ScheduleJobs_ProfitChecker)->withoutOverlapping()->cron('10 00 * * *');
        $schedule->command(CommandConstants::ScheduleJobs_Mtinit)->withoutOverlapping()->cron('*/30 * * * *');
        $schedule->command(CommandConstants::Pending_Orders_CrossCheck_Command)->withoutOverlapping()->everyFifteenMinutes();
        $schedule->command(CommandConstants::News_Calendar_Push_Notification_To_All)->withoutOverlapping()->everyMinute();
        // $schedule->command(CommandConstants::Breach_reminder_email)->dailyAt('02:00'); //coupon code missing

        $schedule->command('news:calendar')
                 ->cron('30 14 * * *');
    }

    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {
        // this artisan command will run every second
        // $shortSchedule->command('App\Http\Controllers\RuleBreachJobController@rulesCheckerReturn')->everySeconds(10)->withoutOverlapping();
        $shortSchedule->command('rulebreach:short')->everySeconds(10)->withoutOverlapping();
        $shortSchedule->command('ping:short')->everySeconds(30)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
