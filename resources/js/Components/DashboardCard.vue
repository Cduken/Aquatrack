<template>
  <div class="bg-white p-4 rounded-md shadow space-y-2">
    <h3 class="text-sm text-gray-600 font-medium">{{ title }}</h3>
    <p class="text-2xl font-bold">{{ value }}</p>
    <slot name="footer"></slot>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number]
});
</script>
