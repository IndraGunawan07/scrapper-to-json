<?php

namespace App\Console;

use App\Jobs\ScrapperJob;
use App\Models\ScheduleTimestamp;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ScrapperCron::class,
    ];
    
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->everyMinute();
        $schedule->command('scrapper:cron')->everyMinute()->timezone('Asia/Jakarta')->when(function () {
            $scheduleTimestamp = ScheduleTimestamp::orderBy('created_at', 'desc')->first()?->created_at;
            
            if (Carbon::parse($scheduleTimestamp)->diffInMinutes(Carbon::now()) >= 7 || is_null($scheduleTimestamp)) {
                ScheduleTimestamp::create();
                return true;
            }
            return false;
        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
