<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengaduanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'foto' => $this->foto_path ? asset('storage/' . $this->foto_path) : null,
            'status' => $this->status,
            'pelapor' => [
                'id' => $this->user->id,
                'nama' => $this->user->name,
            ],
            'kategori' => $this->kategori->nama_kategori,
            'tanggal_lapor' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
