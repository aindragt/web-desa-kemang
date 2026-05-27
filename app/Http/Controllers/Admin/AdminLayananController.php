<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminLayananController extends Controller
{
    /**
     * Display listing of pending approvals for Admin.
     */
    public function index(Request $request): Response
    {
        $query = PengajuanSurat::waitingApproval();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_referensi', 'like', '%' . $request->search . '%');
            });
        }

        $surat = $query->orderBy('created_at', 'asc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/ValidasiSurat/Index', [
            'surat' => $surat,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show detail of pending mail before approval.
     */
    public function show(int $id): Response
    {
        $surat = PengajuanSurat::findOrFail($id);

        return Inertia::render('Admin/ValidasiSurat/Show', [
            'surat' => $surat,
        ]);
    }

    /**
     * Approve the mail (Security check: TTD & Cap must exist in Settings).
     */
    public function setujui(Request $request, int $id): RedirectResponse
    {
        $surat = PengajuanSurat::findOrFail($id);

        // 1. Validasi Keamanan: Cek kelengkapan file PNG cap desa & ttd Kades di database pengaturan
        $ttdKades = Pengaturan::getValue('ttd_kepala_desa');
        $capDesa = Pengaturan::getValue('cap_desa');

        if (empty($ttdKades) || empty($capDesa)) {
            return back()->withErrors([
                'approval' => 'Tanda tangan digital Kepala Desa atau Cap Desa belum diunggah. Silakan lengkapi di menu Pengaturan terlebih dahulu.'
            ]);
        }

        // 2. Set status disetujui & rekam audit log approval
        $surat->update([
            'status' => 'disetujui',
            'disetujui_at' => now(),
            'disetujui_oleh' => Auth::id(),
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return redirect()->route('admin.validasi.index')->with('success', 'Surat berhasil disetujui dan siap dicetak.');
    }

    /**
     * Reject the mail (Requirement: must provide rejection reason).
     */
    public function tolak(Request $request, int $id): RedirectResponse
    {
        $surat = PengajuanSurat::findOrFail($id);

        // Alasan penolakan wajib diisi
        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5'],
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
            'catatan_admin.min' => 'Alasan penolakan minimal 5 karakter.'
        ]);

        $surat->update([
            'status' => 'ditolak',
            'disetujui_at' => now(),
            'disetujui_oleh' => Auth::id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.validasi.index')->with('success', 'Surat berhasil ditolak.');
    }

    /**
     * Send back the mail to Operator for revision.
     */
    public function kembalikan(Request $request, int $id): RedirectResponse
    {
        $surat = PengajuanSurat::findOrFail($id);

        $surat->update([
            'status' => 'diproses',
            'catatan_admin' => $request->input('catatan_admin', 'Harap periksa kembali kesesuaian data berkas.'),
        ]);

        return redirect()->route('admin.validasi.index')->with('success', 'Surat berhasil dikembalikan ke staf Operator untuk direvisi.');
    }
}
