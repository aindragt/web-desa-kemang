# Issue: Pembuatan Seeders Data Awal (User, Statistik, dan Berita)

## Deskripsi
Implementasi class database seeders untuk mengisi data awal ke dalam database E-Gov Desa Kemang. Ini mencakup pembuatan user default (1 Admin, 1 Operator), data statistik awal (penduduk, pekerjaan, agama, pendidikan) untuk chart animasi, dan artikel berita dummy.

## Rencana Pekerjaan

### 1. Inisialisasi Database Seeders
- **`UserSeeder`**:
  - Membuat 1 akun **Admin (Kepala Desa)**:
    - Nama: `H. Ahmad Faisal`
    - Username: `admin_kemang`
    - Password: `password123` (di-hash)
    - Role: `admin`
  - Membuat 1 akun **Operator (Staf Desa)**:
    - Nama: `Siti Rahayu`
    - Username: `operator_kemang`
    - Password: `password123` (di-hash)
    - Role: `operator`
- **`StatistikSeeder`**:
  - Memasukkan data awal kependudukan Desa Kemang:
    - Kategori **Pendidikan**: SD, SMP, SMA, Diploma/Sarjana.
    - Kategori **Pekerjaan**: Petani/Pekebun, Buruh Harian, Wiraswasta, PNS/TNI/Polri.
    - Kategori **Agama**: Islam, Kristen Protestan, Katolik, Budha.
- **`BeritaSeeder`**:
  - Membuat 3-4 artikel berita dummy lengkap dengan kategori (Pemerintahan, Kegiatan Desa, Pengumuman) dan status published agar langsung tampil di beranda utama.
- **`PengaturanSeeder`** (Tambahan):
  - Mengisi default key-value settings seperti `nama_kepala_desa` dan `nip_kepala_desa` untuk kebutuhan kop surat digital.

### 2. Integrasi ke `DatabaseSeeder`
- Memanggil `UserSeeder`, `StatistikSeeder`, `BeritaSeeder`, dan `PengaturanSeeder` di dalam method `run()` pada class `DatabaseSeeder` utama.
