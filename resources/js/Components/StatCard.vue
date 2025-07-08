<template>
  <div class="bg-white rounded-md shadow p-4 space-y-1">
    <h3 class="text-sm text-gray-500 font-semibold">{{ title }}</h3>
    <p class="text-2xl font-bold text-gray-800">{{ value }}</p>
    <slot name="footer" />
  </div>
</template>

<script setup>
defineProps({ title: String, value: String });
</script>
