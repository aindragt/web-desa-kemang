<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminPengaturanController extends Controller
{
    /**
     * Display the settings form with current signature & stamp preview.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Pengaturan', [
            'pengaturan' => [
                'nama_kepala_desa' => Pengaturan::getValue('nama_kepala_desa', ''),
                'nip_kepala_desa' => Pengaturan::getValue('nip_kepala_desa', ''),
                'ttd_kepala_desa' => Pengaturan::getValue('ttd_kepala_desa') ? Storage::url(Pengaturan::getValue('ttd_kepala_desa')) : null,
                'cap_desa' => Pengaturan::getValue('cap_desa') ? Storage::url(Pengaturan::getValue('cap_desa')) : null,
            ]
        ]);
    }

    /**
     * Update settings (Kades name, NIP, upload signature & stamp).
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_kepala_desa' => ['required', 'string', 'max:255'],
            'nip_kepala_desa' => ['required', 'string', 'max:50'],
            'ttd_kepala_desa' => ['nullable', 'image', 'mimes:png', 'max:2048'],
            'cap_desa' => ['nullable', 'image', 'mimes:png', 'max:2048'],
        ], [
            'nama_kepala_desa.required' => 'Nama Kepala Desa wajib diisi.',
            'nip_kepala_desa.required' => 'NIP Kepala Desa wajib diisi.',
            'ttd_kepala_desa.image' => 'Tanda tangan harus berupa berkas gambar.',
            'ttd_kepala_desa.mimes' => 'Format tanda tangan harus PNG.',
            'ttd_kepala_desa.max' => 'Ukuran tanda tangan maksimal 2MB.',
            'cap_desa.image' => 'Cap desa harus berupa berkas gambar.',
            'cap_desa.mimes' => 'Format cap desa harus PNG.',
            'cap_desa.max' => 'Ukuran cap desa maksimal 2MB.',
        ]);

        // Simpan data teks
        Pengaturan::setValue('nama_kepala_desa', $request->input('nama_kepala_desa'));
        Pengaturan::setValue('nip_kepala_desa', $request->input('nip_kepala_desa'));

        // Handle upload TTD
        if ($request->hasFile('ttd_kepala_desa')) {
            // Hapus file lama jika ada
            $oldTtd = Pengaturan::getValue('ttd_kepala_desa');
            if ($oldTtd && Storage::disk('public')->exists($oldTtd)) {
                Storage::disk('public')->delete($oldTtd);
            }
            
            $path = $request->file('ttd_kepala_desa')->store('ttd', 'public');
            Pengaturan::setValue('ttd_kepala_desa', $path);
        }

        // Handle upload Cap
        if ($request->hasFile('cap_desa')) {
            // Hapus file lama jika ada
            $oldCap = Pengaturan::getValue('cap_desa');
            if ($oldCap && Storage::disk('public')->exists($oldCap)) {
                Storage::disk('public')->delete($oldCap);
            }

            $path = $request->file('cap_desa')->store('cap', 'public');
            Pengaturan::setValue('cap_desa', $path);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Delete signature or stamp file from storage.
     */
    public function hapusFile(string $kunci): RedirectResponse
    {
        if (!in_array($kunci, ['ttd_kepala_desa', 'cap_desa'])) {
            abort(400, 'Kunci pengaturan tidak valid.');
        }

        $path = Pengaturan::getValue($kunci);
        if ($path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Pengaturan::setValue($kunci, null);
        }

        return back()->with('success', 'Berkas berhasil dihapus.');
    }
}
