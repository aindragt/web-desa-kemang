<template>
  <PublicLayout>
    <Head title="Kabar Berita Desa - E-Government Desa Kemang" />

    <!-- Banner Section -->
    <section class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] text-white py-16 border-b-4 border-[#C8952A] relative">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold">Kabar & Berita Desa</h1>
        <p class="text-xs md:text-sm text-amber-100/70 uppercase tracking-widest font-ui font-semibold">
          Informasi Kegiatan Pembangunan, Pengumuman, Agenda, dan Potensi Desa Kemang
        </p>
      </div>
    </section>

    <!-- News Grid Page -->
    <section class="py-12 bg-white font-ui">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs & Search Bar -->
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between border-b border-gray-100 pb-8 mb-10">
          
          <!-- Category Tabs -->
          <div class="flex flex-wrap gap-2 text-xs font-semibold shrink-0">
            <button
              v-for="cat in categories"
              :key="cat.value"
              type="button"
              :class="[
                'px-4 py-2.5 rounded-lg uppercase tracking-wider transition-all duration-150 cursor-pointer',
                activeCategory === cat.value
                  ? 'bg-[#2D5016] text-white shadow-md'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900'
              ]"
              @click="filterCategory(cat.value)"
            >
              {{ cat.label }}
            </button>
          </div>

          <!-- Search Input -->
          <div class="w-full md:w-80 relative shrink-0">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari berita desa..."
              class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D5016]/20 focus:border-[#2D5016]"
              @keyup.enter="handleSearch"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

        </div>

        <!-- News Grid -->
        <div v-if="berita.data && berita.data.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="news in berita.data" :key="news.id" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-200">
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

        <!-- Empty State -->
        <div v-else class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200 space-y-4">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 11-4 0v1m2-1a2 2 0 00-2 2v3m2-3a2 2 0 012 2v3m-2-3V9" />
          </svg>
          <h3 class="text-sm font-bold text-gray-800">Tidak ada kabar berita ditemukan</h3>
          <p class="text-xs text-gray-500 font-body">Coba sesuaikan kata kunci pencarian atau kategori filter Anda.</p>
        </div>

        <!-- Pagination (Inertia Custom Component) -->
        <div v-if="berita.links && berita.links.length > 3" class="flex justify-center items-center gap-1 mt-12 text-xs font-semibold">
          <Link
            v-for="link in berita.links"
            :key="link.label"
            :href="link.url || '#'"
            v-html="link.label"
            :class="[
              'px-3 py-2 rounded-lg border transition-colors',
              link.active
                ? 'bg-[#2D5016] text-white border-[#2D5016]'
                : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50',
              !link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
            ]"
          />
        </div>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';

const props = defineProps({
  berita: {
    type: Object,
    default: () => ({ data: [] })
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

const searchQuery = ref(props.filters.search || '');
const activeCategory = ref(props.filters.category || 'all');

const categories = [
  { label: 'Semua', value: 'all' },
  { label: 'Pembangunan', value: 'pembangunan' },
  { label: 'Pengumuman', value: 'pengumuman' },
  { label: 'Agenda', value: 'agenda' }
];

const handleSearch = () => {
  router.get('/berita', {
    search: searchQuery.value,
    category: activeCategory.value !== 'all' ? activeCategory.value : undefined
  }, { preserveState: true });
};

const filterCategory = (val) => {
  activeCategory.value = val;
  router.get('/berita', {
    search: searchQuery.value || undefined,
    category: val !== 'all' ? val : undefined
  }, { preserveState: true });
};
</script>
