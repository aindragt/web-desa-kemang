# Issue: Pembuatan Controller Berita internal Operator (Multiple Foto Support)

## Deskripsi
Implementasi `OperatorBeritaController` untuk memfasilitasi Staf Operator dalam mengelola portal informasi desa (CRUD lengkap). Controller ini akan dirancang tangguh untuk menangani file upload batch (multiple photos), penghapusan per-foto saat edit, sinkronisasi relasi database di tabel `foto_berita`, serta toggle rilis publikasi berita.

## Rencana Pekerjaan

### 1. Inisialisasi OperatorBeritaController
- **`index()`**: Menampilkan list seluruh berita yang dibuat (paginated), pencarian judul, dan kategori filter.
- **`create()`**: Merender halaman formulir input berita baru.
- **`store()`**:
  - Validasi ketat (judul, kategori, ringkasan, isi, multiple photos format JPG/PNG/WEBP maks 2MB).
  - Penyimpanan artikel berita (slug digenerate otomatis).
  - Upload dan simpan batch gambar ke disk `public/berita/` serta catat relasi di tabel `foto_berita`.
- **`edit()`**: Mengambil data artikel berita beserta list seluruh relasi `fotos` yang terikat.
- **`update()`**:
  - Validasi data artikel baru.
  - Penambahan foto baru (jika ada) ke database.
  - (Opsional/Pendukung): Mendukung payload handling multipart pada update state.
- **`destroy()`**: Menghapus data berita, otomatis menghapus seluruh relasi file fisik foto di storage (menggunakan helper `Storage::delete`) dan data di DB (`onDelete('cascade')`).
- **`togglePublish()`**: Aksi cepat mengubah status `is_published` berita (draft / publish) beserta pencatatan timestamp rilis.
- **`hapusFoto()`**: Handler endpoint untuk menghapus foto tertentu secara asinkron saat proses edit berita berlangsung tanpa perlu menghapus keseluruhan draf artikel.
