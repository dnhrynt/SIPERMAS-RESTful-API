<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Http\Resources\KategoriResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // 1. Pastikan Facade Gate di-import

class KategoriController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Kategori::class); // 2. Gunakan Gate::authorize

        $kategori = Kategori::all();
        return KategoriResource::collection($kategori);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Kategori::class);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create($validated);

        return (new KategoriResource($kategori))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Kategori $kategori)
    {
        Gate::authorize('view', $kategori);

        return new KategoriResource($kategori);
    }

    public function update(Request $request, Kategori $kategori)
    {
        Gate::authorize('update', $kategori);

        $validated = $request->validate([
            'nama_kategori' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return new KategoriResource($kategori);
    }

    public function destroy(Kategori $kategori)
    {
        Gate::authorize('delete', $kategori);

        $kategori->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}