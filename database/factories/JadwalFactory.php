<?php

namespace Database\Factories;

use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'posyandu_id' => Posyandu::factory(),
            'judul' => 'Posyandu Bulanan ' . $this->faker->monthName(),
            'tanggal' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '12:00:00',
            'lokasi' => 'Balai Warga ' . $this->faker->streetName(),
            'catatan' => 'Bawa Buku KIA',
        ];
    }
}
