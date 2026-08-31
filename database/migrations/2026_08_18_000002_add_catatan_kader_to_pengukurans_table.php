<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengukurans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengukurans', 'catatan_kader')) {
                $table->text('catatan_kader')->nullable()->after('catatan_validator');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengukurans', function (Blueprint $table) {
            if (Schema::hasColumn('pengukurans', 'catatan_kader')) {
                $table->dropColumn('catatan_kader');
            }
        });
    }
};
