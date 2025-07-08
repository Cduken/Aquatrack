// resources/js/Shared/StickyButtons.vue
<template>
  <div class="fixed bottom-0 left-0 right-0 bg-gray-100 py-4 shadow-lg border-t border-gray-200">
    <div class="container mx-auto px-4 flex justify-between">
      <button
        @click="reportProblem"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
      >
        Report Problem
      </button>
      <button
        @click="viewInterruptions"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
      >
        View Interruptions
      </button>
      <button
        @click="checkReadings"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
      >
        Check Readings
      </button>
      <button
        @click="seeActiveReports"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
      >
        See Active Reports
      </button>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const reportProblem = () => {
  router.get(route('problems.create'));
};

const viewInterruptions = () => {
  router.get(route('interruptions.index'));
};

const checkReadings = () => {
  router.get(route('readings.index'));
};

const seeActiveReports = () => {
  router.get(route('reports.active'));
};
</script>
