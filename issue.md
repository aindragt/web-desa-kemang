# Issue: Pembuatan Model Eloquent Lengkap & Relasi Database

## Deskripsi
Implementasi ORM Eloquent Models di Laravel 13 untuk memetakan seluruh tabel database Desa Kemang. Setiap Model akan dilengkapi konfigurasi strict, relasi, cast tipe data, mutator/accessor, dan dynamic local scopes untuk mempermudah operasional data.

## Rencana Pekerjaan

### 1. Refaktor & Setup Model `User`
- Menghapus konfigurasi default email.
- Menambahkan `$fillable` (`nama`, `username`, `password`, `role`, `is_active`, `last_login_at`).
- Menambahkan `$hidden` (`password`, `remember_token`).
- Menambahkan `$casts` (`is_active` => boolean, `last_login_at` => datetime, `password` => hashed).
- Mendefinisikan relasi `hasMany` ke `PengajuanSurat` (`disetujuiSurat()`).
- Menambahkan custom scope `scopeActive` dan `scopeRole`.

### 2. Implementasi Model Berita & Foto (`Berita`, `FotoBerita`)
- **`Berita`**:
  - `$fillable` (`judul`, `slug`, `kategori`, `ringkasan`, `isi`, `penulis`, `is_published`, `published_at`).
  - `$casts` (`is_published` => boolean, `published_at` => datetime).
  - Relasi `hasMany` ke `FotoBerita` (`fotos()`).
  - Scope `scopePublished` dan `scopeByCategory`.
  - Mutator otomatis untuk auto-generating slug dari judul.
- **`FotoBerita`**:
  - `$fillable` (`berita_id`, `foto`, `keterangan`, `urutan`).
  - Relasi `belongsTo` ke `Berita` (`berita()`).
  - Scope `scopeOrdered` (urut berdasarkan kolom `urutan` secara ascending).
  - Accessor path absolut untuk file storage gambar.

### 3. Implementasi Model Statistik (`Statistik`)
- `$fillable` (`kategori`, `label`, `nilai`, `satuan`, `urutan`).
- `$casts` (`nilai` => float/decimal).
- Scope `scopeByCategory` dan `scopeOrdered`.

### 4. Implementasi Model Pengajuan Surat (`PengajuanSurat`)
- `$fillable` lengkap untuk biodata warga, data SKU (`nama_usaha`, `jenis_usaha`), status (`status`, `catatan_admin`), dan tracking approval (`diproses_at`, `disetujui_at`, `disetujui_oleh`, `selesai_at`).
- `$casts` (`tanggal_lahir` => date, `diproses_at`/`disetujui_at`/`selesai_at` => datetime).
- Relasi `belongsTo` ke `User` (`validator()`) via foreign key `disetujui_oleh`.
- Scopes: `scopePending`, `scopeInProcess`, `scopeApproved`, `scopeRejected`, `scopeFinished`, `scopeByReference`.

### 5. Fitur Penunjang (`PesanKontak`, `Pengaturan`)
- **`PesanKontak`**:
  - `$fillable` (`nama`, `kontak`, `pesan`, `is_read`).
  - `$casts` (`is_read` => boolean).
  - Scope `scopeUnread` dan `scopeRead`.
- **`Pengaturan`**:
  - `$fillable` (`kunci`, `nilai`).
  - Static helper untuk mengambil value setting berdasarkan key (`Pengaturan::getValue($key)`).
