<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request to Admin Dashboard.
     */
    public function __invoke(Request $request): Response
    {
        $adminId = Auth::id();

        // 1. Jumlah surat menunggu persetujuan Kepala Desa
        $jumlahMenungguPersetujuan = PengajuanSurat::waitingApproval()->count();

        // 2. Daftar surat yang sedang menunggu persetujuan
        $suratMenungguPersetujuan = PengajuanSurat::waitingApproval()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($surat) {
                return [
                    'id' => $surat->id,
                    'nomor_referensi' => $surat->nomor_referensi,
                    'jenis_surat' => $surat->jenis_surat,
                    'nama_lengkap' => $surat->nama_lengkap,
                    'nik' => $surat->nik,
                    'keperluan' => $surat->keperluan,
                    'tanggal_pengajuan' => $surat->created_at->format('Y-m-d H:i'),
                ];
            });

        // 3. Riwayat validasi surat terbaru oleh Admin terkait (limit 5)
        $riwayatValidasi = PengajuanSurat::where('disetujui_oleh', $adminId)
            ->whereIn('status', ['disetujui', 'ditolak', 'selesai'])
            ->orderBy('disetujui_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($surat) {
                return [
                    'id' => $surat->id,
                    'nomor_referensi' => $surat->nomor_referensi,
                    'jenis_surat' => $surat->jenis_surat,
                    'nama_lengkap' => $surat->nama_lengkap,
                    'status' => $surat->status,
                    'catatan_admin' => $surat->catatan_admin,
                    'tanggal_aksi' => $surat->disetujui_at ? $surat->disetujui_at->format('Y-m-d H:i') : null,
                ];
            });

        // 4. Status TTD & Cap
        $hasTTD = !empty(Pengaturan::getValue('ttd_kepala_desa'));
        $hasCap = !empty(Pengaturan::getValue('cap_desa'));
        $namaKades = Pengaturan::getValue('nama_kepala_desa', 'Belum Diatur');
        $nipKades = Pengaturan::getValue('nip_kepala_desa', 'Belum Diatur');

        return Inertia::render('Admin/Dashboard', [
            'statistik' => [
                'menunggu_persetujuan' => $jumlahMenungguPersetujuan,
            ],
            'antreanSurat' => $suratMenungguPersetujuan,
            'riwayatValidasi' => $riwayatValidasi,
            'pengaturanStatus' => [
                'has_ttd' => $hasTTD,
                'has_cap' => $hasCap,
                'nama_kades' => $namaKades,
                'nip_kades' => $nipKades,
            ],
        ]);
    }
}
