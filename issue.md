# Issue: Setup Awal Project - Laravel 13 + Vue 3 + Inertia.js + Tailwind CSS 4 + Vite

## Deskripsi
Melakukan inisialisasi dan konfigurasi awal project E-Government Desa Kemang dengan stack modern:
- Backend: Laravel 13 (PHP 8.3+)
- Frontend: Vue 3 + Inertia.js (SPA)
- Styling: Tailwind CSS 4
- Bundler: Vite

## Pekerjaan Yang Telah Dilakukan

### 1. Inisialisasi Project Laravel
- Mengunduh dan memasang kerangka kerja Laravel 11/13.
- Mengonfigurasi `bootstrap/app.php` dengan menambahkan global middleware `HandleInertiaRequests` pada stack routing `web`.
- Menghapus welcome default blade view (`welcome.blade.php`).

### 2. Instalasi Packages & Dependencies
- **Composer (Server-side):**
  - `inertiajs/inertia-laravel` (v3.1.0)
- **NPM (Client-side):**
  - `@inertiajs/vue3`
  - `vue`
  - `@vitejs/plugin-vue`
  - `@tailwindcss/vite`
  - `tailwindcss` (v4)

### 3. Konfigurasi Client & Server-side
- **Vite (`vite.config.js`):** Mengonfigurasi engine bundler agar menggunakan plugin `@tailwindcss/vite` dan `@vitejs/plugin-vue` serta mengatur alias `@` ke `/resources/js`.
- **Inertia Layout (`resources/views/app.blade.php`):** Membuat root layout HTML5, memuat font (Playfair Display, Lora, Inter), dan memanggil directive `@inertia` dan `@inertiaHead`.
- **Inertia Client-side Setup (`resources/js/app.js`):** Menginisialisasi aplikasi Vue 3 dengan `createInertiaApp` dan dynamic resolver untuk folder `Pages`.
- **Tailwind CSS 4 (`resources/css/app.css`):** Menggunakan sintaksis `@import "tailwindcss"` baru serta mendefinisikan `@theme` palette warna sesuai PRD (Hijau Hutan, Emas, Krem, Coklat Tua, Putih Gading).

### 4. Struktur Folder & Halaman Uji Coba
- Membuat direktori frontend di bawah `resources/js/`:
  - `Pages/Public`, `Pages/Admin`, `Pages/Operator`, `Pages/Auth`
  - `Components/UI`, `Components/Public`, `Components/Dashboard`
  - `Layouts`
  - `Composables`
- Membuat halaman pengujian [Welcome.vue](file:///c:/laragon/www/web-kemang/resources/js/Pages/Welcome.vue) untuk memverifikasi fungsionalitas rendering Vue, Inertia, dan Tailwind CSS 4 theme settings.
- Memperbarui file `routes/web.php` untuk merender halaman `Welcome` dengan custom message props.
- Memvalidasi kompilasi aset production dengan `npm run build` yang sukses tanpa kendala.
