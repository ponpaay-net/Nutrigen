<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Add 'super_admin' to the users.role enum EARLY.
 *
 * Migration 2026_08_19_000002_reseed_production_database menjalankan
 * DatabaseSeeder (dan SuperAdminSeeder) yang membuat akun ber-role
 * 'super_admin'. Namun enum role asli ('puskesmas','kader','ibu') belum
 * memuat nilai tersebut sampai migration 2026_09_04_*. Karena seeder
 * dijalankan lebih dulu, setiap migrate:fresh (test / deploy baru) akan
 * gagal dengan "Data truncated for column 'role'".
 *
 * Migration ini disisipkan SEBELUM reseed agar enum sudah memuat
 * 'super_admin' saat seeder dipanggil. Idempoten: hanya mengubah ketika
 * nilai 'super_admin' belum tercantum pada enum.
 */
return new class extends Migration
{
    public function up(): void
    {
        $column = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        if (empty($column)) {
            return;
        }

        $type = $column[0]->Type ?? '';
        if (!str_contains($type, 'super_admin')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'puskesmas', 'kader', 'ibu') DEFAULT 'ibu'");
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback — nilai super_admin dipertahankan agar
        // pembalikan tidak merusak akun yang sudah ada.
    }
};