<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'posyandu_id' => Posyandu::factory(),
            'no_hp_wa' => $this->faker->phoneNumber(),
        ];
    }
}
