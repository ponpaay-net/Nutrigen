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
        // Pastikan enum role sudah mendukung super_admin SEBELUM seeding
        // (tidak bergantung urutan migrasi add_super_admin_role).
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'puskesmas', 'kader', 'ibu') DEFAULT 'ibu'");

        // NOTE: Migrasi ini TIDAK memanggil DatabaseSeeder.
        //
        // Sebelumnya ia menjalankan `php artisan db:seed`, sehingga ketika
        // `migrate:fresh --seed` dipakai (umum di dev/testing) DatabaseSeeder
        // akan dijalankan DUA kali (di sini + di flag --seed) dan crash dengan
        // "Duplicate entry ... users_email_unique".
        //
        // Seeding kini dilakukan terpisah: `php artisan db:seed` atau
        // `php artisan nutrigen:seed-if-empty` (idempoten).

        // Disable foreign key checks
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
