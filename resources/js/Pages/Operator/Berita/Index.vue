<template>
  <OperatorLayout>
    <Head title="Kelola Berita Desa - Panel Operator" />

    <div class="space-y-8 font-ui">
      
      <!-- Top Actions -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <div class="space-y-1 text-center sm:text-left">
          <h1 class="text-lg font-serif font-bold text-[#2D5016]">Kelola Kabar & Berita Desa</h1>
          <p class="text-xs text-gray-500 font-body">Publikasikan informasi kegiatan dan pengumuman resmi Desa Kemang.</p>
        </div>
        
        <Link href="/operator/berita/tambah" class="shrink-0">
          <AppButton variant="primary" size="md">Tulis Berita Baru</AppButton>
        </Link>
      </div>

      <!-- Filters & Search -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between shrink-0">
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 text-xs font-semibold">
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

        <!-- Search Bar -->
        <div class="w-full md:w-85 relative">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari judul berita..."
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

      <!-- Table Content -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                <th class="py-3 pr-2">Judul</th>
                <th class="py-3 px-2">Kategori</th>
                <th class="py-3 px-2 text-center">Foto</th>
                <th class="py-3 px-2">Penulis</th>
                <th class="py-3 px-2">Status</th>
                <th class="py-3 pl-2 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
              <tr v-for="news in berita.data" :key="news.id" class="hover:bg-gray-50/50">
                <!-- Title & Date -->
                <td class="py-3.5 pr-2 max-w-sm">
                  <span class="block font-bold text-gray-800 line-clamp-1 leading-snug">{{ news.judul }}</span>
                  <span class="block text-[10px] text-gray-400 mt-0.5 leading-none">
                    {{ new Date(news.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                  </span>
                </td>

                <td class="py-3.5 px-2">
                  <span class="uppercase text-[10px] tracking-wider font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                    {{ news.kategori }}
                  </span>
                </td>

                <td class="py-3.5 px-2 text-center font-bold">
                  {{ news.foto_berita_count || 0 }}
                </td>

                <td class="py-3.5 px-2">{{ news.penulis || 'Operator' }}</td>

                <td class="py-3.5 px-2">
                  <AppBadge :variant="news.is_published ? 'hijau' : 'abu'" size="sm">
                    {{ news.is_published ? 'Tayang' : 'Draft' }}
                  </AppBadge>
                </td>

                <!-- Actions -->
                <td class="py-3.5 pl-2 text-right space-x-1 whitespace-nowrap">
                  <!-- Toggle Publish Button -->
                  <AppButton
                    variant="ghost"
                    size="sm"
                    customClass="!py-1"
                    @click="togglePublish(news.id)"
                  >
                    {{ news.is_published ? 'Jadikan Draft' : 'Tayangkan' }}
                  </AppButton>
                  
                  <Link :href="'/operator/berita/' + news.id + '/edit'" class="inline-block">
                    <AppButton variant="secondary" size="sm" customClass="!py-1">
                      Edit
                    </AppButton>
                  </Link>

                  <AppButton
                    variant="danger"
                    size="sm"
                    customClass="!py-1"
                    @click="confirmDelete(news.id)"
                  >
                    Hapus
                  </AppButton>
                </td>
              </tr>
              <tr v-if="berita.data.length === 0">
                <td colspan="6" class="py-10 text-center text-gray-400 font-body">Tidak ada artikel berita ditemukan.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="berita.links && berita.links.length > 3" class="flex justify-center items-center gap-1 mt-8 text-xs font-semibold">
          <Link
            v-for="link in berita.links"
            :key="link.label"
            :href="link.url || '#'"
            v-html="link.label"
            :class="[
              'px-2.5 py-1.5 rounded-lg border transition-colors',
              link.active
                ? 'bg-[#2D5016] text-white border-[#2D5016]'
                : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50',
              !link.url ? 'opacity-45 cursor-not-allowed pointer-events-none' : ''
            ]"
          />
        </div>
      </div>
    </div>

    <!-- Confirm Danger Modal -->
    <AppModal
      :show="showDeleteModal"
      title="Hapus Berita Desa"
      message="Apakah Anda yakin ingin menghapus kabar berita ini? Seluruh data artikel beserta foto-foto pendukungnya akan dihapus permanen dari server."
      confirmText="Ya, Hapus Permanen"
      cancelText="Batal"
      :loading="deleting"
      @close="showDeleteModal = false"
      @confirm="executeDelete"
    />
  </OperatorLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppModal from '@/Components/UI/AppModal.vue';

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

const showDeleteModal = ref(false);
const selectedBeritaId = ref(null);
const deleting = ref(false);

const categories = [
  { label: 'Semua', value: 'all' },
  { label: 'Pembangunan', value: 'pembangunan' },
  { label: 'Pengumuman', value: 'pengumuman' },
  { label: 'Agenda', value: 'agenda' }
];

const handleSearch = () => {
  router.get('/operator/berita', {
    search: searchQuery.value,
    category: activeCategory.value !== 'all' ? activeCategory.value : undefined
  }, { preserveState: true });
};

const filterCategory = (val) => {
  activeCategory.value = val;
  router.get('/operator/berita', {
    search: searchQuery.value || undefined,
    category: val !== 'all' ? val : undefined
  }, { preserveState: true });
};

const togglePublish = (id) => {
  router.patch(`/operator/berita/${id}/toggle-publish`, {}, {
    preserveScroll: true
  });
};

const confirmDelete = (id) => {
  selectedBeritaId.value = id;
  showDeleteModal.value = true;
};

const executeDelete = () => {
  deleting.value = true;
  router.delete(`/operator/berita/${selectedBeritaId.value}`, {
    preserveScroll: true,
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
      selectedBeritaId.value = null;
    }
  });
};
</script>
