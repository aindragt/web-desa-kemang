<template>
  <OperatorLayout>
    <Head :title="isEdit ? 'Ubah Berita Desa - Panel Operator' : 'Tulis Berita Desa - Panel Operator'" />

    <div class="max-w-3xl mx-auto space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">
          {{ isEdit ? 'Ubah Berita Desa' : 'Tulis Kabar Berita Baru' }}
        </h1>
        <p class="text-xs text-gray-500 font-body">Lengkapi judul, kategori, konten artikel, serta foto dokumentasi pendukung.</p>
      </div>

      <!-- Form Card -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          
          <AppInput
            id="judul"
            label="Judul Berita"
            v-model="form.judul"
            placeholder="Masukkan judul berita utama..."
            required
            :error="form.errors.judul"
          />

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <AppSelect
              id="kategori"
              label="Kategori Berita"
              v-model="form.kategori"
              placeholder="Pilih Kategori"
              :options="categoryOptions"
              required
              :error="form.errors.kategori"
            />

            <AppInput
              id="penulis"
              label="Nama Penulis / Jurnalis"
              v-model="form.penulis"
              placeholder="Contoh: Admin Desa, Humas"
              required
              :error="form.errors.penulis"
            />
          </div>

          <!-- Quill Editor Simulation (Normal textarea for stable, reliable standard HTML content updates) -->
          <div class="space-y-1.5">
            <label for="isi" class="block text-sm font-semibold text-gray-700 font-ui">
              Isi Konten Berita (Format HTML)
            </label>
            <textarea
              id="isi"
              v-model="form.isi"
              rows="12"
              placeholder="Tuliskan isi berita lengkap di sini (mendukung tag HTML p, br, strong, dsb)..."
              class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D5016]/20 focus:border-[#2D5016] placeholder-gray-400 resize-y font-body"
              required
            ></textarea>
            <p v-if="form.errors.isi" class="text-xs font-semibold text-red-600">
              {{ form.errors.isi }}
            </p>
          </div>

          <!-- Multiple Photos Section -->
          <div class="space-y-6 pt-6 border-t border-gray-100 shrink-0">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Dokumentasi & Galeri Foto</h3>

            <!-- Existing Photos (Only during edit) -->
            <div v-if="isEdit && existingPhotos.length > 0" class="space-y-3">
              <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Foto yang Sudah Diunggah (Centang untuk menghapus):</span>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div
                  v-for="photo in existingPhotos"
                  :key="photo.id"
                  class="relative h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm transition-all duration-300"
                  :class="{ 'opacity-25 scale-95 border-red-500': form.hapus_foto.includes(photo.id) }"
                >
                  <img :src="'/storage/' + photo.path" class="w-full h-full object-cover" />
                  <label class="absolute inset-0 flex items-center justify-center bg-black/45 opacity-0 hover:opacity-100 transition-opacity cursor-pointer text-white text-[10px] font-bold uppercase tracking-wider">
                    <input
                      type="checkbox"
                      :value="photo.id"
                      v-model="form.hapus_foto"
                      class="mr-1.5 h-4 w-4 rounded accent-red-600"
                    />
                    Hapus
                  </label>
                </div>
              </div>
            </div>

            <!-- Upload New Photos -->
            <div class="space-y-3">
              <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Unggah Foto Baru (Bisa pilih banyak sekaligus):</label>
              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleFileChange"
                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2D5016]/10 file:text-[#2D5016] hover:file:bg-[#2D5016]/20 cursor-pointer"
              />
              <p v-if="form.errors.fotos" class="text-xs font-semibold text-red-600">
                {{ form.errors.fotos }}
              </p>

              <!-- New Photos Preview Grid -->
              <div v-if="previewUrls.length > 0" class="space-y-3 pt-4">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tinjau Foto Baru yang Siap Diunggah:</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                  <div v-for="(url, idx) in previewUrls" :key="idx" class="h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm relative">
                    <img :src="url" class="w-full h-full object-cover" />
                    <button
                      type="button"
                      class="absolute top-1.5 right-1.5 h-5 w-5 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center text-xs cursor-pointer font-bold"
                      @click="removeNewPhoto(idx)"
                    >
                      &times;
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Status Tayang Checkbox -->
          <div class="flex items-center gap-3 pt-4 border-t border-gray-100 shrink-0">
            <input
              id="is_published"
              type="checkbox"
              v-model="form.is_published"
              class="h-4.5 w-4.5 rounded border-gray-300 text-[#2D5016] focus:ring-[#2D5016] cursor-pointer"
            />
            <label for="is_published" class="text-xs font-bold text-gray-800 uppercase tracking-wider cursor-pointer">
              Terbitkan Artikel Ini Sekarang (Jadikan Publik)
            </label>
          </div>

          <!-- Submit Buttons -->
          <div class="flex gap-3 pt-4 shrink-0">
            <AppButton type="submit" variant="primary" :loading="form.processing">
              {{ isEdit ? 'Perbarui Berita' : 'Simpan Berita' }}
            </AppButton>
            <Link href="/operator/berita">
              <AppButton variant="ghost">Batal</AppButton>
            </Link>
          </div>

        </form>
      </div>
    </div>
  </OperatorLayout>
</template>

<script setup>
import { ref, computed, onBeforeUnmount } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppSelect from '@/Components/UI/AppSelect.vue';

const props = defineProps({
  berita: {
    type: Object,
    default: null
  }
});

const isEdit = computed(() => !!props.berita);
const existingPhotos = computed(() => props.berita?.foto_galeri || []);

const form = useForm({
  judul: props.berita?.judul || '',
  kategori: props.berita?.kategori || '',
  penulis: props.berita?.penulis || 'Operator',
  isi: props.berita?.isi || '',
  is_published: props.berita?.is_published || false,
  fotos: [],
  hapus_foto: [] // List of existing photo IDs marked for deletion
});

const categoryOptions = [
  { value: 'pembangunan', label: 'Pembangunan' },
  { value: 'pengumuman', label: 'Pengumuman' },
  { value: 'agenda', label: 'Agenda' }
];

const selectedFiles = ref([]);
const previewUrls = ref([]);

const handleFileChange = (e) => {
  const files = Array.from(e.target.files);
  selectedFiles.value = [...selectedFiles.value, ...files];
  form.fotos = selectedFiles.value;

  // Generate previews
  const newUrls = files.map(file => URL.createObjectURL(file));
  previewUrls.value = [...previewUrls.value, ...newUrls];
};

const removeNewPhoto = (index) => {
  // Revoke object URL
  URL.revokeObjectURL(previewUrls.value[index]);
  
  selectedFiles.value.splice(index, 1);
  previewUrls.value.splice(index, 1);
  form.fotos = selectedFiles.value;
};

const handleSubmit = () => {
  if (isEdit.value) {
    // Laravels PATCH/PUT has some issues parsing multipart/form-data. Use POST with _method override.
    form.transform((data) => ({
      ...data,
      _method: 'POST'
    })).post(`/operator/berita/${props.berita.id}`, {
      preserveScroll: true
    });
  } else {
    form.post('/operator/berita');
  }
};

onBeforeUnmount(() => {
  previewUrls.value.forEach(url => URL.createObjectURL(url));
});
</script>
