# Issue: Pembuatan AdminOperatorController (Kelola Akun Operator)

## Deskripsi Masalah / Kebutuhan
Admin (Kepala Desa) memerlukan sebuah modul pengelolaan akun staf Operator desa untuk mendaftarkan operator baru, menonaktifkan akun sementara (jika berhalangan/cuti), mereset kata sandi jika lupa, serta menghapus akun secara permanen.

## Rencana Implementasi

1. **Membuat AdminOperatorController**:
   - **`index`**: Menampilkan list seluruh akun user yang memiliki role `operator`, diurutkan berdasarkan tanggal pendaftaran terbaru.
   - **`store`**: Membuat akun operator baru. Wajib mengisi `nama`, `username` (wajib unik di tabel `users`), dan `password` (enkripsi otomatis).
   - **`toggleActive`**: Mengaktifkan atau menonaktifkan status akun operator (`is_active` status toggle).
   - **`resetPassword`**: Reset password akun operator ke password default atau inputan baru.
   - **`destroy`**: Menghapus akun operator secara permanen.

2. **Routing di `routes/web.php`**:
   - Menghubungkan route `/admin/operator` ke `AdminOperatorController` untuk index, store, toggle, reset-password, dan destroy.

## Hasil yang Diharapkan
- Admin memiliki kendali penuh atas hak akses staf operator desa.
- Keamanan terjamin karena adanya validasi username unik serta kemudahan reset sandi langsung oleh Kepala Desa.
