<template>
  <OperatorLayout>
    <Head :title="'Detail Surat ' + surat.nomor_referensi + ' - Panel Operator'" />

    <div class="max-w-2xl mx-auto space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between shrink-0">
        <div class="space-y-1">
          <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pelacakan Berkas</span>
          <h1 class="text-sm font-bold text-gray-800 font-mono">{{ surat.nomor_referensi }}</h1>
        </div>
        <AppBadge :variant="badgeVariant(surat.status)" size="md">
          {{ formatStatus(surat.status) }}
        </AppBadge>
      </div>

      <!-- Detail Warga Card -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">Data Pemohon Surat</h3>
        
        <div class="grid grid-cols-2 gap-y-4 text-xs font-medium">
          <div class="space-y-0.5">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nama Lengkap:</span>
            <span class="block text-gray-800">{{ surat.nama_lengkap }}</span>
          </div>
          <div class="space-y-0.5">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">NIK (Nomor Induk):</span>
            <span class="block text-gray-800 font-mono">{{ surat.nik }}</span>
          </div>
          <div class="space-y-0.5 col-span-2">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nomor Kontak / WhatsApp:</span>
            <span class="block text-gray-800">{{ surat.kontak }}</span>
          </div>
          <div class="space-y-0.5 col-span-2">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Maksud & Keperluan:</span>
            <span class="block text-gray-700 leading-relaxed font-body">{{ surat.keperluan }}</span>
          </div>

          <!-- Dynamic: SKU Fields -->
          <div v-if="surat.jenis_surat === 'usaha'" class="col-span-2 grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 mt-2">
            <div class="space-y-0.5">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nama Usaha:</span>
              <span class="block text-[#2D5016] font-bold">{{ surat.nama_usaha }}</span>
            </div>
            <div class="space-y-0.5">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Jenis Usaha:</span>
              <span class="block text-[#2D5016] font-bold">{{ surat.jenis_usaha }}</span>
            </div>
          </div>
        </div>

        <!-- WhatsApp Button shortcut -->
        <div class="pt-4 border-t border-gray-50 flex gap-3">
          <a
            :href="'https://wa.me/' + formatWhatsApp(surat.kontak)"
            target="_blank"
            class="flex-1"
          >
            <AppButton variant="ghost" size="md" customClass="w-full !bg-emerald-50 hover:!bg-emerald-100 !text-emerald-700 !border-emerald-200">
              Hubungi via WhatsApp
            </AppButton>
          </a>
          
          <a
            v-if="['disetujui', 'selesai'].includes(surat.status)"
            :href="'/operator/surat/' + surat.id + '/cetak'"
            class="flex-1"
          >
            <AppButton variant="secondary" size="md" customClass="w-full">
              Cetak Dokumen Surat
            </AppButton>
          </a>
        </div>
      </div>

      <!-- Action Card: Update status (TIDAK BISA SET disetujui / selesai) -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">Perbarui Status Proses</h3>
        
        <form @submit.prevent="handleUpdateStatus" class="space-y-6">
          <AppSelect
            id="status"
            label="Pilih Tahapan Progres"
            v-model="form.status"
            placeholder="Pilih Status"
            :options="[
              { value: 'menunggu', label: 'Antrean (Menunggu verifikasi)' },
              { value: 'diproses', label: 'Sedang Diproses (Pembuatan berkas)' },
              { value: 'menunggu_persetujuan', label: 'Ajukan TTD & Cap Kepala Desa' },
              { value: 'ditolak', label: 'Tolak / Kembalikan berkas' }
            ]"
            required
          />

          <AppTextarea
            id="catatan_admin"
            label="Catatan / Alasan Perubahan Status"
            v-model="form.catatan_admin"
            placeholder="Tuliskan catatan perbaikan berkas jika status ditolak..."
            :rows="3"
          />

          <div class="flex gap-3">
            <AppButton type="submit" variant="primary" :loading="form.processing">
              Simpan Progres
            </AppButton>
            <Link href="/operator/surat">
              <AppButton variant="ghost">Kembali</AppButton>
            </Link>
          </div>
        </form>
      </div>
    </div>
  </OperatorLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppSelect from '@/Components/UI/AppSelect.vue';
import AppTextarea from '@/Components/UI/AppTextarea.vue';

const props = defineProps({
  surat: {
    type: Object,
    required: true
  }
});

const form = useForm({
  status: props.surat.status,
  catatan_admin: props.surat.catatan_admin || ''
});

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

const formatWhatsApp = (num) => {
  let cleaned = num.replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};

const handleUpdateStatus = () => {
  form.patch(`/operator/surat/${props.surat.id}/status`, {
    preserveScroll: true
  });
};
</script>
