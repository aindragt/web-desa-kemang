<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 1. ROUTE PUBLIK (Warga - Tanpa Login)
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', function () {
    return Inertia::render('Public/Beranda');
})->name('home');

// Profil Desa
Route::get('/profil', function () {
    return Inertia::render('Public/Profil');
})->name('profil');

// Statistik Desa
Route::get('/statistik', function () {
    return Inertia::render('Public/Statistik');
})->name('statistik');

// Berita Desa
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Public/Berita/Index');
    })->name('index');
    
    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Public/Berita/Show', ['slug' => $slug]);
    })->name('show');
});

// Layanan Surat Online (E-Service)
Route::prefix('layanan-surat')->name('layanan-surat.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Public/LayananSurat/Index');
    })->name('index');
    
    Route::post('/', function () {
        // Logika submit pengajuan surat
        return back()->with('success', 'Pengajuan surat berhasil dikirim.');
    })->name('store');
    
    Route::get('/status', function () {
        return Inertia::render('Public/LayananSurat/CekStatus');
    })->name('status');
});

// Kontak
Route::prefix('kontak')->name('kontak.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Public/Kontak');
    })->name('index');
    
    Route::post('/', function () {
        // Logika kirim pesan kontak
        return back()->with('success', 'Pesan Anda berhasil dikirim.');
    })->name('store');
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
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

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
    Route::get('/', function () {
        return Inertia::render('Operator/Dashboard');
    })->name('dashboard');

    // Kelola Berita (CRUD + Multiple Photos)
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Operator/Berita/Index');
        })->name('index');
        
        Route::get('/tambah', function () {
            return Inertia::render('Operator/Berita/Create');
        })->name('create');
        
        Route::post('/', function () {
            return redirect()->route('operator.berita.index')->with('success', 'Berita berhasil diterbitkan.');
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) {
            return Inertia::render('Operator/Berita/Edit', ['id' => $id]);
        })->name('edit');
        
        Route::post('/{id}', function ($id) { // Menggunakan POST untuk multipart/form-data update
            return redirect()->route('operator.berita.index')->with('success', 'Berita berhasil diperbarui.');
        })->name('update');
        
        Route::delete('/{id}', function ($id) {
            return back()->with('success', 'Berita berhasil dihapus.');
        })->name('destroy');
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
