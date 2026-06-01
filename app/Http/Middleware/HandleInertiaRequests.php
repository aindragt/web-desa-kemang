<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'nama' => $request->user()->nama,
                    'username' => $request->user()->username,
                    'role' => $request->user()->role,
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'counters' => function () use ($request) {
                if (!$request->user()) return null;

                $user = $request->user();
                $res = [
                    'surat_pending' => 0,
                    'ttd_cap_missing' => false,
                    'berita_total' => 0,
                    'pesan_unread' => 0,
                ];

                if ($user->role === 'admin') {
                    $res['surat_pending'] = \App\Models\PengajuanSurat::waitingApproval()->count();
                    
                    $ttd = \App\Models\Pengaturan::getValue('ttd_kepala_desa');
                    $cap = \App\Models\Pengaturan::getValue('cap_desa');
                    $res['ttd_cap_missing'] = empty($ttd) || empty($cap);
                } elseif ($user->role === 'operator') {
                    // Operator counters
                    $res['surat_pending'] = \App\Models\PengajuanSurat::whereIn('status', ['menunggu', 'diproses'])->count();
                    $res['berita_total'] = \App\Models\Berita::count();
                    $res['pesan_unread'] = \App\Models\PesanKontak::unread()->count();
                }

                return $res;
            },
        ];
    }
}
