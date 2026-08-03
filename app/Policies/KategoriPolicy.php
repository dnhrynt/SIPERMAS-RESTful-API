<?php

namespace App\Policies;

use App\Models\Kategori;
use App\Models\User;

class KategoriPolicy
{
    // Semua user terautentikasi (Admin, Petugas, Warga) boleh melihat list & detail kategori
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kategori $kategori): bool
    {
        return true;
    }

    // Hanya Admin (atau Petugas) yang boleh Create, Update, Delete
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'petugas']);
    }

    public function update(User $user, Kategori $kategori): bool
    {
        return in_array($user->role, ['admin', 'petugas']);
    }

    public function delete(User $user, Kategori $kategori): bool
    {
        return $user->role === 'admin'; // Hanya Admin Utama
    }
}