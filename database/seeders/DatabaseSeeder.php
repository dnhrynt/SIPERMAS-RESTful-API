<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::firstOrCreate(['nama_kategori' => 'Infrastruktur & Fasilitas Umum']);
        Kategori::firstOrCreate(['nama_kategori' => 'Kebersihan & Lingkungan']);
        Kategori::firstOrCreate(['nama_kategori' => 'Pelayanan Publik']);

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => 'password123', 
                'role' => 'admin',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'ahmad@gmail.com'],
            [
                'name' => 'Ahmad Warga',
                'password' => 'password123', 
                'role' => 'warga',
            ]
        );
    }
}