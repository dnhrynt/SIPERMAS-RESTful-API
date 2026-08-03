# 📢 SIPERMAS API - Public Complaint Information System

**SIPERMAS API** is a modern and robust **RESTful API backend** built with **Laravel 12**. This system is specifically designed to handle public reporting and complaint workflows in a structured, secure, and efficient manner.

Engineered following industry best practices, the project features a **clean code** architecture, strict data protection via **Role-Based Access Control** (RBAC), consistent data transformation, and traffic management (**rate limiting**).

### ✨ Key Features & Highlights

- 🛡️ **Role-Based Access Control (RBAC)**: Advanced authorization using Laravel Gate & Policy to manage distinct access levels for Citizens, Officers, and Admins.
- ⚡ **Standardized Output Data**: Leverages JsonResource (PengaduanResource, KategoriResource) to guarantee consistent JSON response formats and protect sensitive attributes.
- 🔒 **Security & Throttling**: Mass Assignment protection via Model Strict Mode alongside custom Rate Limiting to prevent brute-force and spam attacks.
- 🛠️ **Global Exception Handling**: Centralized error handling (401, 403, 404) that returns structured JSON responses for seamless frontend integration.
- 📖 **Auto-Generated API Docs**: Interactive API documentation generated automatically with Laravel Scramble.
- 🌐 **CORS Ready**: Seamlessly integrates with SPA frontends (React, Vue, Next.js) and mobile applications (Flutter).

### Installation Guide
- git clone <https://github.com/dnhrynt/SIPERMAS-RESTful-API>
- cd sipermas
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
- php artisan serve
