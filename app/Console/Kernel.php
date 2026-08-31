<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Kirim pengingat WhatsApp H-2/H-1 ke ibu sebelum jadwal posyandu.
        // Driver default 'log' -> hanya mencatat ke notification_logs (demo-safe);
        // pengiriman asli aktif saat WA_DRIVER + token di-isikan (dikelola terpisah).
        $schedule->command('whatsapp:reminders')->dailyAt('08:00');
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
