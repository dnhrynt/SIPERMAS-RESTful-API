<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengaduanController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // 🔴 PUBLIC ROUTES (Bisa diakses tanpa token)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // 🟢 PROTECTED ROUTES (Harus menyertakan "Bearer <token>" di Header)
    Route::middleware(['auth:sanctum','throttle:api'])->group(function () {
        
        // Auth Actions
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // RESTful Master Data Kategori
        Route::apiResource('kategori', KategoriController::class);

        // RESTful Transaksi Pengaduan
        // 1. Route Khusus Store Pengaduan (Throttle Ketat)
        Route::post('/pengaduan', [PengaduanController::class, 'store'])
            ->middleware('throttle:pengaduan-store');

        // 2. Resource Pengaduan sisanya (index, show, update, destroy)
        Route::apiResource('pengaduan', PengaduanController::class)->except(['store']);
        });

});
