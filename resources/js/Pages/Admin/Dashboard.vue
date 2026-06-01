<template>
  <AdminLayout>
    <Head title="Dashboard Admin - E-Government Desa Kemang" />

    <div class="space-y-8 font-ui">
      <!-- Warning Banner Emas jika TTD atau Cap belum diupload -->
      <div
        v-if="ttdCapBelumLengkap"
        class="bg-[#F5EDD8] border-l-8 border-[#C8952A] rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shrink-0 text-gray-800"
      >
        <div class="flex gap-3 items-center">
          <svg class="h-6 w-6 text-[#C8952A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div class="space-y-0.5">
            <h4 class="text-sm font-bold">Tanda Tangan Digital atau Cap Stempel Belum Lengkap!</h4>
            <p class="text-xs text-gray-500 font-body">Anda tidak dapat memberikan persetujuan (approval) surat warga sebelum melengkapi TTD & Cap.</p>
          </div>
        </div>
        <Link href="/admin/pengaturan" class="shrink-0">
          <AppButton variant="secondary" size="sm">Lengkapi Sekarang</AppButton>
        </Link>
      </div>

      <!-- Welcome Info -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-center sm:text-left">
          <h1 class="text-xl md:text-2xl font-serif font-bold text-[#2D5016]">
            Selamat Datang, {{ authUser.nama }}!
          </h1>
          <p class="text-xs text-gray-500 font-body">Panel Penandatanganan & Validasi Surat Digital Desa Kemang.</p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-[#2D5016]/10 text-[#2D5016] uppercase tracking-widest shrink-0 select-none">
          Kepala Desa
        </span>
      </div>

      <!-- 4 Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 shrink-0">
        <Link
          v-for="stat in statCards"
          :key="stat.label"
          :href="stat.href"
          class="block bg-white border border-gray-100 p-6 rounded-2xl shadow-sm space-y-2 hover:border-[#C8952A]/40 transition-colors"
        >
          <span class="block text-xs font-bold text-gray-400 uppercase tracking-wide">{{ stat.label }}</span>
          <span
            :class="[
              'block text-2xl md:text-3xl font-bold font-serif',
              stat.highlight && stat.value > 0 ? 'text-[#C8952A] animate-pulse' : 'text-[#2D5016]'
            ]"
          >
            {{ stat.value }}
          </span>
        </Link>
      </div>

      <!-- Access Shortcuts -->
      <div class="space-y-4 shrink-0">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Akses Cepat Menu</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <Link v-for="link in quickLinks" :key="link.href" :href="link.href" class="block">
            <div class="bg-[#F5EDD8]/20 hover:bg-[#ebdcb4]/30 border border-[#C8952A]/20 hover:border-[#C8952A]/40 rounded-2xl p-4 text-center transition-all duration-200 cursor-pointer space-y-2">
              <div class="h-10 w-10 bg-[#2D5016]/10 text-[#2D5016] rounded-xl flex items-center justify-center mx-auto">
                <component :is="link.icon" class="h-5 w-5" />
              </div>
              <span class="block text-[11px] font-bold text-[#2D5016] uppercase tracking-wider">{{ link.label }}</span>
            </div>
          </Link>
        </div>
      </div>

      <!-- Validations Table -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Antrean Surat Menunggu Approval -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4 overflow-hidden">
          <div class="flex justify-between items-center border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Antrean Surat Menunggu Validasi</h3>
            <Link href="/admin/validasi">
              <AppButton variant="ghost" size="sm">Semua Antrean</AppButton>
            </Link>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                  <th class="py-3 pr-2">Pemohon</th>
                  <th class="py-3 px-2">No. Ref</th>
                  <th class="py-3 px-2">Jenis</th>
                  <th class="py-3 pl-2 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                <tr v-for="item in antrean" :key="item.id" class="hover:bg-gray-50/50">
                  <td class="py-3.5 pr-2 font-bold">{{ item.nama_lengkap }}</td>
                  <td class="py-3.5 px-2 font-mono text-gray-500">{{ item.nomor_referensi }}</td>
                  <td class="py-3.5 px-2 uppercase text-[10px]">{{ item.jenis_surat }}</td>
                  <td class="py-3.5 pl-2 text-right">
                    <Link :href="'/admin/validasi/' + item.id">
                      <AppButton variant="secondary" size="sm" customClass="!py-1">Validasi</AppButton>
                    </Link>
                  </td>
                </tr>
                <tr v-if="antrean.length === 0">
                  <td colspan="4" class="py-10 text-center text-gray-400 font-body">Tidak ada berkas menunggu persetujuan.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Riwayat Validasi Terakhir -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4 overflow-hidden">
          <div class="flex justify-between items-center border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Validasi Kades</h3>
          </div>
          <div class="space-y-4">
            <div v-for="history in riwayat" :key="history.id" class="flex justify-between items-center text-xs">
              <div class="min-w-0 space-y-0.5">
                <span class="block font-bold text-gray-800 truncate">{{ history.nama_lengkap }}</span>
                <span class="block text-[10px] text-gray-400 font-mono">{{ history.nomor_referensi }}</span>
              </div>
              <AppBadge :variant="badgeVariant(history.status)" size="sm">
                {{ formatStatus(history.status) }}
              </AppBadge>
            </div>
            <div v-if="riwayat.length === 0" class="py-6 text-center text-gray-400 font-body">Belum ada riwayat validasi.</div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';

const props = defineProps({
  counts: {
    type: Object,
    default: () => ({ menunggu: 0, disetujui: 0, ditolak: 0, operator_active: 0 })
  },
  antrean: {
    type: Array,
    default: () => []
  },
  riwayat: {
    type: Array,
    default: () => []
  },
  ttdCapBelumLengkap: {
    type: Boolean,
    default: false
  }
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user || { nama: 'Kepala Desa', role: 'admin' });

const statCards = computed(() => [
  { label: 'Menunggu Validasi', value: props.counts.menunggu, href: '/admin/validasi', highlight: true },
  { label: 'Total Disetujui', value: props.counts.disetujui, href: '/admin/validasi?status=disetujui' },
  { label: 'Total Ditolak', value: props.counts.ditolak, href: '/admin/validasi?status=ditolak' },
  { label: 'Operator Aktif', value: props.counts.operator_active, href: '/admin/operator' }
]);

const formatStatus = (status) => {
  const map = {
    disetujui: 'Disetujui',
    ditolak: 'Ditolak',
    selesai: 'Selesai'
  };
  return map[status] || status;
};

const badgeVariant = (status) => {
  return status === 'ditolak' ? 'merah' : 'hijau';
};

const MailIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
};
const ChartIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>`
};
const KeyIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.24 9.17c.073.273.1.575.1.902a5.502 5.502 0 01-5.5 5.5H8.34l-3.4 3.4a1 1 0 01-.707.293H3.018a1 1 0 01-1-1v-1.215a1 1 0 01.293-.707l3.4-3.4V10.08a5.502 5.502 0 015.5-5.5h.334a5.502 5.502 0 015.5 5.5v.002zM12 8a1 1 0 100-2 1 1 0 000 2z"/></svg>`
};
const UsersIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>`
};

const quickLinks = [
  { label: 'Validasi Surat', href: '/admin/validasi', icon: MailIcon },
  { label: 'Kelola Statistik', href: '/admin/statistik', icon: ChartIcon },
  { label: 'Pengaturan TTD', href: '/admin/pengaturan', icon: KeyIcon },
  { label: 'Kelola Operator', href: '/admin/operator', icon: UsersIcon }
];
</script>
