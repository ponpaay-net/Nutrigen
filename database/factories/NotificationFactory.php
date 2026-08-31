<?php

namespace Database\Factories;

use App\Models\Ibu;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ibu_id' => Ibu::factory(),
            'petugas_id' => null,
            'title' => $this->faker->sentence(3),
            'type' => 'info',
            'message' => $this->faker->paragraph(),
            'is_read' => $this->faker->boolean(),
        ];
    }
}
