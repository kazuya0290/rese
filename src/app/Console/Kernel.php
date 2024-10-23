<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminderMail;

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
    // 毎日朝6時に実行
    $schedule->call(function () {
        $today = now()->format('Y-m-d');

        $upcomingReservations = Reservation::whereDate('date', $today)->get();
        
        // 予約がある場合にメール送信
        foreach ($upcomingReservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminderMail($reservation));
            }
        })->dailyAt('06:00');
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
