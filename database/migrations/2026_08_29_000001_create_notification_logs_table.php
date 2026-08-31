<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TINGGI-02 — Tabel jejak semua pengiriman notifikasi (WhatsApp dll).
     * Kolom sesuai rekomendasi audit Fase 1 poin 3: id, orang_tua_id,
     * pengukuran_id, channel, payload, status, response_body, created_at.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->nullable()->constrained('orang_tuas')->nullOnDelete();
            $table->foreignId('pengukuran_id')->nullable()->constrained('pengukurans')->cascadeOnDelete();
            $table->string('channel')->default('whatsapp');
            $table->string('status')->default('sent'); // sent | failed
            $table->text('payload')->nullable();
            $table->text('response_body')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
