# Issue: Pembuatan Controller untuk Website Publik (Home, Berita, & Layanan)

## Deskripsi
Implementasi controller utama di bawah namespace `Public` untuk melayani seluruh halaman frontend publik (tanpa login). Controller ini bertugas mengambil data dari database (statistik, berita, pengaturan) dan merendernya ke view Vue via Inertia.js.

## Rencana Pekerjaan

### 1. Inisialisasi Controllers
- **`HomeController`**:
  - `index()`: Mengambil berita terbaru (limit 3), data statistik ringkas (total penduduk, pekerjaan, dll), dan pengaturan kop desa untuk ditampilkan di halaman Beranda.
  - `profil()`: Merender halaman profil desa (sejarah, visi misi, wilayah, aparatur).
  - `statistik()`: Mengambil seluruh data dari model `Statistik` untuk dikirimkan ke visualisasi chart animasi.
  - `kontak()`: Merender halaman kontak desa beserta data kantor pelayanan.
  - `kirimPesan()`: Validasi data form kontak warga dan menyimpannya ke tabel `pesan_kontak`.

- **`BeritaController`**:
  - `index()`: Mengambil list berita terbit (`is_published = true`) dengan pagination, filter kategori, dan pencarian judul.
  - `show()`: Mengambil detail berita beserta relasi multiple `fotos` pendukung.

- **`LayananController`**:
  - `index()`: Merender list menu pelayanan 4 jenis surat.
  - `form()`: Menampilkan formulir input spesifik jenis surat (SKD, SKU, SKM, SPK).
  - `submit()`: Validasi form, generate nomor referensi otomatis (format: PREFIX-TAHUN-URUTAN), dan simpan ke database dengan status awal `menunggu`.
  - `cekStatus()`: Mencari status surat berdasarkan nomor referensi dan mengirimkan data timeline status ke Vue.
  - `downloadUlang()`: Fitur tambahan jika warga kehilangan tracking atau perlu mengunduh ulang informasi referensi pengajuan.
