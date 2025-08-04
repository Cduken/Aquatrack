<template>
  <CustomerLayout title="Customer Reports">
    <div class="w-full bg-white shadow-md rounded-lg">
      <div class="p-6">
        <!-- 🔵 Header with Right-Aligned Button -->
        <div class="flex items-center justify-between border-b pb-4">
          <h1 class="text-2xl font-bold text-gray-800">My Reports</h1>
          <button @click="showModal = true"
            class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">
            📝 Report Issue
          </button>
        </div>

        <!-- 📋 Reports Table -->
        <div class="mt-6 overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barangay</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="report in reports.data" :key="report.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ report.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.barangay }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span :class="statusClasses(report.status ?? 'Pending')">
                    {{ report.status ?? 'Pending' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ new Date(report.created_at).toLocaleDateString() }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div class="flex items-center space-x-4">
                    <button class="text-blue-600 hover:text-blue-800" title="View">
                      <v-icon name="bi-eye-fill" class="w-6 h-6" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 📌 Pagination -->
        <div v-if="reports.links" class="mt-6 flex justify-center space-x-2">
          <button
            v-for="link in reports.links"
            :key="link.label"
            :disabled="!link.url"
            @click="navigate(link.url)"
            v-html="link.label"
            class="px-3 py-1 text-sm rounded border"
            :class="{
              'bg-blue-600 text-white': link.active,
              'bg-white text-gray-700 hover:bg-gray-100': !link.active
            }"
          />
        </div>
      </div>
    </div>

    <!-- 🧩 Modal Component -->
    <ReportIssueModal :show="showModal" @close="showModal = false" />
  </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import ReportIssueModal from '@/Components/Modals/ReportIssueModal.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const showModal = ref(false)

// ✅ Receive paginated reports from Laravel
defineProps({
  reports: Object
})

// 🔹 Status styling
const statusClasses = (status) => {
  return {
    'px-3 py-1 rounded-full text-xs font-semibold': true,
    'bg-blue-100 text-blue-800': status === 'In Progress',
    'bg-green-100 text-green-800': status === 'Resolved',
    'bg-gray-100 text-gray-800': status === 'Pending'
  }
}

// 🔹 Pagination navigation
const navigate = (url) => {
  if (url) {
    router.visit(url, { preserveScroll: true, preserveState: true })
  }
}
</script>

<style scoped>
table {
  width: 100%;
  table-layout: auto;
}
.w-full {
  width: 100%;
}
.p-6 {
  padding: 1.5rem;
}
</style>
