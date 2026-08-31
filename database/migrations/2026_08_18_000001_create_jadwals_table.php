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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained('posyandus')->onDelete('cascade');
            $table->foreignId('kader_id')->nullable()->constrained('kaders')->onDelete('set null');
            $table->string('judul')->default('Layanan Posyandu & Penimbangan Balita');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->text('catatan')->nullable();
            $table->string('status', 30)->default('akan_datang'); // akan_datang, selesai, dibatalkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
