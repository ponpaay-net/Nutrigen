<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // NOTE: Migrasi ini TIDAK lagi memanggil DatabaseSeeder.
        //
        // Sebelumnya ia menjalankan `php artisan db:seed`, sehingga ketika
        // `migrate:fresh --seed` dipakai (umum di dev/testing) DatabaseSeeder
        // akan dijalankan DUA kali (di sini + di flag --seed) dan crash dengan
        // "Duplicate entry ... users_email_unique".
        //
        // Seeding seharusnya cukup dilakukan SEKALI lewat `php artisan db:seed`
        // atau `php artisan migrate:fresh --seed`. Migrasi cukup bertugas
        // menyiapkan struktur, bukan mengisi data demo.
        Schema::disableForeignKeyConstraints();

        $tables = ['pengukurans', 'jadwals', 'balitas', 'orang_tuas', 'kaders', 'puskesmas', 'posyandus', 'users'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
