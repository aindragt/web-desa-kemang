<template>
  <OperatorLayout>
    <Head title="Dashboard Operator - E-Government Desa Kemang" />

    <div class="space-y-8 font-ui">
      <!-- Welcome Banner -->
      <div class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] rounded-3xl p-6 md:p-8 text-white border-b-4 border-[#C8952A] shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <div class="space-y-1">
          <h1 class="text-xl md:text-2xl font-serif font-bold text-amber-100">
            Selamat Bekerja, {{ authUser.nama }}!
          </h1>
          <p class="text-xs text-amber-50/70 font-body">Sistem E-Demografi & Pelayanan Surat Mandiri Online Desa Kemang.</p>
        </div>
        <div class="bg-black/20 px-4 py-2 rounded-xl text-xs font-semibold text-amber-200 shrink-0 self-start md:self-auto uppercase tracking-wider">
          {{ todayDate }}
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 shrink-0">
        <div v-for="stat in statCards" :key="stat.label" class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm space-y-2">
          <span class="block text-xs font-bold text-gray-400 uppercase tracking-wide">{{ stat.label }}</span>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl md:text-3xl font-bold text-[#2D5016] font-serif">{{ stat.value }}</span>
            <span v-if="stat.unit" class="text-xs text-gray-400 font-semibold">{{ stat.unit }}</span>
          </div>
        </div>
      </div>

      <!-- Quick Access Links Grid -->
      <div class="space-y-4 shrink-0">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Akses Cepat Menu</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
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

      <!-- Tables Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Surat Terakhir -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4 overflow-hidden">
          <div class="flex justify-between items-center border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Pengajuan Surat Terbaru</h3>
            <Link href="/operator/surat">
              <AppButton variant="ghost" size="sm">Semua Surat</AppButton>
            </Link>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                  <th class="py-3 pr-2">Pemohon</th>
                  <th class="py-3 px-2">No. Ref</th>
                  <th class="py-3 px-2">Jenis</th>
                  <th class="py-3 pl-2 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50 text-gray-700">
                <tr v-for="item in pengajuan" :key="item.id" class="hover:bg-gray-50/50">
                  <td class="py-3.5 pr-2 font-bold">{{ item.nama_lengkap }}</td>
                  <td class="py-3.5 px-2 font-mono text-gray-500">{{ item.nomor_referensi }}</td>
                  <td class="py-3.5 px-2 uppercase text-[10px]">{{ item.jenis_surat }}</td>
                  <td class="py-3.5 pl-2 text-right">
                    <AppBadge :variant="badgeVariant(item.status)" size="sm">
                      {{ formatStatus(item.status) }}
                    </AppBadge>
                  </td>
                </tr>
                <tr v-if="pengajuan.length === 0">
                  <td colspan="4" class="py-6 text-center text-gray-400 font-body">Tidak ada pengajuan surat terbaru.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Berita Terakhir -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4 overflow-hidden">
          <div class="flex justify-between items-center border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Kabar Berita</h3>
            <Link href="/operator/berita">
              <AppButton variant="ghost" size="sm">Semua</AppButton>
            </Link>
          </div>
          <div class="space-y-4">
            <div v-for="news in berita" :key="news.id" class="flex items-center gap-3 text-xs">
              <div class="h-10 w-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                <img :src="news.foto_utama ? '/storage/' + news.foto_utama : '/storage/default_berita.png'" class="w-full h-full object-cover" />
              </div>
              <div class="flex-1 min-w-0">
                <Link :href="'/operator/berita/' + news.id + '/edit'" class="block font-bold text-gray-800 hover:text-[#2D5016] truncate">
                  {{ news.judul }}
                </Link>
                <div class="flex items-center gap-2 mt-0.5 text-[10px] font-semibold text-gray-400">
                  <span class="uppercase">{{ news.kategori }}</span>
                  <span>&bull;</span>
                  <span>{{ news.foto_berita_count || 0 }} Foto</span>
                </div>
              </div>
              <AppBadge :variant="news.is_published ? 'hijau' : 'abu'" size="sm">
                {{ news.is_published ? 'Tayang' : 'Draft' }}
              </AppBadge>
            </div>
            <div v-if="berita.length === 0" class="py-6 text-center text-gray-400 font-body">Tidak ada berita terbaru.</div>
          </div>
        </div>
      </div>

      <!-- Pesan Belum Dibaca -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4 overflow-hidden">
        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
          <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Pesan Kontak Belum Dibaca</h3>
          <Link href="/operator/pesan">
            <AppButton variant="ghost" size="sm">Semua Pesan</AppButton>
          </Link>
        </div>
        <div class="divide-y divide-gray-50">
          <div v-for="msg in pesan" :key="msg.id" class="py-3 flex justify-between items-center gap-4 text-xs font-ui">
            <div class="min-w-0 space-y-0.5">
              <span class="block font-bold text-gray-800 truncate">{{ msg.nama }}</span>
              <p class="text-gray-500 font-body truncate leading-relaxed">{{ msg.pesan }}</p>
            </div>
            <span class="block text-[10px] text-gray-400 shrink-0 font-semibold">
              {{ new Date(msg.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}
            </span>
          </div>
          <div v-if="pesan.length === 0" class="py-6 text-center text-gray-400 font-body">Tidak ada pesan belum dibaca.</div>
        </div>
      </div>
    </div>
  </OperatorLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';

const props = defineProps({
  counts: {
    type: Object,
    default: () => ({ berita: 0, surat_pending: 0, surat_waiting_admin: 0, pesan_unread: 0 })
  },
  pengajuan: {
    type: Array,
    default: () => []
  },
  berita: {
    type: Array,
    default: () => []
  },
  pesan: {
    type: Array,
    default: () => []
  }
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user || { nama: 'Staf Operator', role: 'operator' });

const todayDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

const statCards = computed(() => [
  { label: 'Total Berita', value: props.counts.berita, unit: 'Artikel' },
  { label: 'Surat Pending', value: props.counts.surat_pending, unit: 'Berkas' },
  { label: 'Menunggu TTD Kades', value: props.counts.surat_waiting_admin, unit: 'Berkas' },
  { label: 'Pesan Baru', value: props.counts.pesan_unread, unit: 'Pesan' }
]);

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

const EditIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>`
};
const MailIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
};
const ChartIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>`
};
const ChatIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>`
};
const PrintIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>`
};

const quickLinks = [
  { label: 'Tulis Berita', href: '/operator/berita/tambah', icon: EditIcon },
  { label: 'Kelola Surat', href: '/operator/surat', icon: MailIcon },
  { label: 'Statistik', href: '/operator/statistik', icon: ChartIcon },
  { label: 'Pesan Masuk', href: '/operator/pesan', icon: ChatIcon },
  { label: 'Siap Cetak', href: '/operator/surat?status=disetujui', icon: PrintIcon }
];
</script>
