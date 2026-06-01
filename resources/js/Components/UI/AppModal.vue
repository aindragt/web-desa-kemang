<template>
  <Teleport to="body">
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 transition-opacity flex items-center justify-center p-4"
        @click.self="handleClose"
      >
        <Transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to-class="opacity-100 translate-y-0 sm:scale-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100 translate-y-0 sm:scale-100"
          leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100 font-ui transform transition-all">
            <!-- Modal Body -->
            <div class="p-6">
              <div class="flex items-start gap-4">
                <!-- Danger Icon -->
                <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                  <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-gray-900">
                    {{ title }}
                  </h3>
                  <p class="text-sm text-gray-500 mt-2">
                    {{ message }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Footer / Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse justify-start gap-2">
              <button
                type="button"
                :disabled="loading"
                class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 active:scale-98 rounded-lg shadow-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                @click="$emit('confirm')"
              >
                <span v-if="loading">Memproses...</span>
                <span v-else>{{ confirmText }}</span>
              </button>
              <button
                type="button"
                :disabled="loading"
                class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg active:scale-98 cursor-pointer disabled:opacity-50"
                @click="handleClose"
              >
                {{ cancelText }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Konfirmasi Aksi'
  },
  message: {
    type: String,
    default: 'Apakah Anda yakin ingin melakukan aksi berbahaya ini? Tindakan ini tidak dapat dibatalkan.'
  },
  confirmText: {
    type: String,
    default: 'Ya, Hapus'
  },
  cancelText: {
    type: String,
    default: 'Batal'
  },
  loading: {
    type: Boolean,
    default: false
  },
  closeOnOverlay: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['close', 'confirm']);

const handleClose = () => {
  if (props.closeOnOverlay && !props.loading) {
    emit('close');
  }
};
</script>
