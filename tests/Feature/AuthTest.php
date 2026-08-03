<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_baru_dapat_melakukan_registrasi_akun_warga(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ahmad Warga',
            'email' => 'ahmad@gmail.com',
            'password' => 'password123',
            'role' => 'warga',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email', 'role'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'ahmad@gmail.com']);
    }

    public function test_user_gagal_login_jika_password_salah(): void
    {
        User::factory()->create([
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'budi@gmail.com',
            'password' => 'password_salah',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_dapat_mengakses_profile_me_dengan_bearer_token(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.email', $user->email);
    }
}