<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menambahkan opsi input Bearer Token di dokumentasi Scramble
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });

        // 1. Limit Umum untuk Endpoint API (misal: max 60 request / menit)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        // 2. Limit Ketat untuk Login/Auth (mencegah brute force: max 5x coba / menit)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                // 🔴 Kustomisasi Response Body jika melebihi batas (429)
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam beberapa menit.',
                    ], 429, $headers);
                });
        });

        // 3. Limit Pembuatan Pengaduan (mencegah spamming laporan: max 3 laporan / menit)
        RateLimiter::for('pengaduan-store', function (Request $request) {
            return Limit::perMinute(3)->by($request->user()?->id);
        });
    }
}