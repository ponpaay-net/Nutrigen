<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory OrangTua versi aktif (model App\Models\OrangTua).
 * Factory lama (IbuFactory dll.) mereferensi model yang sudah tidak ada
 * dan dibiarkan apa adanya sampai pembersihan KRITIS-01/03.
 */
class OrangTuaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'no_kk' => $this->faker->unique()->numerify('3204##########'),
            'nik_ayah' => $this->faker->optional()->numerify('3204##########'),
            'nik_ibu' => $this->faker->numerify('3204##########'),
            'nama_ibu' => 'Ibu ' . $this->faker->firstName('female'),
            'nama_ayah' => $this->faker->optional()->name('male'),
            'pekerjaan_ayah' => $this->faker->optional()->jobTitle(),
            'pekerjaan_ibu' => $this->faker->optional()->jobTitle(),
            'no_hp_whatsapp' => '62' . $this->faker->unique()->numerify('8##########'),
            'alamat' => $this->faker->address(),
            'kecamatan' => $this->faker->citySuffix(),
        ];
    }
}
