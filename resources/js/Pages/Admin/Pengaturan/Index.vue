<template>
  <AdminLayout>
    <Head title="Pengaturan TTD & Cap - Panel Admin" />

    <div class="space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Pengaturan Tanda Tangan & Cap Desa</h1>
        <p class="text-xs text-gray-500 font-body">Unggah tanda tangan Kepala Desa dan cap stempel resmi dalam format PNG transparan.</p>
      </div>

      <!-- Alert Status Kelengkapan -->
      <AppAlert v-if="alertMessage" :type="alertType" @close="alertMessage = ''">
        {{ alertMessage }}
      </AppAlert>

      <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row gap-8 items-start">
        
        <!-- Form Left: Edit Details & Upload files -->
        <form @submit.prevent="handleSubmit" class="flex-1 space-y-6 w-full">
          
          <AppInput
            id="nama_kepala_desa"
            label="Nama Lengkap Kepala Desa"
            v-model="form.nama_kepala_desa"
            placeholder="Masukkan nama lengkap beserta gelar"
            required
            :error="form.errors.nama_kepala_desa"
          />

          <AppInput
            id="nip_kepala_desa"
            label="NIP Kepala Desa (PNS)"
            v-model="form.nip_kepala_desa"
            placeholder="Masukkan NIP resmi"
            required
            :error="form.errors.nip_kepala_desa"
          />

          <!-- Upload TTD -->
          <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Tanda Tangan Digital (PNG Transparan):</label>
            <div class="flex items-center gap-4">
              <!-- Upload Input -->
              <input
                type="file"
                accept="image/png"
                @change="handleTtdUpload"
                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2D5016]/10 file:text-[#2D5016] hover:file:bg-[#2D5016]/20 cursor-pointer"
              />
            </div>
            <p v-if="form.errors.ttd_kepala_desa" class="text-xs font-semibold text-red-600">
              {{ form.errors.ttd_kepala_desa }}
            </p>
          </div>

          <!-- Upload Cap -->
          <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Cap Stempel Desa (PNG Transparan):</label>
            <div class="flex items-center gap-4">
              <!-- Upload Input -->
              <input
                type="file"
                accept="image/png"
                @change="handleCapUpload"
                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2D5016]/10 file:text-[#2D5016] hover:file:bg-[#2D5016]/20 cursor-pointer"
              />
            </div>
            <p v-if="form.errors.cap_desa" class="text-xs font-semibold text-red-600">
              {{ form.errors.cap_desa }}
            </p>
          </div>

          <!-- Guidelines -->
          <div class="bg-gray-50 rounded-2xl p-4 border border-gray-150 text-[11px] text-gray-500 font-body leading-relaxed">
            <strong>Tips Legalitas Dokumen:</strong>
            <ul class="list-disc pl-4 space-y-1 mt-1.5">
              <li>Pastikan file gambar TTD & Cap berlatar belakang <strong>transparan</strong> (format berkas <strong>PNG</strong>).</li>
              <li>Anda dapat menggunakan website pembersih background online seperti <a href="https://remove.bg" target="_blank" class="text-[#C8952A] underline font-bold">remove.bg</a> untuk membuat transparan latar belakang.</li>
              <li>Ukuran maksimum berkas gambar dibatasi **2MB**.</li>
            </ul>
          </div>

          <div class="pt-2">
            <AppButton type="submit" variant="primary" :loading="form.processing" customClass="w-full">
              Simpan Perubahan Pengaturan
            </AppButton>
          </div>

        </form>

        <!-- Right Side: Realtime overlapping preview simulating surat output -->
        <div class="w-full md:w-80 shrink-0 bg-[#FAF9F5] border border-gray-100 rounded-3xl p-6 shadow-inner space-y-4">
          <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Tinjauan Area TTD Surat</span>
          
          <div class="border border-[#C8952A]/20 bg-white rounded-2xl p-6 text-center text-xs space-y-1 relative select-none">
            <span class="block text-gray-500">Kemang, {{ todayDate }}</span>
            <span class="block font-bold text-gray-800">Kepala Desa Kemang</span>
            
            <!-- Tumpuk box TTD & Cap -->
            <div class="h-32 w-full relative flex items-center justify-center">
              
              <!-- Stempel (Di belakang) -->
              <div v-if="capPreviewUrl || logoPreviews.cap" class="absolute h-28 w-28 object-contain opacity-80 z-10">
                <img :src="capPreviewUrl || logoPreviews.cap" class="h-full w-full object-contain" />
                <!-- Delete X button -->
                <button
                  type="button"
                  class="absolute top-0 right-0 h-4 w-4 bg-red-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow hover:bg-red-700 cursor-pointer"
                  @click="deleteFile('cap_desa')"
                >
                  &times;
                </button>
              </div>

              <!-- TTD (Di depan) -->
              <div v-if="ttdPreviewUrl || logoPreviews.ttd" class="absolute h-24 w-40 object-contain z-20">
                <img :src="ttdPreviewUrl || logoPreviews.ttd" class="h-full w-full object-contain" />
                <!-- Delete X button -->
                <button
                  type="button"
                  class="absolute top-0 right-0 h-4 w-4 bg-red-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow hover:bg-red-700 cursor-pointer"
                  @click="deleteFile('ttd_kepala_desa')"
                >
                  &times;
                </button>
              </div>

              <span v-if="!ttdPreviewUrl && !logoPreviews.ttd && !capPreviewUrl && !logoPreviews.cap" class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold border border-dashed border-gray-300 p-4 rounded">
                TTD & Cap Kosong
              </span>

            </div>

            <span class="block font-bold text-gray-800 underline">{{ form.nama_kepala_desa || 'Nama Kepala Desa' }}</span>
            <span class="block text-[10px] text-gray-500">NIP: {{ form.nip_kepala_desa || '19700101XXXXXXXX' }}</span>
          </div>

          <!-- Status badge indicator -->
          <div
            class="p-4 rounded-xl text-center text-xs font-semibold"
            :class="isCompleted ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-[#C8952A] border border-[#C8952A]/20'"
          >
            {{ isCompleted ? 'Kelengkapan Legalisasi: Lengkap' : 'Kelengkapan Legalisasi: Belum Lengkap' }}
          </div>
        </div>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppAlert from '@/Components/UI/AppAlert.vue';

const props = defineProps({
  pengaturan: {
    type: Object,
    default: () => ({ nama_kepala_desa: '', nip_kepala_desa: '', ttd_kepala_desa: null, cap_desa: null })
  }
});

const alertMessage = ref('');
const alertType = ref('success');

const form = useForm({
  nama_kepala_desa: props.pengaturan.nama_kepala_desa || '',
  nip_kepala_desa: props.pengaturan.nip_kepala_desa || '',
  ttd_kepala_desa: null,
  cap_desa: null
});

// Save read urls for display previews
const logoPreviews = ref({
  ttd: props.pengaturan.ttd_kepala_desa || null,
  cap: props.pengaturan.cap_desa || null
});

const ttdPreviewUrl = ref('');
const capPreviewUrl = ref('');

const isCompleted = computed(() => {
  const ttdExists = !!logoPreviews.value.ttd || !!ttdPreviewUrl.value;
  const capExists = !!logoPreviews.value.cap || !!capPreviewUrl.value;
  return !!form.nama_kepala_desa && !!form.nip_kepala_desa && ttdExists && capExists;
});

const todayDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

const handleTtdUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  form.ttd_kepala_desa = file;
  ttdPreviewUrl.value = URL.createObjectURL(file);
};

const handleCapUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  form.cap_desa = file;
  capPreviewUrl.value = URL.createObjectURL(file);
};

const deleteFile = (kunci) => {
  if (confirm('Apakah Anda yakin ingin menghapus berkas legalisasi digital ini?')) {
    router.delete(`/admin/pengaturan/file/${kunci}`, {
      preserveScroll: true,
      onSuccess: () => {
        if (kunci === 'ttd_kepala_desa') {
          logoPreviews.value.ttd = null;
          ttdPreviewUrl.value = '';
          form.ttd_kepala_desa = null;
        } else {
          logoPreviews.value.cap = null;
          capPreviewUrl.value = '';
          form.cap_desa = null;
        }
        alertMessage.value = 'Berkas legalisasi berhasil dihapus.';
        alertType.value = 'success';
      }
    });
  }
};

const handleSubmit = () => {
  // Post using _method: POST to support multipart file upload
  form.post('/admin/pengaturan', {
    preserveScroll: true,
    onSuccess: () => {
      alertMessage.value = 'Data pengaturan kades berhasil diperbarui!';
      alertType.value = 'success';
      // Clear inputs
      form.ttd_kepala_desa = null;
      form.cap_desa = null;
    },
    onError: () => {
      alertMessage.value = 'Terjadi kesalahan saat memperbarui pengaturan.';
      alertType.value = 'error';
    }
  });
};
</script>
