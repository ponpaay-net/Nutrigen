<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel audit log aktivitas administrator (Super Admin).
 * Mencatat siapa melakukan aksi apa, pada entitas mana, kapan, dan dari IP mana.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            return;
        }

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 30);                 // create | update | delete | login | other
            $table->string('model_type')->nullable();     // FQCN model atau label entitas
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};