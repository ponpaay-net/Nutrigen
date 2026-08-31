<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PuskesmasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_puskesmas' => 'P' . $this->faker->unique()->numerify('######'),
            'nama' => 'Puskesmas ' . $this->faker->city(),
            'alamat' => $this->faker->address(),
        ];
    }
}
