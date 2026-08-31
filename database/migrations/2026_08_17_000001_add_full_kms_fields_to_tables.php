<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration          
{
    /**
     * Run the migrations.
     * Comprehensive KMS / KIA standards migration for NutriGen.
     */
    public function up(): void
    {
        // 1. Table: orang_tuas
        Schema::table('orang_tuas', function (Blueprint $table) {
            if (!Schema::hasColumn('orang_tuas', 'no_kk')) {
                $table->string('no_kk', 16)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orang_tuas', 'nik_ayah')) {
                $table->string('nik_ayah', 16)->nullable()->after('no_kk');
            }
            if (!Schema::hasColumn('orang_tuas', 'nik_ibu')) {
                $table->string('nik_ibu', 16)->nullable()->after('nik_ayah');
            }
            if (!Schema::hasColumn('orang_tuas', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('nama_ibu');
            }
            if (!Schema::hasColumn('orang_tuas', 'pekerjaan_ayah')) {
                $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            }
            if (!Schema::hasColumn('orang_tuas', 'pekerjaan_ibu')) {
                $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            }
        });

        // 2. Table: balitas
        Schema::table('balitas', function (Blueprint $table) {
            if (!Schema::hasColumn('balitas', 'no_bpjs')) {
                $table->string('no_bpjs')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('balitas', 'berat_lahir')) {
                $table->decimal('berat_lahir', 5, 2)->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('balitas', 'panjang_lahir')) {
                $table->decimal('panjang_lahir', 5, 2)->nullable()->after('berat_lahir');
            }
            if (!Schema::hasColumn('balitas', 'lingkar_kepala_lahir')) {
                $table->decimal('lingkar_kepala_lahir', 5, 2)->nullable()->after('panjang_lahir');
            }
        });

        // 3. Table: pengukurans
        Schema::table('pengukurans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengukurans', 'lingkar_kepala')) {
                $table->decimal('lingkar_kepala', 5, 2)->nullable()->after('tinggi_badan');
            }
            if (!Schema::hasColumn('pengukurans', 'asi_eksklusif')) {
                $table->boolean('asi_eksklusif')->default(false)->after('lingkar_kepala');
            }
            if (!Schema::hasColumn('pengukurans', 'status_kenaikan')) {
                $table->string('status_kenaikan')->nullable()->after('status_gizi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Table: orang_tuas
        Schema::table('orang_tuas', function (Blueprint $table) {
            $colsToDrop = [];
            foreach (['no_kk', 'nik_ayah', 'pekerjaan_ayah', 'pekerjaan_ibu'] as $col) {
                if (Schema::hasColumn('orang_tuas', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });

        // 2. Table: balitas
        Schema::table('balitas', function (Blueprint $table) {
            $colsToDrop = [];
            foreach (['no_bpjs', 'lingkar_kepala_lahir'] as $col) {
                if (Schema::hasColumn('balitas', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });

        // 3. Table: pengukurans
        Schema::table('pengukurans', function (Blueprint $table) {
            $colsToDrop = [];
            foreach (['lingkar_kepala', 'asi_eksklusif', 'status_kenaikan'] as $col) {
                if (Schema::hasColumn('pengukurans', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
