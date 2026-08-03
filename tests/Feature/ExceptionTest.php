<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExceptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mengembalikan_respon_json_404_kustom_jika_id_tidak_ada(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/pengaduan/99999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Resource atau data yang dicari tidak ditemukan.',
            ]);
    }

    public function test_mengembalikan_respon_json_401_jika_akses_tanpa_token(): void
    {
        $response = $this->getJson('/api/v1/pengaduan');

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Sesi berakhir atau token tidak valid. Silakan login kembali.',
            ]);
    }
}