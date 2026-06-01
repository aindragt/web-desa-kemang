<template>
  <PublicLayout>
    <Head :title="'Formulir Pengajuan Surat ' + formatJenis(jenis)" />

    <!-- Form Section -->
    <section class="py-12 bg-white font-ui">
      <div class="max-w-xl mx-auto px-4 sm:px-6">
        
        <!-- Form Header Card -->
        <div class="bg-[#F5EDD8]/20 border border-[#C8952A]/20 rounded-2xl p-6 text-center space-y-2 mb-8">
          <h1 class="text-lg font-serif font-bold text-[#2D5016]">
            Formulir Layanan: {{ formatJenis(jenis) }}
          </h1>
          <p class="text-xs text-gray-500 font-body">Silakan lengkapi formulir di bawah ini dengan data kependudukan yang valid.</p>
        </div>

        <!-- Form Elements -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          
          <AppInput
            id="nik"
            label="Nomor Induk Kependudukan (NIK)"
            v-model="form.nik"
            placeholder="Masukkan 16 digit NIK Anda"
            required
            :error="form.errors.nik"
          />

          <AppInput
            id="nama_lengkap"
            label="Nama Lengkap"
            v-model="form.nama_lengkap"
            placeholder="Masukkan nama lengkap sesuai KTP"
            required
            :error="form.errors.nama_lengkap"
          />

          <AppInput
            id="kontak"
            label="Nomor Telepon / WhatsApp"
            v-model="form.kontak"
            placeholder="Contoh: 082388889999"
            required
            :error="form.errors.kontak"
          />

          <!-- Dinamis: Hanya muncul jika jenis surat = usaha (SKU) -->
          <Transition
            enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150 transform"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
          >
            <div v-if="jenis === 'usaha'" class="space-y-6 bg-gray-50 p-5 rounded-2xl border border-gray-200">
              <AppInput
                id="nama_usaha"
                label="Nama Usaha / UMKM"
                v-model="form.nama_usaha"
                placeholder="Masukkan nama unit usaha Anda"
                required
                :error="form.errors.nama_usaha"
              />

              <AppInput
                id="jenis_usaha"
                label="Jenis / Bidang Usaha"
                v-model="form.jenis_usaha"
                placeholder="Contoh: Perdagangan Sembako, Pertanian Sawit, Kuliner"
                required
                :error="form.errors.jenis_usaha"
              />
            </div>
          </Transition>

          <AppTextarea
            id="keperluan"
            label="Maksud & Keperluan Pengajuan"
            v-model="form.keperluan"
            placeholder="Sebutkan alasan atau keperluan pembuatan surat ini"
            required
            :rows="3"
            :error="form.errors.keperluan"
          />

          <!-- Buttons -->
          <div class="flex gap-3 pt-4">
            <AppButton
              type="submit"
              variant="primary"
              customClass="flex-1"
              :loading="form.processing"
            >
              Kirim Pengajuan Surat
            </AppButton>
            <Link href="/layanan-surat" class="flex-1">
              <AppButton variant="ghost" customClass="w-full">
                Batal
              </AppButton>
            </Link>
          </div>

        </form>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppTextarea from '@/Components/UI/AppTextarea.vue';

const props = defineProps({
  jenis: {
    type: String,
    required: true
  }
});

const form = useForm({
  jenis_surat: props.jenis,
  nik: '',
  nama_lengkap: '',
  kontak: '',
  keperluan: '',
  nama_usaha: '',
  jenis_usaha: ''
});

const formatJenis = (slug) => {
  const map = {
    usaha: 'Surat Keterangan Usaha (SKU)',
    domisili: 'Surat Keterangan Domisili (SKD)',
    ktp: 'Surat Pengantar KTP (SPK)',
    kematian: 'Surat Keterangan Kematian (SKK)'
  };
  return map[slug] || 'Surat Keterangan';
};

const handleSubmit = () => {
  form.post('/layanan-surat/pengajuan');
};
</script>
