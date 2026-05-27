<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        // 2. Cek status keaktifan user
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors(['username' => 'Akun Anda dinonaktifkan.']);
        }

        // 3. Cek kesesuaian role
        if ($user->role !== $role) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk melihat halaman ini.');
        }

        return $next($request);
    }
}
