<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Truncate tables with old dummy data to ensure fresh realistic data on Railway Production
        $tables = ['pengukurans', 'jadwals', 'balitas', 'orang_tuas', 'kaders', 'puskesmas', 'posyandus', 'users'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();

        // Run full clean seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\DatabaseSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
