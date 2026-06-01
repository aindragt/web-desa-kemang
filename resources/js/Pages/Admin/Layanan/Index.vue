<template>
  <AdminLayout>
    <Head title="Antrean Validasi Surat - Panel Admin" />

    <div class="space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Antrean Validasi & Persetujuan Surat</h1>
        <p class="text-xs text-gray-500 font-body">Tinjau, tanda tangani secara digital, atau tolak permohonan berkas kependudukan warga.</p>
      </div>

      <!-- Filters & Search -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm flex flex-col lg:flex-row gap-4 items-center justify-between shrink-0">
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
          <!-- Filter Status -->
          <AppSelect
            id="status_filter"
            v-model="activeFilters.status"
            placeholder="Semua Status"
            :options="statusOptions"
            @change="handleSearch"
            customClass="!py-2 text-xs"
          />

          <!-- Filter Jenis Surat -->
          <AppSelect
            id="jenis_filter"
            v-model="activeFilters.jenis_surat"
            placeholder="Semua Jenis Surat"
            :options="jenisSuratOptions"
            @change="handleSearch"
            customClass="!py-2 text-xs"
          />
        </div>

        <!-- Search Bar -->
        <div class="w-full lg:w-85 relative">
          <input
            type="text"
            v-model="activeFilters.search"
            placeholder="Cari Nama / NIK / No. Ref..."
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
                <th class="py-3 pr-2">Nomor Ref</th>
                <th class="py-3 px-2">Nama Pemohon</th>
                <th class="py-3 px-2">NIK</th>
                <th class="py-3 px-2">Jenis Surat</th>
                <th class="py-3 px-2">Maksud Keperluan</th>
                <th class="py-3 px-2">Tanggal Masuk</th>
                <th class="py-3 px-2 text-center">Status</th>
                <th class="py-3 pl-2 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
              <tr v-for="item in surat.data" :key="item.id" class="hover:bg-gray-50/50">
                <td class="py-3.5 pr-2 font-mono text-gray-500">{{ item.nomor_referensi }}</td>
                <td class="py-3.5 px-2 font-bold">{{ item.nama_lengkap }}</td>
                <td class="py-3.5 px-2 font-mono text-gray-400">{{ item.nik }}</td>
                <td class="py-3.5 px-2 uppercase text-[10px]">{{ item.jenis_surat }}</td>
                <td class="py-3.5 px-2 max-w-xs truncate font-body text-gray-500">{{ item.keperluan }}</td>
                <td class="py-3.5 px-2">{{ new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</td>
                <td class="py-3.5 px-2 text-center">
                  <AppBadge :variant="badgeVariant(item.status)" size="sm">
                    {{ formatStatus(item.status) }}
                  </AppBadge>
                </td>
                <td class="py-3.5 pl-2 text-right whitespace-nowrap">
                  <Link :href="'/admin/validasi/' + item.id" class="inline-block">
                    <AppButton variant="secondary" size="sm" customClass="!py-1">Validasi</AppButton>
                  </Link>
                </td>
              </tr>
              <tr v-if="surat.data.length === 0">
                <td colspan="8" class="py-10 text-center text-gray-400 font-body">Tidak ada pengajuan surat dalam antrean.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="surat.links && surat.links.length > 3" class="flex justify-center items-center gap-1 mt-8 text-xs font-semibold">
          <Link
            v-for="link in surat.links"
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
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppSelect from '@/Components/UI/AppSelect.vue';

const props = defineProps({
  surat: {
    type: Object,
    default: () => ({ data: [] })
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

const activeFilters = reactive({
  status: props.filters.status || 'menunggu_persetujuan', // Default filter menunggu_persetujuan
  jenis_surat: props.filters.jenis_surat || '',
  search: props.filters.search || ''
});

const statusOptions = [
  { value: 'menunggu_persetujuan', label: 'Menunggu Validasi Kades' },
  { value: 'disetujui', label: 'Disetujui' },
  { value: 'selesai', label: 'Selesai (Dicetak)' },
  { value: 'ditolak', label: 'Ditolak' }
];

const jenisSuratOptions = [
  { value: 'usaha', label: 'Keterangan Usaha (SKU)' },
  { value: 'domisili', label: 'Keterangan Domisili (SKD)' },
  { value: 'ktp', label: 'Pengantar KTP (SPK)' },
  { value: 'kematian', label: 'Keterangan Kematian (SKK)' }
];

const formatStatus = (status) => {
  const map = {
    menunggu_persetujuan: 'Waiting TTD',
    disetujui: 'Disetujui',
    selesai: 'Selesai',
    ditolak: 'Ditolak'
  };
  return map[status] || status;
};

const badgeVariant = (status) => {
  const map = {
    menunggu_persetujuan: 'emas',
    disetujui: 'hijau',
    selesai: 'hijau',
    ditolak: 'merah'
  };
  return map[status] || 'abu';
};

const handleSearch = () => {
  router.get('/admin/validasi', {
    status: activeFilters.status || undefined,
    jenis_surat: activeFilters.jenis_surat || undefined,
    search: activeFilters.search || undefined
  }, { preserveState: true });
};
</script>
