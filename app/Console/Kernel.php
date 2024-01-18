<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\Salla\Crons as SallaCrons;
use App\Jobs\Shopify\Crons as ShopifyCrons;
use App\Jobs\TopRatedProductsJob;
use App\Jobs\RefreshTokens;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // update top rated products
        $schedule->command('toprated:products')->daily();
        $schedule->command('bestselling:products')->daily();
        $schedule->job(new SallaCrons)->everyMinute();
        $schedule->job(new ShopifyCrons)->everyMinute();

        $schedule->job(new RefreshTokens)->cron('0 0 1,10,20 * *'); // every 10 days
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
