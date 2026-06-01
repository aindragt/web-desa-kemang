<template>
  <PublicLayout>
    <Head title="Layanan Surat Mandiri Online - E-Government Desa Kemang" />

    <!-- Banner Section -->
    <section class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] text-white py-16 border-b-4 border-[#C8952A] relative">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold">Layanan Surat Online</h1>
        <p class="text-xs md:text-sm text-amber-100/70 uppercase tracking-widest font-ui font-semibold">
          Ajukan permohonan surat kependudukan resmi secara mandiri
        </p>
      </div>
    </section>

    <!-- Services Grid Section -->
    <section class="py-16 bg-white font-ui">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-6 border-b border-gray-100 pb-8 mb-12">
          <div class="space-y-1 text-center sm:text-left">
            <h2 class="text-xl font-serif font-bold text-gray-800">Daftar Formulir Surat Tersedia</h2>
            <p class="text-xs text-gray-500 font-body">Silakan pilih salah satu surat di bawah untuk mulai mengisi berkas.</p>
          </div>
          
          <Link href="/layanan-surat/status" class="shrink-0">
            <AppButton variant="secondary" size="md">Cek Status Pengajuan Surat</AppButton>
          </Link>
        </div>

        <!-- 4 Surat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="surat in types" :key="surat.slug" class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition-shadow space-y-6">
            <div class="space-y-4">
              <div class="h-10 w-10 rounded-lg bg-[#2D5016]/10 flex items-center justify-center text-[#2D5016]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <h3 class="text-sm font-bold text-gray-800 leading-tight">
                {{ surat.name }}
              </h3>
              <p class="text-xs text-gray-500 leading-relaxed font-body">
                {{ surat.desc }}
              </p>
            </div>
            
            <Link :href="'/layanan-surat/pengajuan/' + surat.slug" class="block">
              <AppButton variant="ghost" size="sm" customClass="w-full">Ajukan Surat</AppButton>
            </Link>
          </div>
        </div>

        <!-- Quick Guidelines (Security and Requirements notice) -->
        <div class="mt-16 bg-[#F5EDD8]/20 border border-[#C8952A]/20 rounded-3xl p-6 md:p-8 max-w-4xl mx-auto flex flex-col md:flex-row gap-6 items-start">
          <div class="h-10 w-10 rounded-full bg-[#C8952A]/10 flex items-center justify-center text-[#C8952A] shrink-0">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="space-y-2">
            <h4 class="text-sm font-bold text-gray-800">Pemberitahuan Penting Mengenai Validitas Berkas:</h4>
            <p class="text-xs leading-relaxed text-gray-600 font-body">
              Seluruh surat kependudukan resmi yang diproses secara online akan menggunakan <strong>Tanda Tangan Elektronik transparan Kepala Desa serta Cap Desa digital</strong> yang terekam pada server E-Demografi Desa Kemang. Warga tidak perlu khawatir karena tanda legalitas ini dilindungi oleh riwayat audit log sistem.
            </p>
          </div>
        </div>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';

const types = [
  { name: 'Surat Keterangan Usaha (SKU)', slug: 'usaha', desc: 'Surat legalitas keterangan kepemilikan UMKM/bidang usaha di wilayah desa.' },
  { name: 'Surat Keterangan Domisili (SKD)', slug: 'domisili', desc: 'Surat resmi pembuktian kedudukan tempat tinggal tetap di wilayah desa.' },
  { name: 'Surat Pengantar KTP (SPK)', slug: 'ktp', desc: 'Pengantar rekomendasi perekaman KTP baru atau penggantian KTP rusak/hilang.' },
  { name: 'Surat Keterangan Kematian (SKK)', slug: 'kematian', desc: 'Dokumen pencatatan kematian penduduk desa untuk kebutuhan administrasi ahli waris.' }
];
</script>
