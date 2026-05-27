<?php

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'message' => 'Sistem E-Government Desa Kemang telah berhasil dikonfigurasi menggunakan Laravel, Vue 3, Inertia.js, dan Tailwind CSS 4.'
    ]);
});
