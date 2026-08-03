# 📢 SIPERMAS API - Sistem Informasi Pengaduan Masyarakat

**SIPERMAS API** adalah *backend RESTful API* modern dan tangguh yang dibangun menggunakan **Laravel 12**. Sistem ini dirancang khusus untuk menangani alur pelaporan dan pengaduan masyarakat secara terstruktur, aman, dan efisien.

Diadaptasi dengan standar industri, proyek ini menerapkan arsitektur *clean code*, perlindungan data ketat via *Role-Based Access Control* (RBAC), transformasi data konsisten, serta mitigasi serangan lalu lintas (*rate limiting*).

### ✨ Fitur & Keunggulan Utama

- 🛡️ **Role-Based Access Control (RBAC)**: Otorisasi tingkat lanjut menggunakan Laravel `Gate` & `Policy` untuk memisahkan hak akses antara Masyarakat, Petugas, dan Admin.
- ⚡ **Standardized Output Data**: Menggunakan `JsonResource` (`PengaduanResource`, `KategoriResource`) untuk menjamin konsistensi format JSON dan mencegah kebocoran data sensitif.
- 🔒 **Sistem Keamanan & Throttling**: Proteksi *Mass Assignment* (Model Strict Mode) dan kustomisasi *Rate Limiting* untuk mencegah serangan brute-force / spamming.
- 🛠️ **Global Exception Handling**: Penanganan error terpusat (401, 403, 404, 500) yang merespon dengan format JSON terstruktur untuk kenyamanan integrasi *frontend*.
- 📖 **Auto-Generated API Docs**: Dokumentasi API interaktif yang tergenerasi otomatis menggunakan **Laravel Scramble**.
- 🌐 **CORS Ready**: Siap diintegrasikan dengan aplikasi *frontend* berbasis SPA (React, Vue, Next.js) maupun *mobile app* (Flutter).

### Installation Guide
git clone <https://github.com/dnhrynt/SIPERMAS-RESTful-API>
cd sipermas
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
