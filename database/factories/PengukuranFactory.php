<?php

namespace Database\Factories;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengukuranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'balita_id' => Balita::factory(),
            'jadwal_id' => null, // Can be overridden
            'kader_id' => Kader::factory(),
            'posyandu_id' => Posyandu::factory(),
            'tanggal_ukur' => clone $this->faker->dateTimeBetween('-3 months', 'now'),
            'berat_badan' => $this->faker->randomFloat(2, 5, 15),
            'tinggi_badan' => $this->faker->randomFloat(2, 60, 100),
            'z_score_bb_u' => $this->faker->randomFloat(2, -3, 3),
            'z_score_tb_u' => $this->faker->randomFloat(2, -3, 3),
            'z_score_bb_tb' => $this->faker->randomFloat(2, -3, 3),
            'status_stunting' => $this->faker->randomElement(['Normal', 'Normal', 'Normal', 'Pendek', 'Sangat Pendek']),
            'status_gizi' => $this->faker->randomElement(['Baik', 'Baik', 'Baik', 'Kurang', 'Buruk']),
        ];
    }
}
