<?php

namespace App\Policies;

use App\Models\Pengaduan;
use App\Models\User;

class PengaduanPolicy
{
    /**
     * Memilih siapa saja yang boleh melihat daftar pengaduan (index).
     */
    public function viewAny(User $user): bool
    {
        // Semua user yang sudah login (Admin, Petugas, Warga) boleh mengakses index
        return true; 
    }

    /**
     * Memilih siapa saja yang boleh membuat pengaduan (store).
     */
    public function create(User $user): bool
    {
        // ✅ Hanya Warga yang boleh membuat pengaduan baru
        return $user->isWarga(); 
    }

    /**
     * Admin dan Petugas bisa melihat semua, Warga hanya bisa melihat miliknya sendiri.
     */
    public function view(User $user, Pengaduan $pengaduan): bool
    {
        return $user->isAdmin() || $user->isPetugas() || $user->id === $pengaduan->user_id;
    }

    /**
     * Hanya Petugas & Admin yang boleh mengubah status pengaduan.
     */
    public function update(User $user, Pengaduan $pengaduan): bool
    {
        return $user->isAdmin() || $user->isPetugas();
    }

    /**
     * Hanya Admin yang boleh menghapus pengaduan.
     */
    public function delete(User $user, Pengaduan $pengaduan): bool
    {
        return $user->isAdmin();
    }
}