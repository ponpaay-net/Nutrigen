<?php

namespace Database\Factories;

use App\Models\Ibu;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalitaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ibu_id' => Ibu::factory(),
            'nik' => $this->faker->unique()->numerify('320########'),
            'nama' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-3 years', '-6 months')->format('Y-m-d'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
        ];
    }
}
