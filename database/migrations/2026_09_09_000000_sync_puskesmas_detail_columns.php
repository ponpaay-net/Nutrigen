<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sinkronkan kolom detail tabel `puskesmas` dengan model & controller.
 *
 * Migration 2026_09_05_152218 membuat kolom `no_telepon`, sedangkan seluruh
 * kode (model, controller, blade) memakai `no_telp`. Selain itu kolom
 * `kecamatan`, `kabupaten_kota`, dan `provinsi` yang dipakai oleh
 * SuperAdminController & PuskesmasController belum pernah dibuat.
 *
 * Ditulis dengan SQL mentah + snapshot kolom via SHOW COLUMNS agar deterministik
 * dan idempoten di berbagai kondisi (kolom sudah ada, rename sudah pernah terjadi,
 * keduanya muncul sekaligus), menghindari kendala caching schema builder Laravel.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('puskesmas')) {
            return;
        }

        // Snapshot kolom terkini (hanya yang relevan).
        $columns = array_column(
            DB::select('SHOW COLUMNS FROM puskesmas'),
            'Field'
        );
        $hasNoTelepon = in_array('no_telepon', $columns, true);
        $hasNoTelp    = in_array('no_telp', $columns, true);
        $hasKecamatan = in_array('kecamatan', $columns, true);
        $hasKabKota   = in_array('kabupaten_kota', $columns, true);
        $hasProvinsi  = in_array('provinsi', $columns, true);

        // 1. Normalisasi kolom telepon: target tunggal `no_telp`.
        if ($hasNoTelp && $hasNoTelepon) {
            DB::statement('ALTER TABLE puskesmas DROP COLUMN no_telepon');
            $hasNoTelepon = false;
        } elseif (!$hasNoTelp && $hasNoTelepon) {
            DB::statement('ALTER TABLE puskesmas CHANGE COLUMN no_telepon no_telp VARCHAR(255) NULL');
            $hasNoTelp = true;
        }

        if (!$hasNoTelp) {
            DB::statement('ALTER TABLE puskesmas ADD COLUMN no_telp VARCHAR(255) NULL');
        }

        // 2. Kolom wilayah.
        if (!$hasKecamatan) {
            DB::statement('ALTER TABLE puskesmas ADD COLUMN kecamatan VARCHAR(255) NULL');
        }
        if (!$hasKabKota) {
            DB::statement('ALTER TABLE puskesmas ADD COLUMN kabupaten_kota VARCHAR(255) NULL');
        }
        if (!$hasProvinsi) {
            DB::statement('ALTER TABLE puskesmas ADD COLUMN provinsi VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        Schema::table('puskesmas', function (Blueprint $table) {
            $table->dropColumn(['no_telp', 'kecamatan', 'kabupaten_kota', 'provinsi']);
        });
    }
};