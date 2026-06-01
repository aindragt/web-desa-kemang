<template>
  <OperatorLayout>
    <Head title="Inbox Pesan Kontak - Panel Operator" />

    <div class="space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Inbox Pesan Kontak Warga</h1>
        <p class="text-xs text-gray-500 font-body">Daftar aspirasi, pertanyaan, laporan pembangunan, dan pengaduan langsung warga desa.</p>
      </div>

      <!-- Table Content -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                <th class="py-3 pr-2 w-16 text-center">Status</th>
                <th class="py-3 px-2">Nama Pengirim</th>
                <th class="py-3 px-2">Kontak</th>
                <th class="py-3 px-2">Isi Pesan / Aduan</th>
                <th class="py-3 px-2">Tanggal Masuk</th>
                <th class="py-3 pl-2 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
              <tr
                v-for="msg in pesan.data"
                :key="msg.id"
                :class="[
                  'hover:bg-gray-50/50 transition-colors',
                  !msg.is_read ? 'font-bold text-gray-900 bg-gray-50/30' : 'font-medium text-gray-500'
                ]"
              >
                <!-- Badge Status unread -->
                <td class="py-3.5 pr-2 text-center select-none">
                  <AppBadge v-if="!msg.is_read" variant="emas" size="sm">Baru</AppBadge>
                  <span v-else class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dibaca</span>
                </td>

                <td class="py-3.5 px-2">{{ msg.nama }}</td>
                <td class="py-3.5 px-2 font-mono text-[11px]">{{ msg.kontak }}</td>
                
                <!-- Excerpt message -->
                <td class="py-3.5 px-2 max-w-xs truncate font-body">
                  {{ msg.pesan }}
                </td>

                <td class="py-3.5 px-2 whitespace-nowrap">
                  {{ new Date(msg.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                </td>

                <!-- Actions -->
                <td class="py-3.5 pl-2 text-right space-x-1.5 whitespace-nowrap">
                  <Link :href="'/operator/pesan/' + msg.id" class="inline-block">
                    <AppButton variant="ghost" size="sm" customClass="!py-1">Baca</AppButton>
                  </Link>

                  <AppButton
                    variant="danger"
                    size="sm"
                    customClass="!py-1"
                    @click="confirmDelete(msg.id)"
                  >
                    Hapus
                  </AppButton>
                </td>
              </tr>
              <tr v-if="pesan.data.length === 0">
                <td colspan="6" class="py-10 text-center text-gray-400 font-body">Tidak ada pesan kontak masuk.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pesan.links && pesan.links.length > 3" class="flex justify-center items-center gap-1 mt-8 text-xs font-semibold">
          <Link
            v-for="link in pesan.links"
            :key="link.label"
            :href="link.url || '#'"
            v-html="link.label"
            :class="[
              'px-2.5 py-1.5 rounded-lg border transition-colors',
              link.active
                ? 'bg-[#2D5016] text-white border-[#2D5016]'
                : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50',
              !link.url ? 'opacity-45 cursor-not-allowed pointer-events-none' : ''
            ]"
          />
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <AppModal
      :show="showDeleteModal"
      title="Hapus Pesan Aspirasi"
      message="Apakah Anda yakin ingin menghapus pesan kontak masuk dari warga ini secara permanen?"
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
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppModal from '@/Components/UI/AppModal.vue';

defineProps({
  pesan: {
    type: Object,
    default: () => ({ data: [] })
  }
});

const showDeleteModal = ref(false);
const selectedPesanId = ref(null);
const deleting = ref(false);

const confirmDelete = (id) => {
  selectedPesanId.value = id;
  showDeleteModal.value = true;
};

const executeDelete = () => {
  deleting.value = true;
  router.delete(`/operator/pesan/${selectedPesanId.value}`, {
    preserveScroll: true,
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
      selectedPesanId.value = null;
    }
  });
};
</script>
