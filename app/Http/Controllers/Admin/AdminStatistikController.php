<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Statistik;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminStatistikController extends Controller
{
    /**
     * Display a listing of the statistics grouped by category.
     */
    public function index(Request $request): Response
    {
        $statistik = Statistik::ordered()->get()->groupBy('kategori');

        // Menentukan template render berdasarkan user role (Admin / Operator)
        $role = $request->user()->role;
        $componentPath = $role === 'admin' ? 'Admin/Statistik/Index' : 'Operator/Statistik/Index';

        return Inertia::render($componentPath, [
            'statistik' => $statistik,
        ]);
    }

    /**
     * Store a newly created statistic in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'in:pendidikan,pekerjaan,agama'],
            'label' => ['required', 'string', 'max:255'],
            'nilai' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ], [
            'kategori.required' => 'Kategori statistik wajib diisi.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'label.required' => 'Label statistik wajib diisi.',
            'nilai.required' => 'Nilai statistik wajib diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai tidak boleh minus.',
            'satuan.required' => 'Satuan wajib diisi (misal: jiwa/orang).',
        ]);

        // Default urutan ke paling akhir jika tidak disediakan
        if (!isset($validated['urutan'])) {
            $maxUrutan = Statistik::where('kategori', $validated['kategori'])->max('urutan') ?? 0;
            $validated['urutan'] = $maxUrutan + 1;
        }

        Statistik::create($validated);

        return back()->with('success', 'Data statistik berhasil ditambahkan.');
    }

    /**
     * Update multiple statistics in a single bulk operation.
     */
    public function updateSemua(Request $request): RedirectResponse
    {
        $request->validate([
            'data' => ['required', 'array'],
            'data.*.id' => ['required', 'exists:statistik,id'],
            'data.*.nilai' => ['required', 'numeric', 'min:0'],
            'data.*.urutan' => ['required', 'integer', 'min:0'],
        ], [
            'data.required' => 'Data statistik wajib disertakan.',
            'data.*.id.exists' => 'Data statistik tidak ditemukan.',
            'data.*.nilai.required' => 'Nilai wajib diisi.',
            'data.*.nilai.numeric' => 'Nilai harus berupa angka.',
            'data.*.nilai.min' => 'Nilai tidak boleh negatif.',
            'data.*.urutan.required' => 'Nomor urutan wajib diisi.',
        ]);

        // Eksekusi bulk update menggunakan database transaction agar aman
        \DB::transaction(function () use ($request) {
            foreach ($request->input('data') as $item) {
                Statistik::where('id', $item['id'])->update([
                    'nilai' => $item['nilai'],
                    'urutan' => $item['urutan'],
                ]);
            }
        });

        return back()->with('success', 'Semua data statistik berhasil diperbarui.');
    }

    /**
     * Remove the specified statistic from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $statistik = Statistik::findOrFail($id);
        $statistik->delete();

        return back()->with('success', 'Data statistik berhasil dihapus.');
    }
}
