<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kemenkes@nutrigen.go.id'],
            [
                'name' => 'Kementerian Kesehatan RI',
                'password' => Hash::make('Kemenkes2026!'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
