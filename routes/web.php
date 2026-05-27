<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Inertia\Inertia;

// 1. Public Routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'message' => 'Sistem E-Government Desa Kemang telah berhasil dikonfigurasi menggunakan Laravel, Vue 3, Inertia.js, dan Tailwind CSS 4.'
    ]);
});

// 2. Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// 3. Protected Admin Routes (Hanya Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    });
});

// 4. Protected Operator Routes (Hanya Operator)
Route::middleware(['auth', 'role:operator'])->prefix('operator')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Operator/Dashboard');
    });
});
