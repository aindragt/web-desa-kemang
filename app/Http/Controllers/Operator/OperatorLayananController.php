<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class OperatorLayananController extends Controller
{
    /**
     * Display a listing of the mail submissions.
     */
    public function index(Request $request): Response
    {
        $query = PengajuanSurat::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_referensi', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        $surat = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Operator/Surat/Index', [
            'surat' => $surat,
            'filters' => $request->only(['search', 'status', 'jenis_surat']),
        ]);
    }

    /**
     * Display the specified mail details.
     */
    public function show(int $id): Response
    {
        $surat = PengajuanSurat::with('validator')->findOrFail($id);

        return Inertia::render('Operator/Surat/Show', [
            'surat' => $surat,
        ]);
    }

    /**
     * Update status of the mail (Restriction: cannot set to disetujui or selesai).
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $surat = PengajuanSurat::findOrFail($id);

        $validated = $request->validate([
            'status' => [
                'required', 
                Rule::in(['menunggu', 'diproses', 'menunggu_persetujuan', 'ditolak'])
            ],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $updateData = [
            'status' => $validated['status'],
        ];

        // Audit log timestamp
        if ($validated['status'] === 'diproses' && !$surat->diproses_at) {
            $updateData['diproses_at'] = now();
        }

        if ($request->filled('catatan_admin')) {
            $updateData['catatan_admin'] = $validated['catatan_admin'];
        }

        $surat->update($updateData);

        return back()->with('success', 'Status pengajuan surat berhasil diperbarui.');
    }

    /**
     * Print layout rendering for approved mail.
     */
    public function cetak(int $id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        // Security check: Tombol cetak hanya aktif jika status sudah disetujui Kepala Desa
        if (!in_array($surat->status, ['disetujui', 'selesai'])) {
            abort(403, 'Surat belum disetujui oleh Kepala Desa. Dokumen tidak dapat dicetak.');
        }

        // Ambil data stempel & tanda tangan digital dari pengaturan
        $namaKades = Pengaturan::getValue('nama_kepala_desa', 'H. Ahmad Faisal');
        $nipKades = Pengaturan::getValue('nip_kepala_desa', '19700101 199903 1 001');
        $ttdKades = Pengaturan::getValue('ttd_kepala_desa');
        $capDesa = Pengaturan::getValue('cap_desa');

        // Update status ke 'selesai' setelah dicetak
        if ($surat->status === 'disetujui') {
            $surat->update([
                'status' => 'selesai',
                'selesai_at' => now(),
            ]);
        }

        // Render blade view khusus cetak dokumen fisik (window.print())
        return view('surat.cetak', [
            'surat' => $surat,
            'kades' => [
                'nama' => $namaKades,
                'nip' => $nipKades,
                'ttd_url' => $ttdKades ? asset('storage/' . $ttdKades) : null,
                'cap_url' => $capDesa ? asset('storage/' . $capDesa) : null,
            ]
        ]);
    }
}
