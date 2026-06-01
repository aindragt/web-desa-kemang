<template>
  <AdminLayout>
    <Head title="Kelola Operator Desa - Panel Admin" />

    <div class="space-y-8 font-ui">
      <!-- Header -->
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm shrink-0">
        <h1 class="text-lg font-serif font-bold text-[#2D5016]">Kelola Akun Staf Operator Desa</h1>
        <p class="text-xs text-gray-500 font-body">Mendaftarkan operator baru, memantau akun aktif, menonaktifkan akun sementara, atau mereset password.</p>
      </div>

      <!-- Alert -->
      <AppAlert v-if="alertMessage" :type="alertType" @close="alertMessage = ''">
        {{ alertMessage }}
      </AppAlert>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Table & Inline collapsible Reset Password form -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm overflow-hidden space-y-4">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                  <th class="py-3 pr-2">Nama Operator</th>
                  <th class="py-3 px-2">Username</th>
                  <th class="py-3 px-2">Status</th>
                  <th class="py-3 px-2">Terakhir Login</th>
                  <th class="py-3 pl-2 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50 text-gray-700">
                <template v-for="op in operators.data" :key="op.id">
                  <tr class="hover:bg-gray-50/50 font-medium">
                    <td class="py-3.5 pr-2 font-bold">{{ op.nama }}</td>
                    <td class="py-3.5 px-2 font-mono text-gray-500">{{ op.username }}</td>
                    <td class="py-3.5 px-2">
                      <AppBadge :variant="op.is_active ? 'hijau' : 'merah'" size="sm">
                        {{ op.is_active ? 'Aktif' : 'Nonaktif' }}
                      </AppBadge>
                    </td>
                    <td class="py-3.5 px-2 text-gray-400">
                      {{ op.last_login_at ? new Date(op.last_login_at).toLocaleString('id-ID') : 'Belum pernah' }}
                    </td>
                    <td class="py-3.5 pl-2 text-right space-x-1.5 whitespace-nowrap">
                      <!-- Toggle Status Button -->
                      <AppButton
                        variant="ghost"
                        size="sm"
                        customClass="!py-1"
                        @click="handleToggle(op.id)"
                      >
                        {{ op.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                      </AppButton>

                      <!-- Inline Reset Pass Toggle -->
                      <AppButton
                        variant="secondary"
                        size="sm"
                        customClass="!py-1"
                        @click="toggleResetForm(op.id)"
                      >
                        Reset Sandi
                      </AppButton>

                      <AppButton
                        variant="danger"
                        size="sm"
                        customClass="!py-1"
                        @click="confirmDelete(op.id)"
                      >
                        Hapus
                      </AppButton>
                    </td>
                  </tr>

                  <!-- Collapsible Inline Reset Password Form Row -->
                  <tr v-if="activeResetId === op.id" class="bg-gray-50/70">
                    <td colspan="5" class="py-4 px-6 border-t border-b border-gray-200/50">
                      <form @submit.prevent="handleResetPassword(op.id)" class="flex flex-col sm:flex-row items-end gap-4 max-w-md">
                        <AppInput
                          id="new_password"
                          label="Reset Password Baru"
                          type="password"
                          v-model="resetForm.password"
                          placeholder="Masukkan password baru..."
                          required
                          customClass="flex-1"
                        />
                        <div class="flex gap-2">
                          <AppButton type="submit" variant="secondary" size="sm" :loading="resetForm.processing">
                            Simpan Sandi
                          </AppButton>
                          <AppButton type="button" variant="ghost" size="sm" @click="activeResetId = null">
                            Batal
                          </AppButton>
                        </div>
                      </form>
                    </td>
                  </tr>
                </template>
                <tr v-if="operators.data.length === 0">
                  <td colspan="5" class="py-10 text-center text-gray-400 font-body">Belum ada akun operator terdaftar.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="operators.links && operators.links.length > 3" class="flex justify-center items-center gap-1 mt-6 text-xs font-semibold">
            <Link
              v-for="link in operators.links"
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

        <!-- Right Side: Sticky Add New Operator Form -->
        <div class="space-y-6">
          <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-6 lg:sticky lg:top-24">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">Daftarkan Operator Baru</h3>
            
            <form @submit.prevent="handleCreateOperator" class="space-y-5">
              <AppInput
                id="nama"
                label="Nama Lengkap Operator"
                v-model="createForm.nama"
                placeholder="Masukkan nama lengkap staf"
                required
                :error="createForm.errors.nama"
              />

              <AppInput
                id="username"
                label="Username Login"
                v-model="createForm.username"
                placeholder="Contoh: staf_kemang, budi12"
                required
                :error="createForm.errors.username"
              />

              <AppInput
                id="password"
                label="Password Default"
                type="password"
                v-model="createForm.password"
                placeholder="Minimal 6 karakter"
                required
                :error="createForm.errors.password"
              />

              <!-- Info rights -->
              <div class="bg-gray-50 border border-gray-150 p-4 rounded-2xl text-[11px] text-gray-500 font-body leading-relaxed">
                <strong>Hak Akses Staf Operator:</strong>
                <p class="mt-1">Operator dapat memverifikasi permohonan surat kependudukan warga, merubah progres tahapan pengerjaan, mencetak fisik berkas A4, serta mempublikasikan artikel kabar berita desa.</p>
              </div>

              <AppButton type="submit" variant="primary" :loading="createForm.processing" customClass="w-full">
                Simpan & Daftarkan Staf
              </AppButton>
            </form>
          </div>
        </div>

      </div>
    </div>

    <!-- Confirm Toggle Modal -->
    <AppModal
      :show="showToggleModal"
      title="Ubah Status Akses Staf"
      message="Apakah Anda yakin ingin merubah status akses login staf operator ini? Akun nonaktif tidak akan diizinkan masuk ke sistem."
      confirmText="Ya, Ubah"
      cancelText="Batal"
      :loading="toggling"
      @close="showToggleModal = false"
      @confirm="executeToggle"
    />

    <!-- Confirm Delete Modal -->
    <AppModal
      :show="showDeleteModal"
      title="Hapus Akun Operator"
      message="Apakah Anda yakin ingin menghapus akun staf operator ini secara permanen dari server?"
      confirmText="Ya, Hapus"
      cancelText="Batal"
      :loading="deleting"
      @close="showDeleteModal = false"
      @confirm="executeDelete"
    />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';
import AppBadge from '@/Components/UI/AppBadge.vue';
import AppInput from '@/Components/UI/AppInput.vue';
import AppModal from '@/Components/UI/AppModal.vue';
import AppAlert from '@/Components/UI/AppAlert.vue';

const props = defineProps({
  operators: {
    type: Object,
    default: () => ({ data: [] })
  }
});

const alertMessage = ref('');
const alertType = ref('success');

const showToggleModal = ref(false);
const showDeleteModal = ref(false);

const toggling = ref(false);
const deleting = ref(false);

const selectedOperatorId = ref(null);
const activeResetId = ref(null);

const createForm = useForm({
  nama: '',
  username: '',
  password: ''
});

const resetForm = useForm({
  password: ''
});

const toggleResetForm = (id) => {
  if (activeResetId.value === id) {
    activeResetId.value = null;
  } else {
    activeResetId.value = id;
    resetForm.reset();
  }
};

const handleToggle = (id) => {
  selectedOperatorId.value = id;
  showToggleModal.value = true;
};

const executeToggle = () => {
  toggling.value = true;
  router.patch(`/admin/operator/${selectedOperatorId.value}/toggle`, {}, {
    preserveScroll: true,
    onFinish: () => {
      toggling.value = false;
      showToggleModal.value = false;
      selectedOperatorId.value = null;
      alertMessage.value = 'Status operator berhasil diperbarui!';
      alertType.value = 'success';
    }
  });
};

const confirmDelete = (id) => {
  selectedOperatorId.value = id;
  showDeleteModal.value = true;
};

const executeDelete = () => {
  deleting.value = true;
  router.delete(`/admin/operator/${selectedOperatorId.value}`, {
    preserveScroll: true,
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
      selectedOperatorId.value = null;
      alertMessage.value = 'Akun operator berhasil dihapus secara permanen.';
      alertType.value = 'success';
    }
  });
};

const handleResetPassword = (id) => {
  resetForm.put(`/admin/operator/${id}/reset-password`, {
    preserveScroll: true,
    onSuccess: () => {
      activeResetId.value = null;
      resetForm.reset();
      alertMessage.value = 'Password operator berhasil direset!';
      alertType.value = 'success';
    },
    onError: () => {
      alertMessage.value = 'Gagal melakukan reset password.';
      alertType.value = 'error';
    }
  });
};

const handleCreateOperator = () => {
  createForm.post('/admin/operator', {
    onSuccess: () => {
      createForm.reset();
      alertMessage.value = 'Staf operator baru berhasil didaftarkan!';
      alertType.value = 'success';
    },
    onError: () => {
      alertMessage.value = 'Gagal mendaftarkan operator. Username mungkin duplikat.';
      alertType.value = 'error';
    }
  });
};
</script>
