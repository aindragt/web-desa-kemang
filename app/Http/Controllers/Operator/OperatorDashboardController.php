<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\Berita;
use App\Models\PesanKontak;
use Inertia\Inertia;
use Inertia\Response;

class OperatorDashboardController extends Controller
{
    /**
     * Handle the incoming request to Operator Dashboard.
     */
    public function __invoke(Request $request): Response
    {
        // 1. Metrik Ringkasan
        $suratMenunggu = PengajuanSurat::pending()->count();
        $suratDiproses = PengajuanSurat::inProcess()->count();
        $beritaTerbit = Berita::published()->count();
        $pesanBelumDibaca = PesanKontak::unread()->count();

        // 2. Daftar 5 pengajuan surat terbaru
        $pengajuanTerbaru = PengajuanSurat::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($surat) {
                return [
                    'id' => $surat->id,
                    'nomor_referensi' => $surat->nomor_referensi,
                    'jenis_surat' => $surat->jenis_surat,
                    'nama_lengkap' => $surat->nama_lengkap,
                    'status' => $surat->status,
                    'tanggal_pengajuan' => $surat->created_at->format('Y-m-d H:i'),
                ];
            });

        // 3. Daftar 5 berita terbaru yang diunggah
        $beritaTerbaru = Berita::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($artikel) {
                return [
                    'id' => $artikel->id,
                    'judul' => $artikel->judul,
                    'kategori' => $artikel->kategori,
                    'is_published' => $artikel->is_published,
                    'tanggal_dibuat' => $artikel->created_at->format('Y-m-d H:i'),
                ];
            });

        // 4. Daftar 5 pesan kontak terbaru
        $pesanTerbaru = PesanKontak::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($pesan) {
                return [
                    'id' => $pesan->id,
                    'nama' => $pesan->nama,
                    'kontak' => $pesan->kontak,
                    'pesan_snippet' => \Illuminate\Support\Str::limit($pesan->pesan, 50),
                    'is_read' => $pesan->is_read,
                    'tanggal_masuk' => $pesan->created_at->format('Y-m-d H:i'),
                ];
            });

        return Inertia::render('Operator/Dashboard', [
            'ringkasan' => [
                'surat_menunggu' => $suratMenunggu,
                'surat_diproses' => $suratDiproses,
                'berita_terbit' => $beritaTerbit,
                'pesan_belum_dibaca' => $pesanBelumDibaca,
            ],
            'suratTerbaru' => $pengajuanTerbaru,
            'beritaTerbaru' => $beritaTerbaru,
            'pesanTerbaru' => $pesanTerbaru,
        ]);
    }
}
