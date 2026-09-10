<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Seed database hanya bila belum berisi data user.
 *
 * Dipakai saat deploy/start container agar data demo terisi sekali saja.
 * Aman dijalankan berulang: jika sudah ada user, perintah ini tidak melakukan
 * apa-apa (mencegah "Duplicate entry" dari seeder yang tidak idempoten).
 */
class SeedIfEmpty extends Command
{
    protected $signature = 'nutrigen:seed-if-empty';

    protected $description = 'Isi data demo (seeder) hanya jika database masih kosong.';

    public function handle(): int
    {
        try {
            $userCount = User::count();
        } catch (\Throwable $e) {
            $this->error('Tidak dapat membaca tabel users: ' . $e->getMessage());
            return self::FAILURE;
        }

        if ($userCount > 0) {
            $this->info("Database sudah berisi {$userCount} user — seed dilewati.");
            return self::SUCCESS;
        }

        $this->info('Database kosong — menjalankan seeder demo...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->line(Artisan::output());

        $this->info('Seeder selesai. Total user: ' . User::count());
        return self::SUCCESS;
    }
}