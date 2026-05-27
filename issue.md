# Issue: Pembuatan Controller Dashboard Admin & Operator

## Deskripsi
Implementasi `AdminDashboardController` dan `OperatorDashboardController` untuk melayani panel dashboard internal masing-masing role. Controller ini akan mengumpulkan metrik statistik operasional desa, log audit, dan memuat status kelengkapan data administratif.

## Rencana Pekerjaan

### 1. AdminDashboardController
- Menerima request untuk dashboard utama Admin (Kepala Desa) terproteksi.
- Mengirimkan data metrik:
  - Jumlah pengajuan surat berstatus `menunggu_persetujuan` (menjadi antrean utama Kepala Desa).
  - Daftar surat yang menunggu keputusan Kepala Desa (disertai biodata singkat pemohon).
  - Riwayat validasi surat terbaru yang disetujui/ditolak oleh Kepala Desa tersebut (limit 5).
  - Status kelengkapan upload stempel digital desa (`cap_desa`) dan tanda tangan transparan (`ttd_kepala_desa`) di tabel pengaturan.

### 2. OperatorDashboardController
- Menerima request untuk dashboard utama Operator (Staf Desa) terproteksi.
- Mengirimkan data metrik ringkasan:
  - Jumlah total surat masuk berstatus `menunggu` dan `diproses`.
  - Jumlah total artikel berita terpublikasi.
  - Jumlah pesan masuk dari warga yang berstatus belum dibaca (`is_read = false`).
  - Daftar 5 pengajuan surat terbaru untuk segera direspons.
  - Daftar 5 berita terbaru yang diunggah staf.
  - Daftar 5 pesan kontak warga terbaru yang masuk.
