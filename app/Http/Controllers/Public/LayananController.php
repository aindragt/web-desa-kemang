<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class LayananController extends Controller
{
    /**
     * Display service information.
     */
    public function index(): Response
    {
        return Inertia::render('Public/LayananSurat/Index');
    }

    /**
     * Display service submission form.
     */
    public function form(string $jenis): Response
    {
        $jenisValid = ['domisili', 'usaha', 'tidak_mampu', 'pengantar'];
        
        if (!in_array($jenis, $jenisValid)) {
            abort(404);
        }

        return Inertia::render('Public/LayananSurat/Form', [
            'jenisSurat' => $jenis,
            'jenisSuratLabel' => $this->getJenisSuratLabel($jenis)
        ]);
    }

    /**
     * Store service submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $rules = [
            'jenis_surat' => ['required', Rule::in(['domisili', 'usaha', 'tidak_mampu', 'pengantar'])],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'kontak' => ['required', 'string', 'max:20'],
            'keperluan' => ['required', 'string'],
        ];

        // Conditional validation jika jenis surat = usaha (SKU)
        if ($request->jenis_surat === 'usaha') {
            $rules['nama_usaha'] = ['required', 'string', 'max:255'];
            $rules['jenis_usaha'] = ['required', 'string', 'max:255'];
        } else {
            $rules['nama_usaha'] = ['nullable'];
            $rules['jenis_usaha'] = ['nullable'];
        }

        $validated = $request->validate($rules, [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus berukuran 16 karakter.',
            'kontak.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'keperluan.required' => 'Maksud keperluan surat wajib diisi.',
            'nama_usaha.required' => 'Nama usaha wajib diisi untuk jenis surat usaha.',
            'jenis_usaha.required' => 'Jenis usaha wajib diisi untuk jenis surat usaha.',
        ]);

        // Default empty required fields for migration compat
        $validated['tempat_lahir'] = 'Pelalawan';
        $validated['tanggal_lahir'] = now()->toDateString();
        $validated['jenis_kelamin'] = 'laki-laki';
        $validated['agama'] = 'Islam';
        $validated['pekerjaan'] = 'Wiraswasta';
        $validated['alamat'] = 'Desa Kemang';
        $validated['no_hp'] = $validated['kontak'];

        // Generate nomor referensi otomatis
        $validated['nomor_referensi'] = $this->generateNomorReferensi($validated['jenis_surat']);
        $validated['status'] = 'menunggu';

        $pengajuan = PengajuanSurat::create($validated);

        return redirect()->route('layanan-surat.status', ['ref' => $pengajuan->nomor_referensi])
            ->with('success', 'Pengajuan surat berhasil dikirim! Catat nomor referensi Anda.');
    }

    /**
     * Tracking reference status checking.
     */
    public function cekStatus(Request $request): Response
    {
        $pengajuan = null;
        $ref = $request->input('ref');

        if ($ref) {
            $pengajuan = PengajuanSurat::byReference($ref)
                ->first();
        }

        return Inertia::render('Public/LayananSurat/CekStatus', [
            'pengajuan' => $pengajuan ? [
                'id' => $pengajuan->id,
                'nomor_referensi' => $pengajuan->nomor_referensi,
                'jenis_surat' => $pengajuan->jenis_surat,
                'jenis_surat_label' => $this->getJenisSuratLabel($pengajuan->jenis_surat),
                'nama_lengkap' => $pengajuan->nama_lengkap,
                'status' => $pengajuan->status,
                'catatan_admin' => $pengajuan->catatan_admin,
                'created_at' => $pengajuan->created_at->format('Y-m-d H:i:s'),
                'diproses_at' => $pengajuan->diproses_at ? $pengajuan->diproses_at->format('Y-m-d H:i:s') : null,
                'disetujui_at' => $pengajuan->disetujui_at ? $pengajuan->disetujui_at->format('Y-m-d H:i:s') : null,
                'selesai_at' => $pengajuan->selesai_at ? $pengajuan->selesai_at->format('Y-m-d H:i:s') : null,
            ] : null,
            'searchedRef' => $ref,
        ]);
    }

    /**
     * Download / print reference slip.
     */
    public function downloadUlang(string $ref): Response
    {
        $pengajuan = PengajuanSurat::byReference($ref)->firstOrFail();
        
        return Inertia::render('Public/LayananSurat/SlipReferensi', [
            'pengajuan' => $pengajuan,
            'jenisSuratLabel' => $this->getJenisSuratLabel($pengajuan->jenis_surat)
        ]);
    }

    /**
     * Helper to map letter type to readable labels.
     */
    private function getJenisSuratLabel(string $jenis): string
    {
        $map = [
            'domisili' => 'Surat Keterangan Domisili (SKD)',
            'usaha' => 'Surat Keterangan Usaha (SKU)',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu (SKM)',
            'pengantar' => 'Surat Pengantar KTP / KK (SPK)',
        ];

        return $map[$jenis] ?? '';
    }

    /**
     * Core helper logic to generate unique dynamic reference numbers.
     */
    private function generateNomorReferensi(string $jenisSurat): string
    {
        $prefixMap = [
            'domisili' => 'SKD',
            'usaha' => 'SKU',
            'tidak_mampu' => 'SKM',
            'pengantar' => 'SPK',
        ];

        $prefix = $prefixMap[$jenisSurat] ?? 'SRT';
        $tahun = date('Y');

        // Hitung urutan terakhir untuk prefix & tahun ini
        $lastNumber = PengajuanSurat::where('nomor_referensi', 'LIKE', "{$prefix}-{$tahun}-%")
            ->count();

        $urutan = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tahun}-{$urutan}";
    }
}
