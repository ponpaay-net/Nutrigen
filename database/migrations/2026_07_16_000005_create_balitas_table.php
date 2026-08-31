<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
            $table->foreignId('posyandu_id')->constrained('posyandus')->cascadeOnDelete();
            $table->string('nik', 16)->unique()->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->decimal('berat_lahir', 5, 2)->nullable();
            $table->decimal('panjang_lahir', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};
