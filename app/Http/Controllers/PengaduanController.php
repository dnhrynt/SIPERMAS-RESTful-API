<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Resources\PengaduanResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PengaduanController extends Controller
{
    
    // 1. GET /api/v1/pengaduan (Daftar semua laporan)
    public function index(Request $request)
    {
        $pengaduan = Pengaduan::with(['user:id,name', 'kategori:id,nama']) // Eager loading relasi agar hemat query (N+1 Problem)
            // 1. Filter berdasarkan status (contoh: ?status=proses)
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            // 2. Filter berdasarkan kategori (contoh: ?kategori_id=2)
            ->when($request->filled('kategori_id'), function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori_id);
            })
            // 3. Filter pencarian kata kunci (contoh: ?search=jalan)
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
                });
            })
            ->latest() // Urutkan dari pengaduan terbaru
            ->paginate($request->integer('per_page', 10)); // Default 10 item, bisa di-custom via query params (?per_page=20)

        return response()->json($pengaduan);
    }

    // 2. POST /api/v1/pengaduan (Buat laporan baru)
    public function store(StorePengaduanRequest $request)
    {
        Gate::authorize('create', Pengaduan::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id; // Ambil ID dari user yang sedang login

        if ($request->hasFile('foto')) {
            $validated['foto_path'] = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create($validated);

        return new PengaduanResource($pengaduan->load(['user', 'kategori']));
    }

    // 3. GET /api/v1/pengaduan/{pengaduan} (Detail 1 laporan)
    public function show(Pengaduan $pengaduan)
    {
        Gate::authorize('view', $pengaduan);

        return new PengaduanResource($pengaduan->load(['user', 'kategori']));
    }

    // 4. PUT/PATCH /api/v1/pengaduan/{pengaduan} (Update status/isi laporan)
    public function update(Request $request, Pengaduan $pengaduan)
    {
        Gate::authorize('update', $pengaduan);

        // Validasi input untuk update (misal: merubah status pengaduan)
        $validated = $request->validate([
            'status' => 'required|in:pending,proses,selesai',
            'judul' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
        ]);

        $pengaduan->update($validated);

        return response()->json([
            'message' => 'Status pengaduan berhasil diperbarui',
            'data' => new PengaduanResource($pengaduan->load(['user', 'kategori']))
        ], 200);
    }

    // 5. DELETE /api/v1/pengaduan/{pengaduan} (Hapus laporan)
    public function destroy(Pengaduan $pengaduan)
    {
        Gate::authorize('delete', $pengaduan);

        // Hapus file foto dari storage jika ada (mencegah file sampah di server)
        if ($pengaduan->foto_path) {
            Storage::disk('public')->delete($pengaduan->foto_path);
        }

        $pengaduan->delete();

        return response()->json([
            'message' => 'Laporan pengaduan berhasil dihapus'
        ], 200);
    }
}