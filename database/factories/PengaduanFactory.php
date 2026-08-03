<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengaduanFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Laravel otomatis membuatkan User & Kategori dummy jika tidak diisi secara eksplisit
            'user_id' => User::factory(),
            'kategori_id' => Kategori::factory(),
            'judul' => fake()->sentence(),
            'deskripsi' => fake()->paragraph(),
            'foto_path' => null,
            'status' => 'pending',
        ];
    }
}