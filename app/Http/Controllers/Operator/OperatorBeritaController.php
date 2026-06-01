<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\FotoBerita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class OperatorBeritaController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request): Response
    {
        $query = Berita::with(['fotos' => function ($query) {
            $query->ordered();
        }]);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $berita = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Operator/Berita/Index', [
            'berita' => $berita,
            'filters' => $request->only(['search', 'kategori']),
        ]);
    }

    /**
     * Show the form for creating a new news.
     */
    public function create(): Response
    {
        return Inertia::render('Operator/Berita/Create');
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'ringkasan' => ['required', 'string'],
            'isi' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // Maks 2MB
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'kategori.required' => 'Kategori berita wajib dipilih.',
            'ringkasan.required' => 'Ringkasan berita wajib diisi.',
            'isi.required' => 'Isi berita wajib diisi.',
            'is_published.required' => 'Status publikasi wajib ditentukan.',
            'fotos.*.image' => 'File harus berupa gambar.',
            'fotos.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'fotos.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $validated['penulis'] = Auth::user()->nama;
        $validated['published_at'] = $request->is_published ? now() : null;

        // Auto slug generator via model boot
        $berita = Berita::create($validated);

        // Upload multiple foto jika ada
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $index => $file) {
                $path = $file->store('berita', 'public');
                
                FotoBerita::create([
                    'berita_id' => $berita->id,
                    'foto' => $path,
                    'keterangan' => 'Foto Lampiran ' . ($index + 1),
                    'urutan' => $index,
                ]);
            }
        }

        return redirect()->route('operator.berita.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(int $id): Response
    {
        $berita = Berita::with(['fotos' => function ($query) {
            $query->ordered();
        }])->findOrFail($id);

        return Inertia::render('Operator/Berita/Edit', [
            'berita' => $berita,
        ]);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'ringkasan' => ['required', 'string'],
            'isi' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'kategori.required' => 'Kategori berita wajib dipilih.',
            'ringkasan.required' => 'Ringkasan berita wajib diisi.',
            'isi.required' => 'Isi berita wajib diisi.',
            'is_published.required' => 'Status publikasi wajib ditentukan.',
            'fotos.*.image' => 'File harus berupa gambar.',
            'fotos.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'fotos.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Atur published_at jika status berubah menjadi published
        if ($request->is_published && !$berita->is_published) {
            $validated['published_at'] = now();
        } elseif (!$request->is_published) {
            $validated['published_at'] = null;
        }

        $berita->update($validated);

        // Upload foto baru jika ada
        if ($request->hasFile('fotos')) {
            // Hitung urutan terakhir foto berita
            $lastOrder = FotoBerita::where('berita_id', $berita->id)->max('urutan') ?? -1;

            foreach ($request->file('fotos') as $index => $file) {
                $path = $file->store('berita', 'public');
                
                FotoBerita::create([
                    'berita_id' => $berita->id,
                    'foto' => $path,
                    'keterangan' => 'Foto Lampiran Baru ' . ($index + 1),
                    'urutan' => $lastOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->route('operator.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified news and its associated files from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $berita = Berita::with('fotos')->findOrFail($id);

        // Hapus fisik foto berita di storage
        foreach ($berita->fotos as $foto) {
            Storage::disk('public')->delete($foto->foto);
        }

        // Hapus berita dari DB (tabel foto_berita akan ikut cascade deleted)
        $berita->delete();

        return redirect()->route('operator.berita.index')->with('success', 'Berita beserta lampiran fotonya berhasil dihapus.');
    }

    /**
     * Toggle the publish status of a news.
     */
    public function togglePublish(int $id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);
        
        $newStatus = !$berita->is_published;
        
        $berita->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? now() : null,
        ]);

        $message = $newStatus ? 'Berita berhasil diterbitkan ke publik.' : 'Berita berhasil diubah menjadi draft.';

        return back()->with('success', $message);
    }

    /**
     * Delete a single photo from news gallery (Asynchronous / Edit page action).
     */
    public function hapusFoto(int $fotoId): RedirectResponse
    {
        $foto = FotoBerita::findOrFail($fotoId);

        // Hapus file fisik di storage
        Storage::disk('public')->delete($foto->foto);

        // Hapus relasi DB
        $foto->delete();

        return back()->with('success', 'Foto lampiran berhasil dihapus.');
    }
}
