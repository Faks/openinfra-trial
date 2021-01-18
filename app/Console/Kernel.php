<?php

namespace App\Console;

use App\Console\Commands\AuthLoginCommand;
use App\Console\Commands\AuthRegisterCommand;
use App\Console\Commands\BalanceCommand;
use App\Console\Commands\DepositCommand;
use App\Console\Commands\HistoryCommand;
use App\Console\Commands\WithdrawCommand;
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
        AuthLoginCommand::class,
        AuthRegisterCommand::class,
        BalanceCommand::class,
        DepositCommand::class,
        WithdrawCommand::class,
        HistoryCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
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
