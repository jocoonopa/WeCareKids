<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\RepairCellStandard::class,
        Commands\RepairCell::class,
        Commands\RepairDiag::class,
        Commands\CopyGroup::class,
        Commands\CellSeed::class,
        Commands\SortoutChannel::class
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date = \Carbon\Carbon::now()->toW3cString();
        $environment = env('APP_ENV');
        $database = env('DB_CONNECTION');
        $schedule->command(
            "db:backup --database={$database} --destination=local --destinationPath=/{$environment}/{$environment}_{$date} --compression=gzip"
            )->twiceDaily(12, 21);
    }
}
