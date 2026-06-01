# Issue: Pembuatan OperatorPesanController (Kelola & Baca Pesan Kontak)

## Deskripsi Masalah / Kebutuhan
Operator (staf desa) memerlukan modul kelola pesan masuk dari warga untuk melihat daftar aspirasi/kontak, membaca detail pesan, serta menghapus pesan yang tidak relevan atau bersifat spam. Sistem harus secara otomatis menandai pesan sebagai terbaca (`is_read = true`) ketika operator membuka rincian pesan tersebut.

## Rencana Implementasi

1. **Membuat OperatorPesanController**:
   - **`index`**: Menampilkan semua daftar pesan kontak masuk (paginated) diurutkan berdasarkan tanggal terbaru. Dilengkapi dengan filter pencarian dan filter status pesan (`sudah dibaca`/`belum dibaca`).
   - **`show`**: Menampilkan rincian pesan. Ketika dibuka, sistem secara otomatis merubah status pesan `is_read = true` jika sebelumnya berstatus belum dibaca.
   - **`destroy`**: Menghapus pesan secara permanen dari basis data.

2. **Routing di `routes/web.php`**:
   - Menghubungkan route `/operator/pesan` ke `OperatorPesanController` untuk index, show, dan destroy.

## Hasil yang Diharapkan
- Pesan dari masyarakat di halaman kontak publik masuk ke dashboard Operator dan terkelola secara efisien.
- Status pesan terbaca terkelola secara otomatis sehingga memberikan akurasi data statistik pada dashboard.
