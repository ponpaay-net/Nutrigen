<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add soft deletes to core domain tables so records can be recovered
     * and audit trails preserved instead of being hard-deleted.
     */
    public function up(): void
    {
        foreach (['balitas', 'pengukurans', 'posyandus', 'jadwals'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['balitas', 'pengukurans', 'posyandus', 'jadwals'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};
