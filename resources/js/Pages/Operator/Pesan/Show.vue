<template>
  <OperatorLayout>
    <Head title="Detail Pesan Masuk - Panel Operator" />

    <div class="max-w-2xl mx-auto space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-center shrink-0">
        <div class="space-y-1">
          <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Aspirasi & Pengaduan Warga</span>
          <h1 class="text-sm font-bold text-gray-800">Detail Pesan Masuk</h1>
        </div>
        
        <Link href="/operator/pesan">
          <AppButton variant="ghost" size="sm">Kembali</AppButton>
        </Link>
      </div>

      <!-- Detail Box -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <!-- Sender Header info -->
        <div class="flex justify-between items-start gap-4 border-b border-gray-100 pb-4">
          <div class="space-y-0.5">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Nama Pengirim</span>
            <span class="block text-sm font-bold text-gray-800">{{ pesan.nama }}</span>
          </div>
          <div class="space-y-0.5 text-right">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Tanggal Masuk</span>
            <span class="block text-xs font-semibold text-gray-700">
              {{ new Date(pesan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
            </span>
          </div>
        </div>

        <!-- Message Body -->
        <div class="space-y-1.5 col-span-2">
          <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Isi Pesan / Pengaduan:</span>
          <div class="bg-[#F5EDD8]/10 border border-[#C8952A]/10 rounded-2xl p-5 text-sm leading-relaxed text-gray-700 font-body">
            {{ pesan.pesan }}
          </div>
        </div>

        <div class="grid grid-cols-2 gap-y-4 pt-4 text-xs font-medium border-t border-gray-50">
          <div class="space-y-0.5 col-span-2">
            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wide">Informasi Kontak Pemohon:</span>
            <span class="block text-gray-800 font-mono">{{ pesan.kontak }}</span>
          </div>
        </div>

        <!-- Call Action Buttons -->
        <div class="pt-4 border-t border-gray-50 flex flex-wrap gap-3">
          <!-- Reply via WhatsApp (conditional template shortcut) -->
          <a
            v-if="isNumber(pesan.kontak)"
            :href="'https://wa.me/' + formatWhatsApp(pesan.kontak)"
            target="_blank"
            class="flex-1 min-w-[150px]"
          >
            <AppButton variant="secondary" size="md" customClass="w-full">
              Balas via WhatsApp
            </AppButton>
          </a>

          <!-- Reply via Email (conditional template shortcut) -->
          <a
            v-if="isEmail(pesan.kontak)"
            :href="'mailto:' + pesan.kontak"
            class="flex-1 min-w-[150px]"
          >
            <AppButton variant="secondary" size="md" customClass="w-full">
              Balas via Email
            </AppButton>
          </a>

          <AppButton variant="danger" size="md" customClass="flex-1 min-w-[150px]" :loading="deleting" @click="showDeleteModal = true">
            Hapus Pesan
          </AppButton>
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <AppModal
      :show="showDeleteModal"
      title="Hapus Pesan Aspirasi"
      message="Apakah Anda yakin ingin menghapus pesan kontak masuk ini secara permanen?"
      confirmText="Ya, Hapus"
      cancelText="Batal"
      :loading="deleting"
      @close="showDeleteModal = false"
      @confirm="executeDelete"
    />
  </OperatorLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import OperatorLayout from '@/Layouts/OperatorLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppModal from '@/Components/UI/AppModal.vue';

const props = defineProps({
  pesan: {
    type: Object,
    required: true
  }
});

const showDeleteModal = ref(false);
const deleting = ref(false);

const isEmail = (val) => {
  return val.includes('@');
};

const isNumber = (val) => {
  return /^\+?[0-9\s-]{8,20}$/.test(val);
};

const formatWhatsApp = (num) => {
  let cleaned = num.replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};

const executeDelete = () => {
  deleting.value = true;
  router.delete(`/operator/pesan/${props.pesan.id}`, {
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
    }
  });
};
</script>
