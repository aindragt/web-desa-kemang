# Issue: Pembuatan Controller Pengelolaan Statistik Desa (Admin & Operator)

## Deskripsi Masalah / Kebutuhan
Sistem E-Demografi & Statistik Desa Kemang membutuhkan modul manajemen statistik untuk mempermudah pembaruan data kependudukan (Pendidikan, Pekerjaan, Agama). Pembaruan data ini harus efisien sehingga mendukung penambahan data tunggal serta pengkinian massal (*bulk update*) secara bersamaan.

## Rencana Implementasi

1. **Membuat Shared/Base Controller**:
   - Kita bisa membuat satu controller yang dapat digunakan bersama baik untuk Admin maupun Operator (misal: `App\Http\Controllers\Admin\AdminStatistikController` atau di namespace general yang dialias ke route masing-masing role). Namun agar modular dan terstruktur sesuai role-based routing sebelumnya, kita akan membuat controller tunggal yaitu `App\Http\Controllers\Admin\AdminStatistikController` untuk Admin dan memetakan route Operator ke sana (atau menggunakan namespace `App\Http\Controllers\General\StatistikController` agar bersih). 
   - Mari kita buat `App\Http\Controllers\Admin\AdminStatistikController` sebagai modul utama yang meng-handle kelola data statistik (baik untuk admin maupun operator, karena secara data & logic CRUD statis ini identik untuk kedua role).

2. **Fungsionalitas Controller**:
   - **`index`**: Mengambil seluruh data statistik, dikelompokkan berdasarkan field `kategori` (`pendidikan`, `pekerjaan`, `agama`), diurutkan berdasarkan `urutan` secara menanjak (ordered scope), lalu dirender via `Inertia::render()`.
   - **`store`**: Menambahkan label/statistik tunggal baru untuk kategori tertentu dengan validasi lengkap (`kategori`, `label`, `nilai`, `satuan`, `urutan`).
   - **`updateSemua` (Bulk Update)**: Menerima array berisikan data statistik untuk melakukan pembaruan nilai & urutan banyak data sekaligus secara cepat dan efisien.
   - **`destroy`**: Menghapus data statistik tunggal berdasarkan ID.

3. **Routing (`routes/web.php`)**:
   - Menghubungkan route kelola statistik baik pada prefix `admin/statistik` maupun `operator/statistik` ke controller baru tersebut.

## Hasil yang Diharapkan
- Pengelolaan statistik desa (Pendidikan, Pekerjaan, Agama) dapat diakses dengan cepat.
- Operator dan Admin dapat memperbarui semua data dalam kategori secara massal (*bulk*) sekali klik.
