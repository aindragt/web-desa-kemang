<template>
  <AdminLayout>
    <Head title="Kelola Statistik Desa - Panel Admin" />

    <div class="space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Kelola Data Demografi & Statistik</h1>
        <p class="text-xs text-gray-500 font-body">Tambahkan parameter data demografi baru atau perbarui nilai statistik kependudukan secara langsung.</p>
      </div>

      <!-- Success / Error Alert -->
      <AppAlert v-if="alertMessage" :type="alertType" @close="alertMessage = ''">
        {{ alertMessage }}
      </AppAlert>

      <!-- Form Tambah Data Baru -->
      <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Tambah Item Statistik Baru</h3>
        <form @submit.prevent="handleStore" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <AppSelect
            id="new_kategori"
            label="Kategori"
            v-model="newForm.kategori"
            placeholder="Pilih Kategori"
            :options="[
              { value: 'pendidikan', label: 'Pendidikan' },
              { value: 'pekerjaan', label: 'Pekerjaan' },
              { value: 'agama', label: 'Agama' }
            ]"
            required
          />
          <AppInput
            id="new_label"
            label="Label Parameter"
            v-model="newForm.label"
            placeholder="Contoh: Belum Sekolah, Islam"
            required
          />
          <AppInput
            id="new_nilai"
            label="Nilai Angka"
            type="number"
            v-model="newForm.nilai"
            placeholder="Contoh: 150"
            required
          />
          <div class="flex gap-2">
            <AppInput
              id="new_satuan"
              label="Satuan"
              v-model="newForm.satuan"
              placeholder="jiwa/orang"
              required
              customClass="flex-1"
            />
            <AppButton type="submit" variant="primary" :loading="newForm.processing" customClass="shrink-0 h-10 self-end">
              Tambah
            </AppButton>
          </div>
        </form>
      </div>

      <!-- Editable Tables Per Category -->
      <div v-for="(items, category) in groupedStatistik" :key="category" class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-6">
        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
          <h3 class="text-sm font-bold text-[#2D5016] uppercase tracking-wider font-serif">Kategori: {{ category }}</h3>
          <AppButton
            variant="secondary"
            size="sm"
            :loading="bulkForms[category].processing"
            @click="handleBulkUpdate(category)"
          >
            Simpan Perubahan
          </AppButton>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                <th class="py-3 pr-2 w-1/3">Label</th>
                <th class="py-3 px-2 w-24 text-center">Urutan</th>
                <th class="py-3 px-2 w-32">Nilai (Satuan)</th>
                <th class="py-3 px-2">Visual Persentase</th>
                <th class="py-3 pl-2 text-right w-20">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
              <tr v-for="(item, idx) in bulkForms[category].data" :key="item.id" class="hover:bg-gray-50/50">
                <!-- Label (Static) -->
                <td class="py-3 pr-2 font-bold">{{ item.label }}</td>
                
                <!-- Order input -->
                <td class="py-2 px-2">
                  <input
                    type="number"
                    v-model.number="item.urutan"
                    class="w-16 mx-auto text-center px-1.5 py-1 bg-white border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#2D5016]"
                    required
                  />
                </td>

                <!-- Nilai input -->
                <td class="py-2 px-2">
                  <div class="flex items-center gap-1.5">
                    <input
                      type="number"
                      v-model.number="item.nilai"
                      class="w-20 px-1.5 py-1 bg-white border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#2D5016] font-semibold"
                      required
                    />
                    <span class="text-gray-400 text-[10px] uppercase font-bold">{{ item.satuan }}</span>
                  </div>
                </td>

                <!-- Mini Bar Percent -->
                <td class="py-3 px-2">
                  <div class="flex items-center gap-3">
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden shrink">
                      <div class="bg-[#2D5016] h-full rounded-full" :style="{ width: calculatePercent(item.nilai, bulkForms[category].data) + '%' }"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 shrink-0">{{ calculatePercent(item.nilai, bulkForms[category].data) }}%</span>
                  </div>
                </td>

                <!-- Actions -->
                <td class="py-2 pl-2 text-right">
                  <AppButton
                    variant="danger"
                    size="sm"
                    customClass="!py-1"
                    @click="confirmDelete(item.id)"
                  >
                    Hapus
                  </AppButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Confirm Delete Modal -->
    <AppModal
      :show="showDeleteModal"
      title="Hapus Parameter Statistik"
      message="Apakah Anda yakin ingin menghapus parameter data statistik kependudukan ini secara permanen?"
      confirmText="Ya, Hapus"
      cancelText="Batal"
      :loading="deleting"
      @close="showDeleteModal = false"
      @confirm="executeDelete"
    />
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppSelect from '@/Components/UI/AppSelect.vue';
import AppAlert from '@/Components/UI/AppAlert.vue';
import AppModal from '@/Components/UI/AppModal.vue';

const props = defineProps({
  statistik: {
    type: Object,
    default: () => ({})
  }
});

const alertMessage = ref('');
const alertType = ref('success');

const showDeleteModal = ref(false);
const selectedItemId = ref(null);
const deleting = ref(false);

const groupedStatistik = computed(() => props.statistik || {});

// Forms setup
const newForm = useForm({
  kategori: '',
  label: '',
  nilai: '',
  satuan: '',
  urutan: 0
});

// Bulk update forms per category
const bulkForms = {};
const initBulkForms = () => {
  ['pendidikan', 'pekerjaan', 'agama'].forEach(cat => {
    const list = groupedStatistik.value[cat] || [];
    bulkForms[cat] = useForm({
      data: list.map(item => ({
        id: item.id,
        label: item.label,
        nilai: item.nilai,
        satuan: item.satuan,
        urutan: item.urutan
      }))
    });
  });
};
initBulkForms();

const calculatePercent = (val, list) => {
  const sum = list.reduce((s, i) => s + parseFloat(i.nilai || 0), 0);
  if (sum === 0) return 0;
  return Math.round((val / sum) * 100);
};

const handleStore = () => {
  newForm.post('/admin/statistik', {
    onSuccess: () => {
      newForm.reset();
      initBulkForms();
      alertMessage.value = 'Item statistik baru berhasil ditambahkan!';
      alertType.value = 'success';
    },
    onError: () => {
      alertMessage.value = 'Gagal menambahkan data statistik.';
      alertType.value = 'error';
    }
  });
};

const handleBulkUpdate = (category) => {
  bulkForms[category].put('/admin/statistik/update-semua', {
    preserveScroll: true,
    onSuccess: () => {
      alertMessage.value = `Data statistik ${category} berhasil diperbarui!`;
      alertType.value = 'success';
    },
    onError: () => {
      alertMessage.value = `Gagal memperbarui data statistik ${category}.`;
      alertType.value = 'error';
    }
  });
};

const confirmDelete = (id) => {
  selectedItemId.value = id;
  showDeleteModal.value = true;
};

const executeDelete = () => {
  deleting.value = true;
  router.delete(`/admin/statistik/${selectedItemId.value}`, {
    preserveScroll: true,
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
      selectedItemId.value = null;
      initBulkForms();
      alertMessage.value = 'Item statistik berhasil dihapus.';
      alertType.value = 'success';
    }
  });
};
</script>
