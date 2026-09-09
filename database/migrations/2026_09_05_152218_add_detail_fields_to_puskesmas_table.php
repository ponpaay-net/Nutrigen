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
        Schema::table('puskesmas', function (Blueprint $table) {
            if (!Schema::hasColumn('puskesmas', 'kepala_puskesmas')) {
                $table->string('kepala_puskesmas')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('puskesmas', 'no_telepon') && !Schema::hasColumn('puskesmas', 'no_telp')) {
                $table->string('no_telepon')->nullable()->after('kepala_puskesmas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puskesmas', function (Blueprint $table) {
            $table->dropColumn(['kepala_puskesmas', 'no_telepon', 'kecamatan']);
        });
    }
};
