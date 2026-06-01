<template>
  <OperatorLayout>
    <Head title="Kelola Layanan Surat - Panel Operator" />

    <div class="space-y-8 font-ui">
      
      <!-- Top Actions Banner -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Kelola Layanan Surat Warga</h1>
        <p class="text-xs text-gray-500 font-body">Verifikasi berkas permohonan warga, ubah progres pengerjaan, dan lakukan pencetakan surat fisik.</p>
      </div>

      <!-- 6 Stat Cards per status -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 shrink-0">
        <div
          v-for="card in statCards"
          :key="card.label"
          class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm text-center space-y-1 cursor-pointer hover:border-[#C8952A]/40 transition-colors"
          @click="filterStatus(card.value)"
        >
          <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ card.label }}</span>
          <span class="block text-xl font-serif font-bold text-[#2D5016]">{{ card.count }}</span>
        </div>
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
                <td class="py-3.5 pl-2 text-right space-x-1.5 whitespace-nowrap">
                  <Link :href="'/operator/surat/' + item.id" class="inline-block">
                    <AppButton variant="ghost" size="sm" customClass="!py-1">Detail</AppButton>
                  </Link>
                  <a
                    v-if="['disetujui', 'selesai'].includes(item.status)"
                    :href="'/operator/surat/' + item.id + '/cetak'"
                    class="inline-block"
                  >
                    <AppButton variant="secondary" size="sm" customClass="!py-1">Cetak</AppButton>
                  </a>
                </td>
              </tr>
              <tr v-if="surat.data.length === 0">
                <td colspan="8" class="py-10 text-center text-gray-400 font-body">Tidak ada pengajuan surat ditemukan.</td>
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
  </OperatorLayout>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
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
  },
  statusCounts: {
    type: Object,
    default: () => ({
      menunggu: 0,
      diproses: 0,
      menunggu_persetujuan: 0,
      disetujui: 0,
      selesai: 0,
      ditolak: 0
    })
  }
});

const activeFilters = reactive({
  status: props.filters.status || '',
  jenis_surat: props.filters.jenis_surat || '',
  search: props.filters.search || ''
});

const statCards = computed(() => [
  { label: 'Antrean', count: props.statusCounts.menunggu || 0, value: 'menunggu' },
  { label: 'Proses', count: props.statusCounts.diproses || 0, value: 'diproses' },
  { label: 'Waiting TTD', count: props.statusCounts.menunggu_persetujuan || 0, value: 'menunggu_persetujuan' },
  { label: 'Disetujui', count: props.statusCounts.disetujui || 0, value: 'disetujui' },
  { label: 'Selesai', count: props.statusCounts.selesai || 0, value: 'selesai' },
  { label: 'Ditolak', count: props.statusCounts.ditolak || 0, value: 'ditolak' }
]);

const statusOptions = [
  { value: 'menunggu', label: 'Antrean' },
  { value: 'diproses', label: 'Sedang Diproses' },
  { value: 'menunggu_persetujuan', label: 'Menunggu TTD Kades' },
  { value: 'disetujui', label: 'Disetujui Kades' },
  { value: 'selesai', label: 'Selesai & Dicetak' },
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
    menunggu: 'Antrean',
    diproses: 'Proses',
    menunggu_persetujuan: 'Waiting TTD',
    disetujui: 'Disetujui',
    selesai: 'Selesai',
    ditolak: 'Ditolak'
  };
  return map[status] || status;
};

const badgeVariant = (status) => {
  const map = {
    menunggu: 'abu',
    diproses: 'emas',
    menunggu_persetujuan: 'emas',
    disetujui: 'hijau',
    selesai: 'hijau',
    ditolak: 'merah'
  };
  return map[status] || 'abu';
};

const handleSearch = () => {
  router.get('/operator/surat', {
    status: activeFilters.status || undefined,
    jenis_surat: activeFilters.jenis_surat || undefined,
    search: activeFilters.search || undefined
  }, { preserveState: true });
};

const filterStatus = (val) => {
  activeFilters.status = val;
  handleSearch();
};
</script>
