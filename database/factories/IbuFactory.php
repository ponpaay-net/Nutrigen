<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IbuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('320########'),
            'nama' => 'Ibu ' . $this->faker->firstNameFemale(),
            'no_hp_wa' => $this->faker->unique()->phoneNumber(),
            'token_whatsapp' => Str::random(10),
        ];
    }
}
