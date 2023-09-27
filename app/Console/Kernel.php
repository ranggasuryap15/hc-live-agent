<?php

namespace App\Console;

use App\Conversation;
use DateTime;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // update perhari, setiap created at yang sudah melewati hari ini, maka di update
        $schedule->call(function () {
            //
            $today = new DateTime();
            $today->format('Y-m-d');

            $conversation = Conversation::whereRaw("TO_CHAR(created_at, 'yyyy-mm-dd') < $today")->update([
                'in_queue' => false,
                'is_resolved' => true,
            ]);
            $conversation->save();
        })->daily();

        // $schedule->command('inspire')
        //          ->hourly();
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
