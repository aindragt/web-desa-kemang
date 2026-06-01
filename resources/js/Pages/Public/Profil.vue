<template>
  <PublicLayout>
    <Head title="Profil Desa - E-Government Desa Kemang" />

    <!-- Banner Section -->
    <section class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] text-white py-16 border-b-4 border-[#C8952A] relative">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold">Profil Desa Kemang</h1>
        <p class="text-xs md:text-sm text-amber-100/70 uppercase tracking-widest font-ui font-semibold">
          Kenali Sejarah, Batas Wilayah, Visi Misi, dan Struktur Aparatur Pemerintahan
        </p>
      </div>
    </section>

    <!-- Content Tabs Section -->
    <section class="py-12 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Tab Navigation Buttons -->
        <div class="flex flex-wrap justify-center gap-2 border-b border-gray-100 pb-6 mb-10 font-ui text-xs font-semibold">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            :class="[
              'px-5 py-3 rounded-lg uppercase tracking-wider transition-all duration-200 cursor-pointer',
              activeTab === tab.id
                ? 'bg-[#2D5016] text-white shadow-md'
                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100'
            ]"
            @click="activeTab = tab.id"
          >
            {{ tab.label }}
          </button>
        </div>

        <!-- Tab Contents -->
        <div class="transition-all duration-300">
          
          <!-- Sejarah (Vertical Timeline) -->
          <div v-if="activeTab === 'sejarah'" class="max-w-3xl mx-auto space-y-12">
            <h2 class="text-2xl font-serif font-bold text-[#2D5016] text-center mb-10">Kronik & Sejarah Desa</h2>
            <div class="relative border-l-2 border-[#C8952A]/40 pl-6 ml-4 space-y-12 font-body text-xs">
              <div v-for="time in sejarah" :key="time.year" class="relative">
                <span class="absolute -left-[35px] top-1 h-5 w-5 rounded-full bg-[#C8952A] border-4 border-white shadow-sm flex items-center justify-center" />
                <span class="block text-[#C8952A] font-serif font-bold text-lg leading-none">{{ time.year }}</span>
                <h3 class="text-sm font-bold text-gray-800 mt-2 font-ui">{{ time.title }}</h3>
                <p class="text-gray-500 mt-1 leading-relaxed">{{ time.desc }}</p>
              </div>
            </div>
          </div>

          <!-- Wilayah (Batas Desa) -->
          <div v-if="activeTab === 'wilayah'" class="max-w-4xl mx-auto space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
              <div class="space-y-6">
                <h2 class="text-2xl font-serif font-bold text-[#2D5016]">Geografis & Batas Desa</h2>
                <p class="text-xs leading-relaxed text-gray-600 font-body">
                  Desa Kemang secara administratif masuk ke dalam Kecamatan Pangkalan Kuras, Kabupaten Pelalawan, Riau. Letaknya strategis karena dilewati oleh Lintas Timur Sumatera.
                </p>
                <div class="bg-[#F5EDD8]/20 border border-[#C8952A]/20 rounded-2xl p-6 font-ui text-xs space-y-3">
                  <h4 class="font-bold text-gray-800">Batas-Batas Wilayah Administratif:</h4>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex justify-between border-b border-[#C8952A]/10 pb-1.5"><span>Utara:</span><span class="font-semibold text-gray-800">Desa Palas</span></li>
                    <li class="flex justify-between border-b border-[#C8952A]/10 pb-1.5"><span>Selatan:</span><span class="font-semibold text-gray-800">Kecamatan Pangkalan Lesung</span></li>
                    <li class="flex justify-between border-b border-[#C8952A]/10 pb-1.5"><span>Timur:</span><span class="font-semibold text-gray-800">Desa Sorek Dua</span></li>
                    <li class="flex justify-between pb-1"><span>Barat:</span><span class="font-semibold text-gray-800">Desa Dundangan</span></li>
                  </ul>
                </div>
              </div>
              <div class="bg-[#FAF9F5] border border-gray-100 rounded-3xl p-6 shadow-inner text-center shrink-0">
                <div class="h-64 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest font-ui font-semibold shadow-inner">
                  Peta Wilayah Desa Kemang
                </div>
                <p class="text-[10px] text-gray-400 mt-3 font-body">Visualisasi Peta Kadaster Wilayah Desa Kemang</p>
              </div>
            </div>
          </div>

          <!-- Visi & Misi -->
          <div v-if="activeTab === 'visimisi'" class="max-w-3xl mx-auto space-y-12">
            <div class="bg-[#2D5016] text-white rounded-3xl p-8 border-l-8 border-[#C8952A] shadow-md text-center space-y-4">
              <span class="text-xs tracking-widest font-semibold font-ui text-amber-200">VISI UTAMA</span>
              <p class="text-lg md:text-xl font-serif font-bold leading-relaxed text-amber-50">
                "Mewujudkan Desa Kemang yang Mandiri, Sejahtera, Berkarakter Melayu yang Luhur, berlandaskan Tata Kelola Pemerintahan yang Bersih dan Berbasis Digital Terpercaya."
              </p>
            </div>

            <div class="space-y-6">
              <h3 class="text-lg font-serif font-bold text-[#2D5016] text-center mb-6">Misi Pembangunan Desa</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="(misi, idx) in misiList" :key="idx" class="bg-[#F5EDD8]/20 border border-[#C8952A]/10 rounded-2xl p-5 flex gap-4">
                  <span class="h-7 w-7 rounded-full bg-[#2D5016] text-white flex items-center justify-center text-xs font-bold shrink-0">
                    {{ idx + 1 }}
                  </span>
                  <p class="text-xs text-gray-600 leading-relaxed font-body">{{ misi }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Potensi Desa -->
          <div v-if="activeTab === 'potensi'" class="max-w-5xl mx-auto space-y-10">
            <div class="text-center space-y-3">
              <h2 class="text-2xl font-serif font-bold text-[#2D5016]">Potensi & Kekayaan Desa</h2>
              <p class="text-xs text-gray-500 font-body">Komoditas utama pendukung perekonomian mandiri warga Desa Kemang.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div v-for="potensi in potensiList" :key="potensi.title" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h4 class="text-sm font-bold text-gray-800 font-ui">{{ potensi.title }}</h4>
                <p class="text-xs leading-relaxed text-gray-500 font-body">{{ potensi.desc }}</p>
              </div>
            </div>
          </div>

          <!-- Aparatur Desa (Struktur) -->
          <div v-if="activeTab === 'aparatur'" class="max-w-5xl mx-auto space-y-12">
            <div class="text-center space-y-3">
              <h2 class="text-2xl font-serif font-bold text-[#2D5016]">Aparatur Pemerintahan Desa</h2>
              <p class="text-xs text-gray-500 font-body">Para pelayan publik di jajaran struktur resmi Pemerintah Desa Kemang.</p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 font-ui">
              <div v-for="staff in aparatur" :key="staff.name" class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm text-center flex flex-col hover:shadow-md transition-shadow">
                <div class="h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-semibold uppercase tracking-wider shrink-0">
                  Foto Resmi
                </div>
                <div class="p-5 flex-1 flex flex-col justify-center space-y-1">
                  <h4 class="text-xs font-bold text-gray-800">{{ staff.name }}</h4>
                  <p class="text-[10px] font-semibold text-[#C8952A] uppercase tracking-wider">{{ staff.role }}</p>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const activeTab = ref('sejarah');

const tabs = [
  { id: 'sejarah', label: 'Sejarah' },
  { id: 'wilayah', label: 'Wilayah' },
  { id: 'visimisi', label: 'Visi & Misi' },
  { id: 'potensi', label: 'Potensi' },
  { id: 'aparatur', label: 'Aparatur' }
];

const sejarah = [
  { year: '1974', title: 'Pembukaan Dusun Awal', desc: 'Pemukiman kecil didirikan oleh para tetua adat Melayu dengan fokus pada pemanfaatan hasil getah karet hutan.' },
  { year: '1985', title: 'Perubahan Status Desa Persiapan', desc: 'Mendapatkan pengakuan resmi sebagai wilayah administratif persiapan karena pertumbuhan penduduk yang signifikan.' },
  { year: '1999', title: 'Desa Definitif Swasembada', desc: 'Resmi menyandang gelar desa definitif di bawah naungan Kecamatan Pangkalan Kuras, Kabupaten Pelalawan.' },
  { year: '2024', title: 'Digitalisasi Desa (E-Government)', desc: 'Peluncuran sistem pelayanan digital online kependudukan secara menyeluruh demi pelayanan transparan.' }
];

const misiList = [
  'Meningkatkan tata kelola administrasi pelayanan desa berbasis teknologi informasi (E-Government) yang transparan dan bebas pungli.',
  'Mendorong pembangunan infrastruktur perdesaan secara merata untuk kelancaran arus komoditas perkebunan.',
  'Mengembangkan potensi UMKM lokal dan memperkuat Badan Usaha Milik Desa (BUMDes) sebagai pilar ekonomi warga.',
  'Melestarikan adat dan kebudayaan Melayu yang luhur dalam sendi kehidupan sosial masyarakat.'
];

const potensiList = [
  { title: 'Perkebunan Kelapa Sawit', desc: 'Menjadi penyokong utama perputaran roda ekonomi dengan luas perkebunan sawit rakyat mencapai ratusan hektar.' },
  { title: 'UMKM Kerajinan Anyaman', desc: 'Kelompok usaha wanita melestarikan anyaman tikar dan aksesoris khas pandan hutan yang bernilai ekonomi.' },
  { title: 'Perkebunan Karet Rakyat', desc: 'Komoditas getah karet alam kering tetap eksis dikelola secara tradisional berkesinambungan.' }
];

const aparatur = [
  { name: 'Lukman Hakim', role: 'Kepala Desa' },
  { name: 'Suryadi Pratama', role: 'Sekretaris Desa' },
  { name: 'Megawati Sihombing', role: 'Kaur Keuangan' },
  { name: 'Ahmad Fachri', role: 'Kaur Pembangunan' }
];
</script>
