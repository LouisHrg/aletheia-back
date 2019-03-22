<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrawlCommand::class,
        Commands\CrawlSourcesScore::class,
        Commands\UpdateUrl::class,
        Commands\FetchSourcesCommand::class,
        Commands\FetchWordsCommand::class,
        Commands\FetchArticlesCommand::class,
        Commands\TestArticlesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
