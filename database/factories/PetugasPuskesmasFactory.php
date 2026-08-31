<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetugasPuskesmasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'puskesmas_id' => Puskesmas::factory(),
            'nip' => $this->faker->unique()->numerify('198########'),
            'jabatan' => 'Ahli Gizi',
        ];
    }
}
