<template>
  <PublicLayout>
    <Head title="Pengajuan Surat Berhasil - E-Government Desa Kemang" />

    <!-- Success Section -->
    <section class="py-16 bg-white font-ui">
      <div class="max-w-xl mx-auto px-4 sm:px-6 text-center space-y-8">
        
        <!-- Large success check circle -->
        <div class="h-20 w-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
          <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <div class="space-y-3">
          <h1 class="text-2xl font-serif font-bold text-gray-900">Pengajuan Surat Berhasil Dikirim!</h1>
          <p class="text-xs text-gray-500 font-body max-w-sm mx-auto leading-relaxed">
            Permohonan surat Anda telah tersimpan dengan aman pada basis data E-Government Desa Kemang.
          </p>
        </div>

        <!-- Reference Box -->
        <div class="bg-[#F5EDD8]/30 border border-[#C8952A]/30 rounded-3xl p-6 space-y-4">
          <div>
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest">Nomor Referensi Anda</span>
            <span class="block text-xl font-mono font-bold text-[#2D5016] tracking-wide mt-1">
              {{ nomor_referensi }}
            </span>
          </div>
          <div class="h-px bg-[#C8952A]/20 w-full" />
          <div class="text-left space-y-2 text-xs font-body text-gray-600 leading-relaxed">
            <p><strong>Nama Pemohon:</strong> {{ nama_pemohon }}</p>
            <p><strong>Jenis Surat:</strong> {{ formatJenis(jenis_surat) }}</p>
          </div>
        </div>

        <!-- Action Guide Card -->
        <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 text-left space-y-4">
          <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Panduan Langkah Selanjutnya:</h3>
          <ul class="space-y-2 text-xs text-gray-500 font-body leading-relaxed list-disc pl-4">
            <li><strong>Simpan Baik-Baik</strong> nomor referensi di atas untuk pelacakan.</li>
            <li>Anda dapat memantau status secara langsung melalui halaman <strong>Cek Status Pengajuan</strong>.</li>
            <li>Staf operator desa akan melakukan verifikasi berkas paling lambat 1x24 jam pada hari pelayanan resmi.</li>
          </ul>
        </div>

        <!-- Button Actions -->
        <div class="flex gap-3 justify-center">
          <Link :href="'/layanan-surat/status?ref=' + nomor_referensi">
            <AppButton variant="secondary" size="md">Lacak Surat Sekarang</AppButton>
          </Link>
          <Link href="/">
            <AppButton variant="ghost" size="md">Kembali ke Beranda</AppButton>
          </Link>
        </div>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';

defineProps({
  nomor_referensi: {
    type: String,
    required: true
  },
  nama_pemohon: {
    type: String,
    required: true
  },
  jenis_surat: {
    type: String,
    required: true
  }
});

const formatJenis = (slug) => {
  const map = {
    usaha: 'Surat Keterangan Usaha (SKU)',
    domisili: 'Surat Keterangan Domisili (SKD)',
    ktp: 'Surat Pengantar KTP (SPK)',
    kematian: 'Surat Keterangan Kematian (SKK)'
  };
  return map[slug] || slug;
};
</script>
