<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Gauge } from 'lucide-vue-next'
import { AlertTriangle } from 'lucide-vue-next'
import { Bell } from 'lucide-vue-next'
import { FileText } from 'lucide-vue-next'

defineOptions({
  layout: AuthenticatedLayout
})

import { router } from '@inertiajs/vue3'
function goToReport() {
  router.visit('/report')
}
</script>

<template>

  <div class="min-h-screen bg-gray-100">
    <!-- Sticky Quick Action Bar -->
    <section class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 py-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
    <!-- Report Problem -->
    <button
        @click="goToReport"
      type="button"
      class="flex flex-col items-center justify-center w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm shadow-sm transition duration-150 ease-in-out focus:outline-none"
    >
      <AlertTriangle class="w-6 h-6 mb-1 text-white" />
      <span>Report Problem</span>
    </button>

    <!-- View Interruptions -->
    <button
      type="button"
      class="group flex flex-col items-center justify-center w-full px-4 py-3 border border-blue-600 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 transition duration-150 ease-in-out focus:outline-none shadow-sm"
    >
      <Bell class="w-6 h-6 mb-1 transition-transform duration-200 group-hover:scale-110" />
      <span>View Interruptions</span>
    </button>

    <!-- Check Readings -->
    <button
      type="button"
      class="group flex flex-col items-center justify-center w-full px-4 py-3 border border-green-600 text-green-600 rounded-lg text-sm font-medium hover:bg-green-50 transition duration-150 ease-in-out focus:outline-none shadow-sm"
    >
      <Gauge class="w-6 h-6 mb-1 transition-transform duration-200 group-hover:scale-110" />
      <span>Check Readings</span>
    </button>

    <!-- See Active Reports -->
    <button
      type="button"
      class="group flex flex-col items-center justify-center w-full px-4 py-3 border border-orange-600 text-orange-600 rounded-lg text-sm font-semibold hover:bg-orange-50 transition duration-150 ease-in-out focus:outline-none shadow-sm"
    >
      <FileText class="w-6 h-6 mb-1 text-orange-600 transition-transform duration-200 group-hover:scale-110" />
      <span>See Active Reports</span>
    </button>
  </div>
</section>
<!-- <ReportProblemForm v-if="showReportForm" @close="toggleReportForm" class="mt-6" /> -->

    <!-- Welcome Message -->
    <p class="mt-2 px-4">
      Welcome back, John Doe! Here's your monthly water meter reading and usage overview for Clarin, Bohol.
    </p>

    <!-- Stats Grid -->
    <main class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard title="Total Meter Reading" value="24,567 m³" />
      <StatCard title="This Month's Usage" value="1,322 m³">
        <template #footer><p class="text-red-500">📉 -5% from last month</p></template>
      </StatCard>
      <StatCard title="Active Reports" value="2">
        <template #footer><p>1 pending review</p></template>
      </StatCard>
      <StatCard title="Monthly Meter Reading" value="24,567" />
    </main>
  </div>

</template>
