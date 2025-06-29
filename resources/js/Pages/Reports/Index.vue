<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AquatrackLogo from '@/Components/AquatrackLogo.vue';
import Navbar from '@/Components/Header/Navigation.vue';
import Modal from '@/Components/Modal.vue'; // Make sure this path is correct
import { ref } from 'vue';

defineProps({
    reports: {
        type: Object,
        required: true
    }
});

const selectedReport = ref(null);
const showModal = ref(false);

const openModal = (report) => {
    selectedReport.value = report;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <Navbar>

        <Head title="Reports" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Water Quality Reports
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Location
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ report.purok }}, {{ report.barangay }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ report.municipality }}, {{ report.province }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                                {{ report.description }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(report.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openModal(report)"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for showing report details -->
        <Modal :show="showModal" @close="closeModal">
            <template #title>
                <AquatrackLogo>
                    Report Details
                </AquatrackLogo>
            </template>

            <div v-if="selectedReport" class="space-y-4">
                <!-- Location Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Location Information</h3>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Municipality:</span>
                            <span>{{ selectedReport.municipality }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Province:</span>
                            <span>{{ selectedReport.province }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Barangay:</span>
                            <span>{{ selectedReport.barangay }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Purok/Street:</span>
                            <span>{{ selectedReport.purok }}</span>
                        </div>
                    </div>
                </div>

                <!-- Report Metadata -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Report Information</h3>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Report ID:</span>
                            <span>#{{ selectedReport.id }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Submitted:</span>
                            <span>{{ new Date(selectedReport.created_at).toLocaleString() }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ selectedReport.description }}</p>
                </div>

                <!-- Photos Section -->
                <div v-if="selectedReport.photos && selectedReport.photos.length">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Photos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div v-for="(photo, index) in selectedReport.photos" :key="index" class="relative group">
                            <img :src="'/storage/' + photo.path" :alt="`Report photo ${index + 1}`"
                                class="w-full h-64 object-cover rounded-lg border border-gray-200">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <a :href="'/storage/' + photo.path" target="_blank"
                                    class="text-white bg-blue-600 bg-opacity-80 p-2 rounded-full hover:bg-opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <button @click="closeModal" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </template>
        </Modal>
    </Navbar>
</template>
