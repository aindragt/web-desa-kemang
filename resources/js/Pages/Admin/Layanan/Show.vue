<template>
  <AdminLayout>
    <Head :title="'Validasi Surat ' + surat.nomor_referensi + ' - Panel Admin'" />

    <div class="space-y-8 font-ui">
      <!-- Top Actions Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between shrink-0">
        <div class="space-y-1">
          <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Permohonan Validasi Berkas</span>
          <h1 class="text-sm font-bold text-gray-800 font-mono">{{ surat.nomor_referensi }}</h1>
        </div>
        <AppBadge :variant="badgeVariant(surat.status)" size="md">
          {{ formatStatus(surat.status) }}
        </AppBadge>
      </div>

      <!-- Main Layout Body (Detail & Sticky Sidebar Action panel) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Pemohon Read-Only Info -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
          <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">Data Diri Pemohon (Read-Only)</h3>
          
          <div class="grid grid-cols-2 gap-y-4 text-xs font-semibold">
            <div class="space-y-0.5">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nama Lengkap:</span>
              <span class="block text-gray-800">{{ surat.nama_lengkap }}</span>
            </div>
            <div class="space-y-0.5">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">NIK (Kependudukan):</span>
              <span class="block text-gray-800 font-mono">{{ surat.nik }}</span>
            </div>
            <div class="space-y-0.5 col-span-2">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nomor Telepon / WhatsApp:</span>
              <span class="block text-gray-800 font-mono">{{ surat.kontak }}</span>
            </div>
            <div class="space-y-0.5 col-span-2">
              <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Maksud Keperluan:</span>
              <span class="block text-gray-700 leading-relaxed font-body">{{ surat.keperluan }}</span>
            </div>

            <!-- Dynamic SKU info -->
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
        </div>

        <!-- Right Side: Sticky Action Panel -->
        <div class="space-y-6">
          <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-6 lg:sticky lg:top-24">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">Aksi Validasi Surat</h3>

            <!-- JIKA status menunggu_persetujuan (Antrean approval) -->
            <div v-if="surat.status === 'menunggu_persetujuan'" class="space-y-6">
              
              <!-- 1. Setujui & TTD -->
              <div class="space-y-2">
                <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Aksi Penandatanganan Resmi:</span>
                <AppButton variant="primary" size="md" customClass="w-full" @click="showApproveModal = true">
                  Setujui & Tanda Tangani
                </AppButton>
              </div>

              <!-- 2. Kembalikan ke Operator -->
              <div class="space-y-2 pt-4 border-t border-gray-100">
                <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kembalikan Untuk Direvisi:</span>
                <AppButton variant="ghost" size="md" customClass="w-full" @click="showReturnModal = true">
                  Kembalikan ke Operator
                </AppButton>
              </div>

              <!-- 3. Tolak Form (Required Alasan) -->
              <form @submit.prevent="handleReject" class="space-y-3 pt-4 border-t border-gray-100">
                <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tolak Permohonan:</span>
                <AppTextarea
                  id="catatan_admin"
                  v-model="rejectForm.catatan_admin"
                  placeholder="Sebutkan alasan penolakan secara jelas (wajib)..."
                  :rows="3"
                  required
                  :error="rejectForm.errors.catatan_admin"
                />
                <AppButton type="submit" variant="danger" size="md" customClass="w-full" :loading="rejectForm.processing">
                  Tolak Pengajuan Surat
                </AppButton>
              </form>

            </div>

            <!-- JIKA sudah disetujui -->
            <div v-else-if="['disetujui', 'selesai'].includes(surat.status)" class="space-y-3 text-xs">
              <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200 leading-normal space-y-1 font-body">
                <strong>Dokumen Telah Disetujui!</strong>
                <span class="block text-[10px] font-semibold text-emerald-600 font-sans uppercase tracking-wider mt-2">Disetujui oleh:</span>
                <span class="block font-sans font-bold text-gray-800">{{ surat.validator?.nama || 'Kepala Desa' }}</span>
                <span class="block text-[10px] font-semibold text-emerald-600 font-sans uppercase tracking-wider mt-1">Pada Tanggal:</span>
                <span class="block font-sans text-gray-800">{{ new Date(surat.disetujui_at).toLocaleString('id-ID') }}</span>
              </div>
            </div>

            <!-- JIKA status lain (Ditolak / Diproses) -->
            <div v-else class="space-y-3 text-xs">
              <div class="bg-gray-100 text-gray-700 p-4 rounded-xl border border-gray-200 leading-normal font-body">
                <strong>Status Terkini Berkas:</strong>
                <p class="mt-2 font-sans font-bold text-gray-800 uppercase tracking-widest text-[11px]">{{ formatStatus(surat.status) }}</p>
                <div v-if="surat.catatan_admin" class="mt-3 pt-2 border-t border-gray-200/50 text-[11px]">
                  <strong>Catatan:</strong> {{ surat.catatan_admin }}
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Confirm Approve Modal -->
    <AppModal
      :show="showApproveModal"
      title="Setujui & Tanda Tangani Surat"
      message="Apakah Anda yakin ingin memberikan persetujuan resmi? Sistem akan menyematkan Tanda Tangan Digital & Cap Desa transparan Anda secara otomatis ke berkas slip surat ini."
      confirmText="Ya, Setujui"
      cancelText="Batal"
      :loading="approving"
      @close="showApproveModal = false"
      @confirm="executeApprove"
    />

    <!-- Confirm Return Modal -->
    <AppModal
      :show="showReturnModal"
      title="Kembalikan Berkas Surat"
      message="Apakah Anda yakin ingin mengembalikan berkas pengajuan ini ke Operator staf desa untuk direvisi kembali?"
      confirmText="Ya, Kembalikan"
      cancelText="Batal"
      :loading="returning"
      @close="showReturnModal = false"
      @confirm="executeReturn"
    />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/AdminLayout'; // fallback alias mapping if any
import AdminLayoutReal from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppTextarea from '@/Components/UI/AppTextarea.vue';
import AppModal from '@/Components/UI/AppModal.vue';

// Handle dynamic imports mapping or manual override
const AdminLayoutComponent = AdminLayoutReal;

const props = defineProps({
  surat: {
    type: Object,
    required: true
  }
});

const showApproveModal = ref(false);
const showReturnModal = ref(false);

const approving = ref(false);
const returning = ref(false);

const rejectForm = useForm({
  catatan_admin: ''
});

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

const executeApprove = () => {
  approving.value = true;
  router.put(`/admin/validasi/${props.surat.id}/setujui`, {}, {
    preserveScroll: true,
    onFinish: () => {
      approving.value = false;
      showApproveModal.value = false;
    }
  });
};

const executeReturn = () => {
  returning.value = true;
  router.put(`/admin/validasi/${props.surat.id}/kembalikan`, {}, {
    preserveScroll: true,
    onFinish: () => {
      returning.value = false;
      showReturnModal.value = false;
    }
  });
};

const handleReject = () => {
  rejectForm.put(`/admin/validasi/${props.surat.id}/tolak`, {
    preserveScroll: true
  });
};
</script>
