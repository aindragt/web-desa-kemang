<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    message: String
});
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="min-h-screen flex flex-col items-center justify-center bg-krem p-6 text-center select-none">
        <!-- Logo / Icon -->
        <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center text-accent text-4xl font-bold shadow-lg mb-6 border-2 border-accent animate-pulse">
            DK
        </div>

        <!-- Heading -->
        <h1 class="font-display text-4xl md:text-5xl font-bold text-coklat-tua mb-2">
            Desa Kemang
        </h1>
        <p class="font-serif text-lg text-primary font-semibold mb-6">
            Kec. Pangkalan Kuras, Kab. Pelalawan, Provinsi Riau
        </p>

        <!-- Main Card -->
        <div class="max-w-md bg-putih-gading p-8 rounded-xl shadow-md border-t-4 border-accent">
            <h2 class="font-sans text-xl font-bold text-coklat-tua mb-4">
                Setup Awal Berhasil!
            </h2>
            <p class="font-serif text-sm text-[#6B7280] leading-relaxed mb-6">
                {{ message || 'Sistem E-Government Desa Kemang telah berhasil dikonfigurasi menggunakan Laravel 13, Vue 3, Inertia.js, dan Tailwind CSS 4.' }}
            </p>
            
            <div class="space-y-2">
                <div class="text-xs font-semibold text-primary uppercase tracking-wider">
                    Design System Verified
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary text-putih-gading">Primary (Hijau)</span>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-accent text-coklat-tua">Accent (Emas)</span>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-krem text-coklat-tua">Krem</span>
                </div>
            </div>
        </div>

        <div class="mt-8 text-xs text-[#6B7280] font-sans">
            Mata Kuliah E-Government &copy; 2026
        </div>
    </div>
</template>
