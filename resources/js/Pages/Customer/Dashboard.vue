<template>
  <CustomerLayout title="Dashboard">
    <div class="w-full bg-white rounded-lg">
      <!-- Header Section -->
      <div class="border-b p-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <v-icon name="bi-droplet" class="text-blue-500" /> AquaTrack
        </h1>

      </div>

      <!-- Main Content -->
      <div class="p-6">
        <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
          <v-icon name="bi-house-door-fill" /> Dashboard
        </h2>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="border rounded-lg p-6">
            <h3 class="text-gray-500 mb-2 flex items-center gap-2">
              <v-icon name="bi-calendar-fill" /> Monthly Usage
            </h3>
            <p class="text-3xl font-bold flex items-center gap-1">
              <v-icon name="bi-water" class="text-blue-500" /> 45 m³
            </p>
          </div>
          <div class="border rounded-lg p-6">
            <h3 class="text-gray-500 mb-2 flex items-center gap-2">
              <v-icon name="bi-cash-stack" /> Current Bill
            </h3>
            <p class="text-3xl font-bold">$120.50</p>
          </div>
          <div class="border rounded-lg p-6">
            <h3 class="text-gray-500 mb-2 flex items-center gap-2">
              <v-icon name="bi-bell-fill" /> Unread Announcements
            </h3>
            <p class="text-3xl font-bold">2</p>
          </div>
        </div>

        <!-- Water Consumption Analytics -->
        <div class="border rounded-lg p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold flex items-center gap-2">
              <v-icon name="bi-graph-up" /> Water Consumption Analytics
            </h3>
            <div class="flex gap-4">
              <div class="flex items-center gap-1">
                <v-icon name="bi-circle-fill" class="text-blue-500 text-xs" />
                <span class="text-sm">Your Usage</span>
              </div>
              <div class="flex items-center gap-1">
                <v-icon name="bi-circle-fill" class="text-gray-400 text-xs" />
                <span class="text-sm">Area Avg</span>
              </div>
            </div>
          </div>
          <h4 class="text-md font-medium mb-2 flex items-center gap-2">
            <v-icon name="bi-calendar-week-fill" /> Weekly Water Consumption
          </h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="flex items-center gap-2">
              <v-icon name="bi-arrow-up-circle-fill" class="text-green-500" />
              <p class="text-gray-600">Year Consumption: <span class="font-bold">540 m³</span></p>
            </div>
            <div class="flex items-center gap-2">
              <v-icon name="bi-dash-circle-fill" class="text-yellow-500" />
              <p class="text-gray-600">Area Average: <span class="font-bold">480 m³</span></p>
            </div>
          </div>

          <!-- Chart Container -->
          <div class="h-64 mt-6">
            <canvas ref="waterChart"></canvas>
          </div>
        </div>

        <!-- Yield Consumption -->
        <div class="border rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <v-icon name="bi-bar-chart-fill" /> Yield Consumption (m³)
          </h3>
          <div class="h-64">
            <canvas ref="yieldChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';

const waterChart = ref(null);
const yieldChart = ref(null);

onMounted(() => {
  // Water Consumption Chart with static data
  new Chart(waterChart.value, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
      datasets: [{
        label: 'Your Consumption',
        data: [35, 42, 38, 45, 50, 48, 52],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.3,
        fill: true
      }, {
        label: 'Area Average',
        data: [30, 38, 35, 42, 45, 43, 47],
        borderColor: 'rgb(156, 163, 175)',
        backgroundColor: 'rgba(156, 163, 175, 0.1)',
        tension: 0.3,
        fill: true,
        borderDash: [5, 5]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          min: 20,
          title: {
            display: true,
            text: 'm³'
          }
        }
      }
    }
  });

  // Yield Consumption Chart with static data
  new Chart(yieldChart.value, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
      datasets: [{
        label: 'Yield Consumption',
        data: [40, 45, 42, 48, 52, 50, 55],
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderColor: 'rgb(59, 130, 246)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          min: 30,
          title: {
            display: true,
            text: 'm³'
          }
        }
      }
    }
  });
});
</script>

<style scoped>
/* Custom styles if needed */
</style>
