<template>
    <CustomerLayout title="Announcements">
        <div class="w-full bg-white rounded-lg">
            <!-- Header Section -->
            <div class="border-b p-6">
                <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
            </div>

            <!-- Main Content -->
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <v-icon name="bi-bell-fill" scale="1.5" class="text-blue-500" /> Latest Announcements</h2>

                <!-- Announcements List -->
                <div class="space-y-6">
                    <!-- Announcement Item -->
                    <div v-for="announcement in announcements" :key="announcement.id" class="border-b pb-6 last:border-b-0">
                        <h3 class="text-lg font-semibold mb-2">{{ announcement.title }}</h3>
                        <p class="text-gray-700 mb-3">{{ announcement.content }}</p>
                        <p class="text-sm text-gray-500">Posted From: {{ announcement.start_date }} To: {{ announcement.end_date }}</p>
                        <span :class="{
                            'text-green-500': announcement.status === 'Active',
                            'text-red-500': announcement.status === 'Inactive'
                        }" class="text-sm font-medium">
                            {{ announcement.status }}
                        </span>

                    </div>

                    <!-- Empty State -->
                    <div v-if="announcements.length === 0" class="text-center py-8">
                        <p class="text-gray-500">No announcements available at this time.</p>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    announcements: {
        type: Array,
        required: true,
        default: () => []
    }
});
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
