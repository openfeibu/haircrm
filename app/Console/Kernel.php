<?php

namespace App\Console;

use App\Console\Commands\MailScheduleCommand;
use App\Console\Commands\OnbuyOrderSyncCommand;
use App\Console\Commands\OnbuyOrderSyncUpdateCommand;
use App\Console\Commands\PricingCommand;
use App\Console\Commands\RestorePriceCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CmsCommand;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        CmsCommand::class,
        MailScheduleCommand::class,
        PricingCommand::class,
        RestorePriceCommand::class,
        OnbuyOrderSyncCommand::class,
        OnbuyOrderSyncUpdateCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('mail_schedule:auto')->everyMinute();
        $schedule->command('pricing:auto')->everyMinute();
        $schedule->command('restore_price:auto')->everyMinute();
        $schedule->command('onbuy_order_sync:auto')->everyFiveMinutes();
        $schedule->command('onbuy_order_sync_update:auto')->everyFiveMinutes()->between('10:00', '13:00');
		Log::info('schedule');
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
