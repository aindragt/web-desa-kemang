<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesanKontak;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class OperatorPesanController extends Controller
{
    /**
     * Display a listing of messages.
     */
    public function index(Request $request): Response
    {
        $query = PesanKontak::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kontak', 'like', '%' . $request->search . '%')
                  ->orWhere('pesan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'dibaca') {
                $query->read();
            } elseif ($request->status === 'belum_dibaca') {
                $query->unread();
            }
        }

        $pesan = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Operator/Pesan/Index', [
            'pesan' => $pesan,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Display the specified message. Automatically mark as read if not already.
     */
    public function show(int $id): Response
    {
        $pesan = PesanKontak::findOrFail($id);

        if (!$pesan->is_read) {
            $pesan->update([
                'is_read' => true,
            ]);
        }

        return Inertia::render('Operator/Pesan/Show', [
            'pesan' => $pesan,
        ]);
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $pesan = PesanKontak::findOrFail($id);
        $pesan->delete();

        return redirect()->route('operator.pesan.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
