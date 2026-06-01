<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed font-ui active:scale-98',
      sizeClasses[size],
      variantClasses[variant],
      customClass
    ]"
    @click="$emit('click', $event)"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
    <slot />
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (v) => ['primary', 'secondary', 'danger', 'ghost'].includes(v)
  },
  size: {
    type: String,
    default: 'md',
    validator: (v) => ['sm', 'md', 'lg'].includes(v)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  customClass: {
    type: String,
    default: ''
  }
});

defineEmits(['click']);

const sizeClasses = {
  sm: 'px-3 py-1.5 text-xs',
  md: 'px-4 py-2.5 text-sm',
  lg: 'px-6 py-3.5 text-base'
};

const variantClasses = {
  primary: 'bg-[#2D5016] text-white hover:bg-[#1f380e] shadow-sm focus:ring-2 focus:ring-[#2D5016]/40',
  secondary: 'bg-[#C8952A] text-white hover:bg-[#b08120] shadow-sm focus:ring-2 focus:ring-[#C8952A]/40',
  danger: 'bg-red-600 text-white hover:bg-red-700 shadow-sm focus:ring-2 focus:ring-red-500/40',
  ghost: 'bg-[#F5EDD8] text-[#2D5016] border border-[#2D5016]/20 hover:bg-[#ebdcb4] focus:ring-2 focus:ring-[#2D5016]/10'
};
</script>
