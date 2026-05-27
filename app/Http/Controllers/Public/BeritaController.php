<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Inertia\Inertia;
use Inertia\Response;

class BeritaController extends Controller
{
    /**
     * Display a list of published news.
     */
    public function index(Request $request): Response
    {
        $query = Berita::published()->with(['fotos' => function ($query) {
            $query->ordered();
        }]);

        // Filter: Pencarian judul
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter: Kategori berita
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $berita = $query->orderBy('published_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        // Mengambil list seluruh kategori unik untuk filter dropdown
        $kategoriList = Berita::published()
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        return Inertia::render('Public/Berita/Index', [
            'berita' => $berita,
            'kategoriList' => $kategoriList,
            'filters' => $request->only(['search', 'kategori']),
        ]);
    }

    /**
     * Display the specified news details.
     */
    public function show(string $slug): Response
    {
        $berita = Berita::published()
            ->with(['fotos' => function ($query) {
                $query->ordered();
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil berita terkait (kategori yang sama, limit 3)
        $beritaTerkait = Berita::published()
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return Inertia::render('Public/Berita/Show', [
            'berita' => $berita,
            'beritaTerkait' => $beritaTerkait,
        ]);
    }
}
