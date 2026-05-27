# Issue: Pembuatan Controller Layanan Surat (Operator & Admin)

## Deskripsi
Implementasi `OperatorLayananController` dan `AdminLayananController` untuk menangani alur lengkap pengesahan surat digital. Sistem ini dirancang aman dengan pembatasan hak akses yang jelas (check & balance) serta memicu penggabungan file TTD transparan dan stempel digital Kepala Desa secara otomatis.

## Rencana Pekerjaan

### 1. OperatorLayananController (Staf Operator)
- **`index()`**: Menampilkan list seluruh pengajuan surat warga (paginated) dengan filter jenis surat dan status tracking.
- **`show()`**: Menampilkan rincian data pengisian formulir pemohon.
- **`updateStatus()`**:
  - Memungkinkan operator memperbarui status surat (contoh: dari `menunggu` -> `diproses`, atau `diproses` -> `menunggu_persetujuan`).
  - **Keamanan:** Memblokir request jika operator mencoba mengubah status menjadi `disetujui` atau `selesai` (hak khusus Admin).
- **`cetak()`**:
  - Merender layout cetak surat resmi berbasis print CSS (`window.print()`).
  - **Keamanan:** Memvalidasi status surat. Tombol/fitur cetak hanya diizinkan aktif dan diakses jika status surat telah bernilai `"disetujui"`. Menyertakan path file TTD digital Kepala Desa dan Cap Desa dari database.

### 2. AdminLayananController (Kepala Desa)
- **`index()`**: Menampilkan antrean pengajuan surat berstatus `menunggu_persetujuan`.
- **`show()`**: Menampilkan detail isi pengajuan surat sebelum ditandatangani.
- **`setujui()`**:
  - **Keamanan:** Memastikan kelengkapan data file TTD digital dan Cap Desa transparan telah diunggah di database pengaturan. Jika belum, melempar error validasi.
  - Memperbarui status surat menjadi `"disetujui"`.
  - Menyimpan pencatatan audit approval (`disetujui_at` => waktu sekarang, `disetujui_oleh` => ID admin yang sedang login).
- **`tolak()`**:
  - Mewajibkan pengisian alasan penolakan pada kolom `catatan_admin`.
  - Mengubah status surat menjadi `"ditolak"`.
- **`kembalikan()`**:
  - Mengubah status surat kembali menjadi `"diproses"` agar operator dapat melakukan revisi data pemohon yang keliru.
