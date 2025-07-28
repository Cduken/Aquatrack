<template>
    <CustomerLayout title="Announcements">
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
                    <v-icon name="bi-megaphone-fill" /> Announcements
                </h2>

                <!-- Announcements List -->
                <div class="space-y-6">
                    <!-- Announcement 1 -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                            <v-icon name="bi-tools" class="text-yellow-500" /> Scheduled Maintenance
                        </h3>
                        <p class="text-gray-700 mb-3">
                            There will be scheduled maintenance on June 15th from 2AM to 4AM. Water supply might be
                            interrupted during this period.
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <v-icon name="bi-calendar" class="text-gray-400" /> Posted: 2023-06-01
                        </p>
                    </div>

                    <!-- Announcement 2 -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                            <v-icon name="bi-credit-card" class="text-blue-500" /> New Billing System
                        </h3>
                        <p class="text-gray-700 mb-3">
                            We've upgraded our billing system with new features. You can now view detailed usage history
                            and set up automatic payments.
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <v-icon name="bi-calendar" class="text-gray-400" /> Posted: 2023-05-20
                        </p>
                    </div>

                    <!-- Announcement 3 -->
                    <div class="pb-6">
                        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                            <v-icon name="bi-lightbulb" class="text-green-500" /> Water Conservation Tips
                        </h3>
                        <p class="text-gray-700 mb-3">
                            With summer approaching, here are some tips to reduce your water consumption: Fix leaks
                            promptly, water plants early morning or late evening, and take shorter showers.
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <v-icon name="bi-calendar" class="text-gray-400" /> Posted: 2023-05-05
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
</script>

<style scoped>
/* Custom styles if needed */
</style>
