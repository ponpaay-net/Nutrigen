<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->foreignId('validated_by')
                ->nullable()
                ->after('catatan_validator')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable()->after('validated_by');
        });
    }

    public function down(): void
    {
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['validated_by', 'validated_at']);
        });
    }
};
