<template>
  <Transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="visible"
      :class="[
        'max-w-md w-full border rounded-xl p-4 shadow-lg flex items-start gap-3 backdrop-blur-md font-ui transition-all duration-300',
        variantClasses[type]
      ]"
    >
      <!-- Icon Success -->
      <svg
        v-if="type === 'success'"
        class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2.5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <!-- Icon Error -->
      <svg
        v-else
        class="h-5 w-5 text-red-600 shrink-0 mt-0.5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2.5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>

      <div class="flex-1 text-sm font-medium leading-5">
        <slot />
      </div>

      <!-- Close Button -->
      <button
        type="button"
        class="text-gray-400 hover:text-gray-600 transition-colors shrink-0 cursor-pointer"
        @click="dismiss"
      >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'success',
    validator: (v) => ['success', 'error'].includes(v)
  },
  duration: {
    type: Number,
    default: 5000
  }
});

const emit = defineEmits(['close']);
const visible = ref(true);
let timer = null;

const dismiss = () => {
  visible.value = false;
  emit('close');
};

onMounted(() => {
  if (props.duration > 0) {
    timer = setTimeout(() => {
      dismiss();
    }, props.duration);
  }
});

onBeforeUnmount(() => {
  if (timer) clearTimeout(timer);
});

const variantClasses = {
  success: 'bg-emerald-50/90 border-emerald-200 text-emerald-800',
  error: 'bg-red-50/90 border-red-200 text-red-800'
};
</script>
