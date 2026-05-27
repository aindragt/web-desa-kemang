# Issue: Implementasi Sistem Autentikasi 2 Role (Session-Based Auth)

## Deskripsi
Implementasi modul autentikasi lengkap dengan 1 halaman login bersama untuk 2 role (`admin` dan `operator`), controller otorisasi session, redirect otomatis pasca-login, serta proteksi route group menggunakan custom middleware.

## Rencana Pekerjaan

### 1. Pembuatan AuthController
- **`AuthController`**:
  - `showLogin()`: Merender halaman login menggunakan Inertia.js.
  - `login()`: Validasi kredensial `username` & `password`. Cek status keaktifan user (`is_active`). Memulai session, dan melakukan redirect otomatis sesuai role (admin -> `/admin`, operator -> `/operator`).
  - `logout()`: Menghancurkan session user dan redirect ke `/login`.

### 2. Implementasi Middleware & Proteksi Route
- **`EnsureRole` (Custom Middleware)**:
  - Cek apakah user sudah login. Jika belum, redirect ke `/login`.
  - Membatasi akses route berdasarkan parameter role (contoh: `role:admin` atau `role:operator`).
  - Jika role tidak sesuai, mengembalikan response 403 Forbidden.

### 3. Pembuatan Halaman Login UI
- **`resources/js/Pages/Auth/Login.vue`**:
  - Form login responsif dengan framework Vue 3 + Inertia `useForm`.
  - Integrasi style premium Tailwind CSS 4 dengan warna aksen songket Melayu Riau (Hijau Hutan, Emas, Krem, Coklat).

### 4. Konfigurasi Routes (`routes/web.php`)
- Route `/login` (GET/POST) dan `/logout` (POST).
- Group `/admin` dengan middleware `EnsureRole:admin`.
- Group `/operator` dengan middleware `EnsureRole:operator`.
- Halaman placeholder dashboard admin & operator untuk memverifikasi fungsionalitas.
