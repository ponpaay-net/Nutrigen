<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify the enum to include 'draft'
        DB::statement("ALTER TABLE pengukurans MODIFY COLUMN status_validasi ENUM('draft', 'pending', 'approved', 'rejected') NOT NULL DEFAULT 'draft'");

        // 2. Add new columns
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->decimal('z_score_bbt', 5, 2)->nullable()->after('z_score_tbu');
            $table->string('status_bbt', 50)->nullable()->after('status_gizi');
            $table->string('status_tbu', 50)->nullable()->after('status_bbt');
            $table->string('status_bbu', 50)->nullable()->after('status_tbu');
            $table->string('rekomendasi_pmt', 100)->nullable()->after('status_bbu');
            $table->boolean('is_susulan')->default(false)->after('rekomendasi_pmt');
            $table->foreignId('sesi_posyandu_id')->nullable()->constrained('sesi_posyandus')->nullOnDelete()->after('id');
            // Wait, we need sesi_posyandus table created before this foreign key, 
            // OR just use a simple integer field first, then add foreign key in the other migration.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->dropForeign(['sesi_posyandu_id']);
            $table->dropColumn([
                'sesi_posyandu_id',
                'z_score_bbt',
                'status_bbt',
                'status_tbu',
                'status_bbu',
                'rekomendasi_pmt',
                'is_susulan'
            ]);
        });

        // Revert enum
        DB::statement("ALTER TABLE pengukurans MODIFY COLUMN status_validasi ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
