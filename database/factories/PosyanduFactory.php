<?php

namespace Database\Factories;

use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosyanduFactory extends Factory
{
    public function definition(): array
    {
        return [
            'puskesmas_id' => Puskesmas::factory(),
            'nama' => 'Posyandu ' . $this->faker->streetName(),
            'alamat' => $this->faker->address(),
        ];
    }
}
