<!-- resources/js/Pages/Landing/LandingPage.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
import AddReportModal from '@/Components/Modals/AddReportModal.vue';
import { ref } from 'vue';
import ReportSuccessModal from '@/Components/Modals/ReportSuccessModal.vue';
import TrackReportModal from '@/Components/Modals/TrackReportModal.vue';

const showReportModal = ref(false);
const showSuccessModal = ref(false);
const showTrackModal = ref(false);
const trackingInfo = ref(null);

const handleAddReport = () => {
    showReportModal.value = true;
};

const handleTrackReport = () => {
    showTrackModal.value = true;
};

const handleReportSubmitted = (response) => {
    showReportModal.value = false;
    trackingInfo.value = {
        code: response.trackingCode,
        date: response.dateSubmitted
    };
    showSuccessModal.value = true;
};
</script>

<template>
    <main class="relative pt-16 w-full h-screen">
        <!-- Background elements -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/images/HomePage.png')"></div>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Track Report Button with Tooltip -->
        <div class="absolute top-8 right-8 z-10 group">
            <button @click="handleTrackReport"
                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-lg">
                <v-icon name="io-desktop" scale="1.3" />
                <span class="sr-only">Track Report</span>
            </button>

            <!-- Tooltip -->
            <div
                class="absolute left-1/2 -translate-x-1/2 top-full mt-2 px-3 py-2 text-sm font-medium text-white bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                Track Report
                <div class="absolute left-1/2 -translate-x-1/2 -top-1 w-2 h-2 bg-gray-800 transform rotate-45"></div>
            </div>
        </div>

        <!-- Content container -->
        <div class="relative w-full h-full flex flex-col items-center justify-center text-center px-4 sm:px-6">
            <div class="w-full">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-bold text-white">
                    Report Water Issues with <span class="text-blue-400">AquaTrack</span>
                </h1>

                <p class="mt-6 max-w-5xl mx-auto text-base sm:text-md md:text-lg lg:text-xl text-gray-200">
                    Modern water management system for residential customers. Monitor usage, pay bills, and report
                    issues all in one convenient platform.
                </p>

                <div class="mt-8">
                    <div class="flex gap-4 justify-center">
                        <button @click="handleAddReport"
                            class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-lg font-medium text-white hover:bg-blue-700 transition-colors">
                            <v-icon name="bi-plus-circle" scale="1.3" />
                            <span>Report Now</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Report Form Modal -->
    <AddReportModal :show="showReportModal" @close="showReportModal = false" @submitted="handleReportSubmitted" />

    <!-- Success Modal -->
    <ReportSuccessModal v-if="trackingInfo" :show="showSuccessModal" :tracking-code="trackingInfo.code"
        :date-submitted="trackingInfo.date" @close="showSuccessModal = false" />

    <!-- Track Report Modal -->
    <TrackReportModal :show="showTrackModal" @close="showTrackModal = false" />
</template>
