<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\LayananController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\OperatorBeritaController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 1. ROUTE PUBLIK (Warga - Tanpa Login)
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil Desa
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');

// Statistik Desa
Route::get('/statistik', [HomeController::class, 'statistik'])->name('statistik');

// Berita Desa
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
});

// Layanan Surat Online (E-Service)
Route::prefix('layanan-surat')->name('layanan-surat.')->group(function () {
    Route::get('/', [LayananController::class, 'index'])->name('index');
    Route::get('/pengajuan/{jenis}', [LayananController::class, 'form'])->name('form');
    Route::post('/pengajuan', [LayananController::class, 'submit'])->name('submit');
    Route::get('/status', [LayananController::class, 'cekStatus'])->name('status');
    Route::get('/{ref}/slip', [LayananController::class, 'downloadUlang'])->name('slip');
});

// Kontak
Route::prefix('kontak')->name('kontak.')->group(function () {
    Route::get('/', [HomeController::class, 'kontak'])->name('index');
    Route::post('/', [HomeController::class, 'kirimPesan'])->name('store');
});

/*
|--------------------------------------------------------------------------
| 2. ROUTE AUTENTIKASI (1 Halaman Login Bersama)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. ROUTE PROTECTED ADMIN (Kepala Desa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    // Validasi Surat (Setujui / Tolak / Kembalikan)
    Route::prefix('validasi')->name('validasi.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/ValidasiSurat/Index');
        })->name('index');
        
        Route::get('/{id}', function ($id) {
            return Inertia::render('Admin/ValidasiSurat/Show', ['id' => $id]);
        })->name('show');
        
        Route::put('/{id}', function ($id) {
            // Logika validasi surat (Put/Patch)
            return redirect()->route('admin.validasi.index')->with('success', 'Status surat berhasil diperbarui.');
        })->name('update');
    });

    // Pengaturan TTD & Cap
    Route::get('/pengaturan', function () {
        return Inertia::render('Admin/Pengaturan');
    })->name('pengaturan');
    
    Route::post('/pengaturan', function () {
        // Logika update pengaturan TTD / Cap
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    })->name('pengaturan.update');

    // Kelola Statistik
    Route::prefix('statistik')->name('statistik.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Statistik/Index');
        })->name('index');
        
        Route::get('/tambah', function () {
            return Inertia::render('Admin/Statistik/Form');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.statistik.index')->with('success', 'Data statistik berhasil ditambahkan.');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return Inertia::render('Admin/Statistik/Form', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.statistik.index')->with('success', 'Data statistik berhasil diperbarui.');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return back()->with('success', 'Data statistik berhasil dihapus.');
        })->name('destroy');
    });

    // Kelola Akun Operator
    Route::prefix('operator')->name('operator.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Operator/Index');
        })->name('index');
        
        Route::get('/tambah', function () {
            return Inertia::render('Admin/Operator/Form');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('admin.operator.index')->with('success', 'Akun operator berhasil dibuat.');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return Inertia::render('Admin/Operator/Form', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('admin.operator.index')->with('success', 'Akun operator berhasil diperbarui.');
        })->name('update');
        
        Route::patch('/{id}/toggle', function ($id) {
            // Logika aktif / nonaktifkan operator
            return back()->with('success', 'Status operator berhasil diubah.');
        })->name('toggle');
        
        Route::delete('/{id}', function ($id) {
            return back()->with('success', 'Akun operator berhasil dihapus.');
        })->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| 4. ROUTE PROTECTED OPERATOR (Staf Desa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    // Dashboard Operator
    Route::get('/', OperatorDashboardController::class)->name('dashboard');

    // Kelola Berita (CRUD + Multiple Photos)
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [OperatorBeritaController::class, 'index'])->name('index');
        Route::get('/tambah', [OperatorBeritaController::class, 'create'])->name('create');
        Route::post('/', [OperatorBeritaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [OperatorBeritaController::class, 'edit'])->name('edit');
        Route::post('/{id}', [OperatorBeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [OperatorBeritaController::class, 'destroy'])->name('destroy');
        
        // Aksi Tambahan: Toggle status publish & hapus foto gallery individual
        Route::patch('/{id}/toggle-publish', [OperatorBeritaController::class, 'togglePublish'])->name('toggle-publish');
        Route::delete('/foto/{fotoId}', [OperatorBeritaController::class, 'hapusFoto'])->name('foto.destroy');
    });

    // Kelola Statistik
    Route::prefix('statistik')->name('statistik.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Operator/Statistik/Index');
        })->name('index');
        
        Route::get('/tambah', function () {
            return Inertia::render('Operator/Statistik/Form');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('operator.statistik.index')->with('success', 'Statistik berhasil ditambahkan.');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return Inertia::render('Operator/Statistik/Form', ['id' => $id]);
        })->name('edit');
        
        Route::put('/{id}', function ($id) {
            return redirect()->route('operator.statistik.index')->with('success', 'Statistik berhasil diperbarui.');
        })->name('update');
    });

    // Proses Surat Warga (Ubah status & Cetak)
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Operator/Surat/Index');
        })->name('index');
        
        Route::get('/{id}', function ($id) {
            return Inertia::render('Operator/Surat/Show', ['id' => $id]);
        })->name('show');
        
        Route::patch('/{id}/status', function ($id) {
            // Ubah status proses (misal: "Diproses" atau "Menunggu Persetujuan")
            return back()->with('success', 'Status surat berhasil diperbarui.');
        })->name('status.update');
        
        Route::get('/{id}/cetak', function ($id) {
            // Merender view cetak HTML ( window.print() )
            return view('surat.cetak', ['id' => $id]);
        })->name('cetak');
    });

    // Kelola & Baca Pesan Kontak
    Route::prefix('pesan')->name('pesan.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Operator/Pesan/Index');
        })->name('index');
        
        Route::get('/{id}', function ($id) {
            return Inertia::render('Operator/Pesan/Show', ['id' => $id]);
        })->name('show');
        
        Route::delete('/{id}', function ($id) {
            return redirect()->route('operator.pesan.index')->with('success', 'Pesan berhasil dihapus.');
        })->name('destroy');
    });
});
