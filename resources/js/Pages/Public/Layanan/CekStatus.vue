<template>
  <PublicLayout>
    <Head title="Cek Status Pengajuan Surat - E-Government Desa Kemang" />

    <!-- Cek Status Section -->
    <section class="py-12 bg-white font-ui">
      <div class="max-w-xl mx-auto px-4 sm:px-6">
        
        <!-- Input Kode Referensi Form -->
        <div class="bg-[#F5EDD8]/20 border border-[#C8952A]/20 rounded-3xl p-6 md:p-8 space-y-6">
          <div class="space-y-2 text-center">
            <h1 class="text-xl font-serif font-bold text-[#2D5016]">Cek Progres Persetujuan Surat</h1>
            <p class="text-xs text-gray-500 font-body">Masukkan nomor referensi unik slip tanda terima untuk melihat status real-time surat Anda.</p>
          </div>

          <form @submit.prevent="handleSearch" class="space-y-4">
            <AppInput
              id="nomor_referensi"
              label="Nomor Referensi Surat"
              v-model="searchQuery"
              placeholder="Contoh: SR-KMG-20260527-0001"
              required
            />
            <AppButton type="submit" variant="primary" customClass="w-full" :loading="loading">
              Lacak Pengajuan
            </AppButton>
          </form>
        </div>

        <!-- Result / Tracking Progress Bar -->
        <Transition
          enter-active-class="transition ease-out duration-300 transform"
          enter-from-class="opacity-0 translate-y-4"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-200 transform"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-4"
        >
          <div v-if="searchExecuted" class="mt-10 space-y-8 bg-white border border-gray-100 shadow-lg rounded-3xl p-6 md:p-8">
            
            <!-- Found / Status detail -->
            <div v-if="surat" class="space-y-8">
              <div class="flex justify-between items-start gap-4 border-b border-gray-100 pb-4">
                <div class="space-y-1">
                  <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nomor Referensi</span>
                  <span class="block text-sm font-bold text-gray-800">{{ surat.nomor_referensi }}</span>
                </div>
                <div class="space-y-1 text-right">
                  <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pemohon</span>
                  <span class="block text-sm font-bold text-gray-800">{{ surat.nama_lengkap }}</span>
                </div>
              </div>

              <!-- Tracking Progress Bar -->
              <div class="space-y-4">
                <span class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Status Saat Ini: {{ formatStatus(surat.status) }}</span>
                <div class="relative">
                  <!-- Gray bar -->
                  <div class="h-2 bg-gray-100 w-full rounded-full" />
                  <!-- Green colored bar -->
                  <div
                    class="h-2 bg-[#2D5016] absolute top-0 left-0 rounded-full transition-all duration-500"
                    :style="{ width: getProgressPercent(surat.status) + '%' }"
                  />
                  <!-- Dots -->
                  <div class="flex justify-between mt-3 text-[10px] font-semibold text-gray-400">
                    <span :class="{ 'text-[#2D5016] font-bold': isActiveStep('menunggu', surat.status) }">Diajukan</span>
                    <span :class="{ 'text-[#2D5016] font-bold': isActiveStep('diproses', surat.status) }">Diproses</span>
                    <span :class="{ 'text-[#2D5016] font-bold': isActiveStep('disetujui', surat.status) }">Disetujui</span>
                    <span :class="{ 'text-[#2D5016] font-bold': isActiveStep('selesai', surat.status) }">Selesai (Cetak)</span>
                  </div>
                </div>
              </div>

              <!-- Rejection Notice -->
              <div v-if="surat.status === 'ditolak'" class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-xs font-body leading-relaxed">
                <strong>Catatan Admin / Penolakan:</strong> {{ surat.catatan_admin || 'Berkas kelengkapan pengajuan tidak lengkap.' }}
              </div>

              <!-- Download Button (Visible only if disetujui / selesai) -->
              <div v-if="['disetujui', 'selesai'].includes(surat.status)" class="pt-4">
                <a :href="'/layanan-surat/' + surat.nomor_referensi + '/slip'" target="_blank" class="block">
                  <AppButton variant="secondary" size="md" customClass="w-full">
                    Unduh Salinan Dokumen Slip
                  </AppButton>
                </a>
              </div>
            </div>

            <!-- Not Found State -->
            <div v-else class="text-center py-6 space-y-3">
              <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <h3 class="text-sm font-bold text-gray-800">Nomor Referensi Tidak Ditemukan</h3>
              <p class="text-xs text-gray-500 font-body">Silakan periksa kembali ketikan huruf besar/kecil nomor referensi slip Anda.</p>
            </div>

          </div>
        </Transition>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';

const props = defineProps({
  surat: {
    type: Object,
    default: null
  },
  refQuery: {
    type: String,
    default: ''
  }
});

const searchQuery = ref(props.refQuery);
const loading = ref(false);
const searchExecuted = ref(!!props.surat || !!props.refQuery);

const handleSearch = () => {
  if (!searchQuery.value) return;
  loading.value = true;
  router.get('/layanan-surat/status', {
    ref: searchQuery.value
  }, {
    preserveState: true,
    onFinish: () => {
      loading.value = false;
      searchExecuted.value = true;
    }
  });
};

const formatStatus = (status) => {
  const map = {
    menunggu: 'Menunggu Antrean',
    diproses: 'Sedang Diproses Operator',
    menunggu_persetujuan: 'Menunggu TTD Kades',
    disetujui: 'Disetujui Kades (Siap Cetak)',
    selesai: 'Selesai & Dicetak',
    ditolak: 'Ditolak / Dikembalikan'
  };
  return map[status] || status;
};

const getProgressPercent = (status) => {
  const map = {
    menunggu: 15,
    diproses: 50,
    menunggu_persetujuan: 50,
    disetujui: 80,
    selesai: 100,
    ditolak: 100
  };
  return map[status] || 0;
};

const isActiveStep = (step, currentStatus) => {
  const stepsOrder = ['menunggu', 'diproses', 'menunggu_persetujuan', 'disetujui', 'selesai'];
  const currentIdx = stepsOrder.indexOf(currentStatus);
  const stepIdx = stepsOrder.indexOf(step);
  return stepIdx <= currentIdx;
};
</script>
