<template>
  <PublicLayout>
    <Head title="Hubungi Kami - E-Government Desa Kemang" />

    <!-- Banner Section -->
    <section class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] text-white py-16 border-b-4 border-[#C8952A] relative">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold">Hubungi Kami</h1>
        <p class="text-xs md:text-sm text-amber-100/70 uppercase tracking-widest font-ui font-semibold">
          Sampaikan Aspirasi, Keluhan, atau Pertanyaan Langsung Kepada Pemerintah Desa
        </p>
      </div>
    </section>

    <!-- Contact Grid Section -->
    <section class="py-16 bg-white font-ui">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          
          <!-- Left side: Contact Info & Maps placeholder -->
          <div class="space-y-8">
            <div class="space-y-4">
              <h2 class="text-2xl font-serif font-bold text-[#2D5016]">Pusat Pelayanan Resmi</h2>
              <p class="text-xs text-gray-500 font-body leading-relaxed">
                Kantor Desa Kemang melayani urusan administrasi, koordinasi program pembangunan, dan kemitraan lembaga desa setiap hari kerja.
              </p>
            </div>

            <!-- Jam Pelayanan & Kontak Details -->
            <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 space-y-4 text-xs">
              <h3 class="font-bold text-gray-800 uppercase tracking-wider">Jam Pelayanan & Alamat</h3>
              <div class="space-y-3 font-body text-gray-600">
                <p><strong>Alamat:</strong> Jl. Lintas Timur No.45, Desa Kemang, Kec. Pangkalan Kuras, Kabupaten Pelalawan, Riau</p>
                <p><strong>Jam Kerja:</strong> Senin - Kamis (08:00 - 15:30) & Jumat (08:00 - 16:00)</p>
                <p><strong>Kontak Telepon:</strong> +62 823-8888-9999</p>
                <p><strong>Email Pengaduan:</strong> pengaduan@kemang.desa.id</p>
              </div>
            </div>

            <!-- Maps Placeholder -->
            <div class="bg-[#FAF9F5] border border-gray-100 rounded-3xl p-6 shadow-inner text-center shrink-0">
              <div class="h-56 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 text-xs uppercase tracking-widest font-semibold shadow-inner">
                Peta Lokasi Kantor Desa Kemang
              </div>
              <p class="text-[10px] text-gray-400 mt-2 font-body">Google Maps Kantor Kepala Desa Kemang</p>
            </div>
          </div>

          <!-- Right side: Message Form -->
          <div class="bg-white border border-gray-100 shadow-lg rounded-3xl p-6 md:p-8 space-y-6">
            <div class="space-y-1">
              <h3 class="text-lg font-serif font-bold text-[#2D5016]">Kirim Pesan Langsung</h3>
              <p class="text-xs text-gray-500 font-body">Pesan Anda akan masuk ke dashboard operator desa untuk ditindaklanjuti.</p>
            </div>

            <!-- Success Alert -->
            <AppAlert v-if="successAlert" type="success" @close="successAlert = false">
              Pesan Anda berhasil dikirim! Operator kami akan segera memverifikasi laporan/pertanyaan Anda.
            </AppAlert>

            <form @submit.prevent="handleSubmit" class="space-y-6">
              <AppInput
                id="nama"
                label="Nama Lengkap"
                v-model="form.nama"
                placeholder="Masukkan nama lengkap Anda"
                required
                :error="form.errors.nama"
              />

              <AppInput
                id="kontak"
                label="Email atau No. Telepon"
                v-model="form.kontak"
                placeholder="Contoh: nama@email.com atau 0823xxx"
                required
                :error="form.errors.kontak"
              />

              <AppTextarea
                id="pesan"
                label="Isi Laporan / Pesan"
                v-model="form.pesan"
                placeholder="Tuliskan keluhan, saran, aduan pembangunan, atau pertanyaan Anda di sini..."
                required
                :rows="4"
                :error="form.errors.pesan"
              />

              <AppButton type="submit" variant="primary" customClass="w-full" :loading="form.processing">
                Kirim Pesan Aspirasi
              </AppButton>
            </form>
          </div>

        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppTextarea from '@/Components/UI/AppTextarea.vue';
import AppAlert from '@/Components/UI/AppAlert.vue';

const successAlert = ref(false);

const form = useForm({
  nama: '',
  kontak: '',
  pesan: ''
});

const handleSubmit = () => {
  form.post('/kontak', {
    onSuccess: () => {
      form.reset();
      successAlert.value = true;
    }
  });
};
</script>
