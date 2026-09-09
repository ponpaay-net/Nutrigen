<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesi_posyandus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('total_sasaran')->default(0);
            $table->integer('total_terukur')->default(0);
            $table->integer('total_absen')->default(0);
            $table->decimal('persentase_kehadiran', 5, 2)->default(0);
            $table->text('catatan_kader')->nullable();
            $table->enum('status', ['draft', 'dikirim', 'selesai_verifikasi'])->default('draft');
            $table->dateTime('tanggal_kirim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_posyandus');
    }
};
