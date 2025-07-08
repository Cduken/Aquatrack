<template>
  <div class="p-6 space-y-8">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-6 rounded-md shadow">
      <h1 class="text-2xl font-bold">Customer Dashboard</h1>
      <p class="mt-1">Welcome, {{ userName }} — here’s your latest water usage and report summary for {{ location }}.</p>
    </header>

    <!-- Stats Grid -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <DashboardCard title="Total Meter Reading" :value="`${reading.toLocaleString()} m³`" />
      <DashboardCard title="This Month's Usage" :value="`${monthlyUsage} m³`">
        <template #footer>
          <p :class="usageChange < 0 ? 'text-red-500' : 'text-green-500'">
            {{ usageChange > 0 ? '📈' : '📉' }} {{ usageChange }}% from last month
          </p>
        </template>
      </DashboardCard>
      <DashboardCard title="Active Reports" :value="activeReports">
        <template #footer>
          <p>{{ pendingReports }} pending review</p>
        </template>
      </DashboardCard>
      <DashboardCard title="Monthly Reading" :value="reading.toLocaleString()" />
    </section>

    <!-- Quick Actions -->
    <section>
      <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
      <div class="flex flex-wrap gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Report a Problem</button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">View Interruptions</button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Check Readings</button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">See Active Reports</button>
      </div>
    </section>

    <!-- Placeholder Chart -->
    <section>
      <h2 class="text-lg font-semibold mb-4">Weekly Water Usage</h2>
      <div class="bg-white p-6 rounded-md shadow text-center text-gray-500">
        [Weekly Usage Chart Placeholder]
      </div>
    </section>
  </div>
</template>

<script setup>
defineProps({
  userName: String,
  location: String,
  reading: Number,
  monthlyUsage: Number,
  usageChange: Number,
  activeReports: Number,
  pendingReports: Number
});
</script>
