<template>
    <StaffLayout title="Reading">
        <div class="w-full bg-white shadow-md rounded-lg p-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <v-icon name="bi-droplet" class="text-blue-500" /> AquaTrack
                </h1>

            </div>

            <!-- Content Section -->
            <div>
                <h2 class="text-xl font-semibold mb-6">Reading</h2>

                <!-- Full-width Search Section -->
                <div class="w-full mb-6">
                    <div class="flex gap-4 items-center w-full">
                        <div class="relative flex-grow">

                            <input type="text"
                                class="block w-full pr-3 py-4 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search by Account Number or Name" />
                        </div>
                        <button type="submit"
                            class="px-6 py-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap flex items-center gap-2">
                            <v-icon name="bi-search" class="text-lg" />
                            Search
                        </button>
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
