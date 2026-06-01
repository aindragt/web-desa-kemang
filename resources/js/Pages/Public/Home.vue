<template>
  <PublicLayout>
    <Head title="Beranda E-Government Desa Kemang" />

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#2D5016] via-[#1f370e] to-[#0f1d07] text-white py-24 overflow-hidden border-b-4 border-[#C8952A]">
      <!-- Motif Songket Riau (SVG Pattern overlay) -->
      <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="songketPattern" width="40" height="40" patternUnits="userSpaceOnUse">
              <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="#C8952A" stroke-width="2" />
              <circle cx="20" cy="20" r="3" fill="#C8952A" />
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#songketPattern)" />
        </svg>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-[#C8952A]/20 text-amber-200 border border-[#C8952A]/40 uppercase tracking-widest">
          Portal Resmi Pemerintah Desa
        </span>
        
        <h1 class="text-4xl md:text-6xl font-serif font-bold tracking-tight text-white leading-tight">
          Selamat Datang di Portal E-Government <br>
          <span class="text-[#C8952A]">Desa Kemang</span>
        </h1>

        <p class="max-w-2xl mx-auto text-sm md:text-base text-amber-50/80 leading-relaxed font-body">
          Pusat informasi transparansi, pembangunan kependudukan, demografi desa, serta sistem pelayanan surat mandiri warga secara online dan transparan.
        </p>

        <div class="flex justify-center gap-4">
          <Link href="/layanan-surat">
            <AppButton variant="secondary" size="lg">Ajukan Layanan Surat</AppButton>
          </Link>
          <Link href="/profil">
            <AppButton variant="ghost" size="lg" customClass="!bg-white/10 !text-white hover:!bg-white/20 !border-white/20">
              Pelajari Profil Desa
            </AppButton>
          </Link>
        </div>

        <!-- 4 Statistik Utama (Static/Animasi Ringkas) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-12 max-w-5xl mx-auto">
          <div v-for="stat in stats" :key="stat.label" class="bg-black/25 backdrop-blur-md rounded-xl p-5 border border-white/10 shadow-inner">
            <span class="block text-2xl md:text-3xl font-bold text-[#C8952A] font-serif">{{ stat.value }}</span>
            <span class="block text-xs font-semibold text-amber-100/70 mt-1 uppercase tracking-wider">{{ stat.label }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Profil Singkat Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
        <div class="space-y-3">
          <h2 class="text-3xl font-serif font-bold text-[#2D5016]">Selayang Pandang</h2>
          <div class="h-1 w-20 bg-[#C8952A] mx-auto rounded-full" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="card in profilCards" :key="card.title" class="bg-[#F5EDD8]/20 border border-[#C8952A]/10 rounded-2xl p-6 text-left shadow-sm space-y-4 hover:shadow-md transition-shadow duration-200">
            <div class="h-12 w-12 rounded-xl bg-[#2D5016]/10 flex items-center justify-center text-[#2D5016]">
              <component :is="card.icon" class="h-6 w-6" />
            </div>
            <h3 class="text-lg font-serif font-bold text-[#2D5016]">{{ card.title }}</h3>
            <p class="text-xs leading-relaxed text-gray-600 font-body">{{ card.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Layanan Surat Section -->
    <section class="py-20 bg-[#F5EDD8]/20 border-t border-b border-[#C8952A]/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-3 mb-16">
          <h2 class="text-3xl font-serif font-bold text-[#2D5016]">Layanan Surat Online</h2>
          <p class="text-sm text-gray-500 font-body">Ajukan pembuatan berkas kependudukan resmi secara mandiri tanpa antre.</p>
          <div class="h-1 w-20 bg-[#C8952A] mx-auto rounded-full" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Alur 5 Langkah -->
          <div class="space-y-8">
            <h3 class="text-xl font-serif font-bold text-[#2D5016] mb-6">Alur Pengajuan Surat</h3>
            <div class="space-y-6">
              <div v-for="(step, idx) in alur" :key="idx" class="flex gap-4 items-start">
                <span class="h-8 w-8 rounded-full bg-[#C8952A] text-white flex items-center justify-center font-bold text-sm shrink-0">
                  {{ idx + 1 }}
                </span>
                <div>
                  <h4 class="text-sm font-bold text-gray-800">{{ step.title }}</h4>
                  <p class="text-xs text-gray-500 font-body mt-1 leading-relaxed">{{ step.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- 4 Jenis Kartu Surat -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div v-for="surat in jenisSurat" :key="surat.name" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
              <h4 class="text-sm font-bold text-gray-800">{{ surat.name }}</h4>
              <p class="text-[11px] leading-relaxed text-gray-500 font-body">{{ surat.desc }}</p>
              <Link :href="'/layanan-surat/pengajuan/' + surat.slug" class="block">
                <AppButton variant="ghost" size="sm" customClass="w-full">Pilih Layanan</AppButton>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Berita Terbaru Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-3 mb-16">
          <h2 class="text-3xl font-serif font-bold text-[#2D5016]">Berita Desa Terbaru</h2>
          <p class="text-sm text-gray-500 font-body">Ikuti terus kabar perkembangan kegiatan dan pembangunan di Desa Kemang.</p>
          <div class="h-1 w-20 bg-[#C8952A] mx-auto rounded-full" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="news in berita" :key="news.id" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-200">
            <div class="h-48 bg-gray-100 relative overflow-hidden shrink-0">
              <img
                :src="news.foto_utama ? '/storage/' + news.foto_utama : '/storage/default_berita.png'"
                class="w-full h-full object-cover"
                alt="Foto berita"
              />
              <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#C8952A]/90 text-white uppercase tracking-wider">
                {{ news.kategori }}
              </span>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
              <div class="space-y-2">
                <span class="text-[10px] font-semibold text-[#C8952A] tracking-wider uppercase">
                  {{ new Date(news.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                </span>
                <h3 class="text-base font-serif font-bold text-gray-800 line-clamp-2 leading-snug">
                  {{ news.judul }}
                </h3>
                <p class="text-xs text-gray-500 font-body line-clamp-3 leading-relaxed">
                  {{ news.excerpt || news.isi_singkat }}
                </p>
              </div>
              <Link :href="'/berita/' + (news.slug || news.id)" class="block">
                <AppButton variant="ghost" size="sm" customClass="w-full">Baca Selengkapnya</AppButton>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-[#2D5016] text-white relative overflow-hidden border-t-4 border-[#C8952A]">
      <div class="max-w-5xl mx-auto px-4 text-center space-y-6 relative z-10">
        <h2 class="text-3xl font-serif font-bold text-amber-100">Hubungi Pusat Layanan Warga</h2>
        <p class="max-w-xl mx-auto text-xs text-amber-50/70 font-body leading-relaxed">
          Temukan bantuan administrasi, adukan keluhan pembangunan, atau sampaikan aspirasi Anda langsung kepada Staf Kantor Desa Kemang.
        </p>
        <Link href="/kontak" class="inline-block">
          <AppButton variant="secondary" size="lg">Hubungi Kontak Kami</AppButton>
        </Link>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';

defineProps({
  berita: {
    type: Array,
    default: () => []
  }
});

const stats = [
  { label: 'Total Penduduk', value: '2.280 Jiwa' },
  { label: 'Kepala Keluarga', value: '540 KK' },
  { label: 'Luas Wilayah', value: '45,8 Km²' },
  { label: 'Jumlah Dusun', value: '4 Dusun' }
];

const profilCards = [
  {
    title: 'Wilayah Strategis',
    description: 'Terletak di poros perlintasan utama Sumatera Timur dengan kontur tanah subur dan perkebunan produktif.',
    icon: {
      template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
    }
  },
  {
    title: 'Potensi Demografi',
    description: 'Didukung oleh mayoritas masyarakat usia produktif yang aktif dalam usaha tani mandiri serta UMKM lokal.',
    icon: {
      template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>`
    }
  },
  {
    title: 'Visi Mandiri',
    description: 'Mewujudkan tata kelola desa bersih berlandaskan digitalisasi e-government terpercaya demi kesejahteraan warga.',
    icon: {
      template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`
    }
  },
  {
    title: 'Misi Transparan',
    description: 'Mendukung reformasi birokrasi, pemanfaatan teknologi, dan pembangunan infrastruktur desa berkesinambungan.',
    icon: {
      template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`
    }
  }
];

const alur = [
  { title: 'Pilih Jenis Surat', desc: 'Warga memilih surat kependudukan yang diperlukan di portal layanan kami.' },
  { title: 'Lengkapi Data Formulir', desc: 'Isi informasi kependudukan NIK, Nama Lengkap, dan lampiran data pendukung.' },
  { title: 'Verifikasi & Validasi', desc: 'Staf operator desa memeriksa berkas dan admin (kades) memberikan TTD & Cap secara digital.' },
  { title: 'Pantau Status Realtime', desc: 'Pantau posisi persetujuan surat Anda secara langsung menggunakan nomor referensi unik.' },
  { title: 'Cetak Dokumen Selesai', desc: 'Datang langsung ke kantor desa untuk mencetak atau minta operator mengirimkan PDF resminya.' }
];

const jenisSurat = [
  { name: 'Surat Keterangan Usaha', desc: 'Digunakan warga untuk legalitas badan usaha/UMKM mandiri.', slug: 'usaha' },
  { name: 'Surat Keterangan Domisili', desc: 'Surat keterangan domisili resmi tinggal menetap di wilayah desa.', slug: 'domisili' },
  { name: 'Surat Pengantar KTP', desc: 'Digunakan untuk rekomendasi penerbitan/pergantian KTP baru di kecamatan.', slug: 'ktp' },
  { name: 'Surat Keterangan Kematian', desc: 'Surat keterangan pelaporan ahli waris atas kematian warga desa.', slug: 'kematian' }
];
</script>
