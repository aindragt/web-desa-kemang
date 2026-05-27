# Issue: Pemetaan Lengkap Routing Web E-Gov Desa Kemang

## Deskripsi
Implementasi seluruh web routing di `routes/web.php` untuk mendukung seluruh fitur E-Government Desa Kemang sesuai dengan daftar spesifikasi fungsionalitas PRD. Ini mencakup route publik untuk warga (tanpa login), route admin (Kepala Desa) terproteksi, dan route operator (Staf Desa) terproteksi.

## Rencana Pekerjaan

### 1. Route Publik (Warga - Tanpa Login)
- **Beranda**: `/` (GET)
- **Profil Desa**: `/profil` (GET)
- **Statistik Desa**: `/statistik` (GET)
- **Berita Desa**:
  - List berita: `/berita` (GET)
  - Detail berita: `/berita/{slug}` (GET)
- **Layanan Surat Online**:
  - Landing / Form Pengajuan: `/layanan-surat` (GET)
  - Submit Pengajuan: `/layanan-surat` (POST)
  - Cek Status Surat: `/layanan-surat/status` (GET)
- **Kontak**:
  - Info Kontak: `/kontak` (GET)
  - Kirim Pesan: `/kontak` (POST)

### 2. Route Admin (Kepala Desa)
- Prefix: `/admin`, Middleware: `['auth', 'role:admin']`
- **Dashboard**: `/admin` (GET)
- **Validasi Surat**:
  - List surat masuk: `/admin/validasi` (GET)
  - Detail validasi: `/admin/validasi/{id}` (GET)
  - Aksi validasi (Setujui/Tolak/Kembalikan): `/admin/validasi/{id}` (PUT/PATCH)
- **Pengaturan Kop & TTD**: `/admin/pengaturan` (GET/POST)
- **Kelola Statistik**: `/admin/statistik` (Resource Route - CRUD)
- **Kelola Akun Operator**: `/admin/operator` (Resource Route - CRUD + Toggle status keaktifan)

### 3. Route Operator (Staf Desa)
- Prefix: `/operator`, Middleware: `['auth', 'role:operator']`
- **Dashboard**: `/operator` (GET)
- **Kelola Berita**: `/operator/berita` (Resource Route - CRUD + kelola multiple foto)
- **Kelola Statistik**: `/operator/statistik` (Resource Route - CRUD)
- **Proses Surat**:
  - List pengajuan & tracking: `/operator/surat` (GET)
  - Detail pengajuan: `/operator/surat/{id}` (GET)
  - Update status proses: `/operator/surat/{id}/status` (PATCH)
  - Cetak surat (Print layout): `/operator/surat/{id}/cetak` (GET - Hanya aktif jika disetujui)
- **Kelola Pesan Kontak**:
  - List & baca pesan: `/operator/pesan` (GET)
  - Detail pesan: `/operator/pesan/{id}` (GET)
  - Hapus / update read status: `/operator/pesan/{id}` (DELETE/PATCH)
