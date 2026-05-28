# Issue: Fitur Pengaturan TTD Kepala Desa dan Cap Desa (Admin)

## Deskripsi Masalah / Kebutuhan
Admin (Kepala Desa) memerlukan sebuah modul Pengaturan untuk mengelola data identitas pejabat penandatangan dokumen dan elemen tanda tangan/stempel desa yang valid. Modul ini penting untuk memastikan validasi kebenaran dokumen yang dicetak oleh Operator.

## Rencana Implementasi

1. **Model Pengaturan**
   - Menambahkan helper static method `setValue(string $kunci, ?string $nilai)` untuk memudahkan penyimpanan data pengaturan secara dinamis dengan metode key-value.

2. **AdminPengaturanController**
   - **`index`**: Mengambil nilai data pengaturan kades (`nama_kepala_desa`, `nip_kepala_desa`, `ttd_kepala_desa`, `cap_desa`) dengan path URL storage yang siap dipakai frontend Inertia.js.
   - **`update`**: Validasi input (nama, NIP, upload berkas PNG max 2MB). Menyimpan data identitas kades serta memproses berkas TTD & Cap yang diunggah ke storage `public`. Secara otomatis menghapus berkas lama jika diganti untuk efisiensi ruang server.
   - **`hapusFile`**: Menghapus tanda tangan digital atau cap desa secara fisik dari storage dan memperbarui databasenya kembali menjadi `null`.

3. **Routing di `routes/web.php`**
   - Menghubungkan route `/admin/pengaturan` ke `AdminPengaturanController`.
   - Menyediakan route GET (`index`), POST (`update`), dan DELETE (`hapusFile`).

## Hasil yang Diharapkan
- Admin memiliki kendali penuh atas identitas Kepala Desa, TTD digital, serta stempel resmi desa.
- Data ini tersimpan dengan aman pada database dan folder storage lokal secara rapi.
