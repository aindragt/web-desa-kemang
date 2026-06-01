<template>
  <div class="min-h-screen bg-[#F5EDD8] flex flex-col items-center justify-center p-6 text-center font-ui text-gray-800">
    <Head :title="title" />

    <!-- Malay Ornament Border Frame -->
    <div class="max-w-md w-full bg-white border border-[#C8952A]/40 rounded-3xl p-8 md:p-12 shadow-xl space-y-6 relative overflow-hidden">
      <!-- Background Motif overlay -->
      <div class="absolute inset-0 opacity-5 pointer-events-none mix-blend-overlay">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
          <pattern id="errorPattern" width="20" height="20" patternUnits="userSpaceOnUse">
            <path d="M 0 10 L 20 10 M 10 0 L 10 20" fill="none" stroke="#C8952A" stroke-width="1"/>
          </pattern>
          <rect width="100%" height="100%" fill="url(#errorPattern)"/>
        </svg>
      </div>

      <!-- Icon -->
      <div class="h-16 w-16 rounded-2xl bg-[#2D5016]/10 text-[#2D5016] flex items-center justify-center mx-auto relative z-10 shrink-0">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>

      <!-- Title / Code -->
      <div class="space-y-2 relative z-10">
        <span class="block text-4xl font-serif font-bold text-[#2D5016] tracking-wider">{{ status }}</span>
        <h2 class="text-base font-bold text-gray-800 uppercase tracking-widest">{{ title }}</h2>
      </div>

      <!-- Description -->
      <p class="text-xs leading-relaxed text-gray-500 font-body relative z-10 max-w-xs mx-auto">
        {{ description }}
      </p>

      <!-- Back button -->
      <div class="pt-4 relative z-10 shrink-0">
        <button
          type="button"
          class="px-5 py-2.5 bg-[#2D5016] hover:bg-[#1f380e] text-white font-bold text-xs uppercase tracking-widest rounded-lg shadow-md cursor-pointer transition-all active:scale-98"
          @click="goBack"
        >
          Kembali ke Halaman Utama
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
  status: {
    type: Number,
    required: true
  }
});

const title = computed(() => {
  return {
    403: 'Akses Ditolak (Forbidden)',
    404: 'Halaman Tidak Ditemukan',
    500: 'Kesalahan Sistem Internal'
  }[props.status] || 'Terjadi Kesalahan';
});

const description = computed(() => {
  return {
    403: 'Maaf, Anda tidak memiliki hak akses atau izin otorisasi untuk membuka berkas halaman administrasi ini.',
    404: 'Mohon maaf, alamat halaman situs web desa yang Anda tuju telah dipindahkan atau tidak tersedia pada server kami.',
    500: 'Terjadi kegagalan komunikasi koneksi internal pada sistem basis data E-Government Desa Kemang.'
  }[props.status] || 'Terjadi gangguan koneksi pada server.';
});

const goBack = () => {
  router.get('/');
};
</script>
