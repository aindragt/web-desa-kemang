<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class AdminOperatorController extends Controller
{
    /**
     * Display a listing of all operator accounts.
     */
    public function index(Request $request): Response
    {
        $query = User::role('operator');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $operators = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Operator/Index', [
            'operators' => $operators,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Store a newly created operator account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_num', 'unique:users,username'],
            'password' => ['required', 'string', Password::min(6)],
        ], [
            'nama.required' => 'Nama operator wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'username' => strtolower($validated['username']),
            'password' => Hash::make($validated['password']),
            'role' => 'operator',
            'is_active' => true,
        ]);

        return back()->with('success', 'Akun operator berhasil dibuat.');
    }

    /**
     * Toggle the active status of an operator account.
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $user = User::role('operator')->findOrFail($id);
        
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun operator {$user->nama} berhasil {$statusText}.");
    }

    /**
     * Reset the password of an operator account.
     */
    public function resetPassword(Request $request, int $id): RedirectResponse
    {
        $user = User::role('operator')->findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'string', Password::min(6)],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "Password operator {$user->nama} berhasil direset.");
    }

    /**
     * Remove the specified operator account.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::role('operator')->findOrFail($id);
        $user->delete();

        return back()->with('success', "Akun operator {$user->nama} berhasil dihapus.");
    }
}
