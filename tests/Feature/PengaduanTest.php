<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaduanTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_dapat_membuat_pengaduan_dengan_upload_foto(): void
    {
        // Inisialisasi fake storage secara statis
        Storage::fake('public');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('public');

        $warga = User::factory()->create(['role' => 'warga']);
        $kategori = Kategori::factory()->create();
        $file = UploadedFile::fake()->image('bukti_jalan.jpg');

        $response = $this->actingAs($warga, 'sanctum')
            ->postJson('/api/v1/pengaduan', [
                'judul' => 'Jalan Berlubang Parah',
                'deskripsi' => 'Mohon diperbaiki di Jl. Merdeka No 10',
                'kategori_id' => $kategori->id,
                'foto' => $file,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.judul', 'Jalan Berlubang Parah');

        // Bebas garis merah & bebas eror!
        $storage->assertExists('pengaduan/' . $file->hashName());
    }

    public function test_warga_dilarang_menghapus_pengaduan_rbac(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $pengaduan = Pengaduan::factory()->create();

        $response = $this->actingAs($warga, 'sanctum')
            ->deleteJson("/api/v1/pengaduan/{$pengaduan->id}");

        $response->assertStatus(403)
            ->assertJsonPath('status', 'error');
    }

    public function test_admin_berhasil_menghapus_pengaduan_dan_file_storage(): void
    {
        // Inisialisasi fake storage secara statis
        Storage::fake('public');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->image('foto_uji.jpg');
        $path = $file->store('pengaduan', 'public');

        $pengaduan = Pengaduan::factory()->create(['foto_path' => $path]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/pengaduan/{$pengaduan->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('pengaduans', ['id' => $pengaduan->id]);
        
        // Bebas garis merah & bebas eror!
        $storage->assertMissing($path); 
    }
}