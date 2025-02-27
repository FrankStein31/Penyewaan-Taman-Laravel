<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * These should be classes that implement the ShouldAutoRetry interface.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Jalankan setiap menit
        $schedule->command('taman:update-status')->everyMinute();
        
        // Atau jalankan setiap jam
        // $schedule->command('taman:update-status')->hourly();
        
        // Jalankan command cek pemesanan kadaluarsa setiap 30 menit
        $schedule->command('bookings:check-expired')->everyThirtyMinutes();
    }

    /**
     * Register the commands for your application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 