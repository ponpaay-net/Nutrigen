<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateUpgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Menjalankan HANYA migrasi upgrade NutriGen yang aman (soft-delete,
     * validator-tracking, notification_logs). Sengaja TIDAK menjalankan
     * 'reseed_production_database' yang bersifat destructive (truncate+reseed),
     * supaya data di environment target tidak terhapus.
     *
     * @var string
     */
    protected $signature = 'migrate:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jalankan migrasi upgrade aman (soft-delete, validator-tracking, notification_logs). Menghindari reseed yang destructive.';

    /**
     * Migrasi yang boleh dijalankan — aman, tidak menghapus data.
     *
     * @var array<int, string>
     */
    protected array $safeMigrations = [
        'database/migrations/2026_08_29_010000_add_soft_deletes_to_core_tables.php',
        'database/migrations/2026_08_27_000001_add_validator_tracking_to_pengukurans_table.php',
        'database/migrations/2026_08_29_000001_create_notification_logs_table.php',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        foreach ($this->safeMigrations as $path) {
            $this->call('migrate', [
                '--path'  => $path,
                '--force' => true,
            ]);
        }

        $this->newLine();
        $this->info('Selesai. Migrasi upgrade aman sudah dijalankan.');

        return Command::SUCCESS;
    }
}
