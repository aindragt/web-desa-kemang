<template>
  <span
    :class="[
      'inline-flex items-center font-semibold rounded-full font-ui',
      sizeClasses[size],
      variantClasses[variant]
    ]"
  >
    <slot />
  </span>
</template>

<script setup>
defineProps({
  variant: {
    type: String,
    default: 'abu',
    validator: (v) => ['hijau', 'emas', 'merah', 'abu'].includes(v)
  },
  size: {
    type: String,
    default: 'md',
    validator: (v) => ['sm', 'md'].includes(v)
  }
});

const sizeClasses = {
  sm: 'px-2 py-0.5 text-[10px]',
  md: 'px-2.5 py-1 text-xs'
};

const variantClasses = {
  hijau: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
  emas: 'bg-amber-50 text-[#C8952A] border border-[#C8952A]/30',
  merah: 'bg-red-50 text-red-700 border border-red-200/60',
  abu: 'bg-gray-100 text-gray-700 border border-gray-200'
};
</script>
