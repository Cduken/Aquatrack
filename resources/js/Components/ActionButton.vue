<template>
  <button :class="`flex items-center gap-2 px-4 py-2 text-white rounded font-semibold bg-${color}-600 hover:bg-${color}-700`">
    <span>{{ icon }}</span>
    <span>{{ label }}</span>
  </button>
</template>

<script setup>
defineProps({ color: String, label: String, icon: String });
</script>
