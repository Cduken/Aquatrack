<template>
    <StaffLayout title="Staff Dashboard">
        <div class="w-full bg-white shadow-md rounded-lg p-6">
            <!-- Today's Readings Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-4 flex items-center gap-2">
                    <v-icon name="bi-clock-fill" class="text-blue-500" /> Today's Readings
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Main Metric Card -->
                    <div class="bg-blue-50 rounded-lg p-6 flex flex-col items-center justify-center">
                        <div class="flex items-center gap-2">
                            <v-icon name="bi-droplet" class="text-blue-500 text-2xl" />
                            <span class="text-4xl font-bold text-blue-600">2</span>
                        </div>
                        <p class="text-gray-600 mt-2">Readings Today</p>
                    </div>

                    <!-- Recent Activity Card -->
                    <div class="md:col-span-3 bg-white border rounded-lg p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <v-icon name="bi-activity" class="text-blue-500" /> Recent Activity
                        </h2>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <v-icon name="bi-check-circle-fill" class="text-green-500 mt-0.5 mr-2" />
                                <div>
                                    <p>Recorded reading for ACCT-1002 (15 m³)</p>
                                    <p class="text-sm text-gray-500">Today, 10:30 AM</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <v-icon name="bi-check-circle-fill" class="text-green-500 mt-0.5 mr-2" />
                                <div>
                                    <p>Recorded reading for ACCT-1005 (12 m³)</p>
                                    <p class="text-sm text-gray-500">Today, 09:45 AM</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <v-icon name="bi-exclamation-triangle-fill" class="text-red-500 mt-0.5 mr-2" />
                                <div>
                                    <p>High consumption alert for ACCT-1003</p>
                                    <p class="text-sm text-gray-500">Yesterday, 2:15 PM</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Status Sections -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pending Readings -->
                <div class="bg-white border rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <v-icon name="bi-hourglass-top" class="text-yellow-500" /> Pending Readings
                    </h2>
                    <div class="text-center py-8">
                        <v-icon name="bi-file-earmark-text" class="text-gray-400 text-4xl mx-auto mb-2" />
                        <p class="text-gray-500">No pending readings</p>
                    </div>
                </div>

                <!-- Completed Readings -->
                <div class="bg-white border rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <v-icon name="bi-check-circle" class="text-green-500" /> Completed Readings
                    </h2>
                    <div class="text-center py-8">
                        <v-icon name="bi-file-earmark-check-fill" class="text-gray-400 text-4xl mx-auto mb-2" />
                        <p class="text-gray-500">No completed readings today</p>
                    </div>
                </div>

                <!-- Metrics -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-lg p-4 flex flex-col items-center justify-center">
                        <div class="flex items-center gap-1">
                            <v-icon name="bi-calendar-week-fill" class="text-green-500" />
                            <span class="text-3xl font-bold text-green-600">15</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">This Week</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 flex flex-col items-center justify-center">
                        <div class="flex items-center gap-1">
                            <v-icon name="bi-calendar-fill" class="text-purple-500" />
                            <span class="text-3xl font-bold text-purple-600">42</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </StaffLayout>
</template>

<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue';
</script>

<style scoped>
/* Custom styles if needed */
</style>
