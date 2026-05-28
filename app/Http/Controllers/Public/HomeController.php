<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Statistik;
use App\Models\PesanKontak;
use App\Models\Pengaturan;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): Response
    {
        // Berita terbaru (limit 3)
        $beritaTerbaru = Berita::published()
            ->with(['fotos' => function ($query) {
                $query->ordered();
            }])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Data statistik ringkas
        $totalPenduduk = Statistik::where('kategori', 'agama')->sum('nilai'); // Asumsi total nilai agama = total penduduk
        $totalPekerja = Statistik::where('kategori', 'pekerjaan')->sum('nilai');
        
        return Inertia::render('Public/Beranda', [
            'beritaTerbaru' => $beritaTerbaru,
            'statistikRingkas' => [
                'total_penduduk' => $totalPenduduk,
                'total_pekerja' => $totalPekerja,
                'luas_wilayah' => '12.45', // Statis dari data desa
                'rt_rw' => '12/4',
            ]
        ]);
    }

    /**
     * Display the village profile.
     */
    public function profil(): Response
    {
        // Mengambil data aparatur dari pengaturan / statis
        $kepalaDesa = Pengaturan::getValue('nama_kepala_desa', 'H. Ahmad Faisal');
        
        return Inertia::render('Public/Profil', [
            'aparatur' => [
                ['nama' => $kepalaDesa, 'jabatan' => 'Kepala Desa', 'foto' => null],
                ['nama' => 'Siti Rahayu', 'jabatan' => 'Sekretaris Desa', 'foto' => null],
                ['nama' => 'Bambang Utomo', 'jabatan' => 'Kaur Keuangan', 'foto' => null],
                ['nama' => 'Dewi Lestari', 'jabatan' => 'Kaur Pembangunan', 'foto' => null],
            ]
        ]);
    }

    /**
     * Display demographics statistics.
     */
    public function statistik(): Response
    {
        $statistik = Statistik::ordered()->get()->groupBy('kategori');

        return Inertia::render('Public/Statistik', [
            'statistikData' => $statistik
        ]);
    }

    /**
     * Display the contact form and information.
     */
    public function kontak(): Response
    {
        return Inertia::render('Public/Kontak', [
            'kontakInfo' => [
                'alamat' => 'Jl. Lintas Timur No.45, Desa Kemang, Kec. Pangkalan Kuras, Kab. Pelalawan, Riau',
                'email' => 'info@desakemang.go.id',
                'no_hp' => '0812-3456-7890',
                'jam_pelayanan' => 'Senin - Jumat, 08:00 - 15:30 WIB'
            ]
        ]);
    }

    /**
     * Store contact message from public.
     */
    public function kirimPesan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:255'], // Email / No HP
            'pesan' => ['required', 'string', 'min:10'],
        ]);

        PesanKontak::create($validated);

        return back()->with('success', 'Terima kasih, pesan Anda berhasil dikirim ke perangkat desa.');
    }
}
