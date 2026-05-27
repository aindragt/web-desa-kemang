<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    auth: Object
});
</script>

<template>
    <Head title="Dashboard Operator" />
    <div class="min-h-screen bg-krem p-6 font-sans">
        <div class="max-w-4xl mx-auto bg-putih-gading rounded-2xl shadow border-t-8 border-accent p-8">
            <div class="flex items-center justify-between border-b border-accent/10 pb-6 mb-6">
                <div>
                    <h1 class="font-display text-2xl font-bold text-coklat-tua">Dashboard Operator (Staf Desa)</h1>
                    <p class="font-serif text-sm text-primary font-semibold mt-1">Sistem E-Government Desa Kemang</p>
                </div>
                <Link 
                    href="/logout" 
                    method="post" 
                    as="button" 
                    class="px-4 py-2 bg-red-700 text-putih-gading rounded-lg hover:bg-red-800 text-sm font-semibold transition-colors cursor-pointer"
                >
                    Keluar
                </Link>
            </div>
            
            <div class="bg-primary/5 p-6 rounded-xl border border-accent/10 mb-6">
                <h2 class="text-lg font-bold text-coklat-tua mb-2">Informasi Akun</h2>
                <div class="space-y-1 text-sm">
                    <p><span class="font-semibold text-coklat-tua/75">Nama:</span> {{ auth.user.nama }}</p>
                    <p><span class="font-semibold text-coklat-tua/75">Username:</span> {{ auth.user.username }}</p>
                    <p><span class="font-semibold text-coklat-tua/75">Role:</span> <span class="px-2 py-0.5 bg-primary text-putih-gading rounded-full text-xs font-bold font-sans uppercase">{{ auth.user.role }}</span></p>
                </div>
            </div>

            <div class="border border-accent/10 rounded-xl p-6 bg-putih-gading">
                <h3 class="font-display text-md font-bold text-coklat-tua mb-3">Wewenang Role Operator:</h3>
                <ul class="list-disc pl-5 space-y-1.5 text-sm text-coklat-tua/80">
                    <li>Mengelola publikasi portal berita desa (CRUD & Multiple Photo Upload).</li>
                    <li>Mengubah status tracking pengajuan surat warga (Menunggu -> Diproses -> Menunggu Persetujuan).</li>
                    <li>Melakukan cetak dokumen surat resmi (tombol hanya aktif jika status disetujui Kepala Desa).</li>
                    <li>Membaca dan membalas pesan warga dari form kontak.</li>
                </ul>
            </div>
        </div>
    </div>
</template>
