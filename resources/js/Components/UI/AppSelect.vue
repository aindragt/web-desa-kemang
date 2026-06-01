<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-semibold text-gray-700 mb-1.5 font-ui">
      {{ label }}
    </label>
    <div class="relative">
      <select
        :id="id"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        :class="[
          'w-full px-3.5 py-2.5 bg-white border rounded-lg text-sm text-gray-900 transition-all duration-200 font-ui appearance-none focus:outline-none focus:ring-2 disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
          error
            ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
            : 'border-gray-300 focus:border-[#2D5016] focus:ring-[#2D5016]/20'
        ]"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
      <!-- Custom Arrow Icon -->
      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <p v-if="error" class="mt-1 text-xs font-semibold text-red-600 font-ui">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  id: {
    type: String,
    required: true
  },
  label: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  options: {
    type: Array,
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
});

defineEmits(['update:modelValue']);
</script>
