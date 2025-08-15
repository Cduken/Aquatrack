<script setup>
import { Link } from '@inertiajs/vue3';
import AddReportModal from '@/Components/Modals/AddReportModal.vue';
import { ref } from 'vue';
import ReportSuccessModal from '@/Components/Modals/ReportSuccessModal.vue';
import TrackReportModal from '@/Components/Modals/TrackReportModal.vue';
import Swal from 'sweetalert2';

const showReportModal = ref(false);
const showSuccessModal = ref(false);
const showTrackModal = ref(false);
const trackingInfo = ref(null);



const handleTrackReport = () => {
    showTrackModal.value = true;
};

const handleReportSubmitted = async (response) => {
    try {
        showReportModal.value = false;

        if (response.success) {
            trackingInfo.value = {
                code: response.trackingCode,
                date: response.dateSubmitted
            };
            showSuccessModal.value = true;

            // Show SweetAlert notification
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Report submitted successfully!',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                background: '#4BB543',
                iconColor: 'white',
                color: 'white',
                timerProgressBar: true,
            });
        } else {
            throw new Error(response.message || 'Failed to submit report');
        }
    } catch (error) {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: error.message,
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            background: '#FF3333',
            iconColor: 'white',
            color: 'white',
            timerProgressBar: true,
        });
    }
};
</script>

<template>
    <main class="relative w-full min-h-screen overflow-hidden">
        <!-- Background elements - image with gradient overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/images/AquatrackIMG.jpg')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#062F64]/80 to-[#1E4272]/80">
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 to-transparent"></div>
        </div>

        <!-- Floating water drop decoration -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-blue-500 opacity-10 blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-96 h-96 rounded-full bg-teal-500 opacity-10 blur-3xl"></div>

        <!-- Main content -->
        <div class="relative z-10 container mx-auto px-6 h-screen flex flex-col justify-start pt-32">
            <!-- Changed justify-center to justify-start and added pt-32 -->
            <div class="max-w-4xl mx-auto text-center">
                <!-- Tagline -->
                <div
                    class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                    <v-icon name="fa-water" class="text-blue-300" />
                    <span class="text-sm font-medium text-blue-100">Water Management System</span>
                </div>

                <!-- Main headline -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200">Track</span>,
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200">Report</span>,
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-200">Resolve</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-lg md:text-xl lg:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed mb-10">
                    AquaTrack empowers you to monitor water usage, report issues instantly, and track resolutions - all
                    in one seamless platform.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link :href="route('reports.index')"
                        class="flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <v-icon name="bi-plus-circle" scale="1.3" />
                        <span>Report Water Issue</span>
                    </Link>

                    <!-- <button @click="handleTrackReport"
                        class="flex items-center gap-3 px-8 py-4 bg-transparent border-2 border-white/30 hover:border-white/50 text-white font-semibold rounded-xl backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                        <v-icon name="io-search" scale="1.3" />
                        <span>Track Report</span>
                    </button> -->
                </div>
            </div>

            <!-- Stats section remains at the bottom -->
            <div class="absolute bottom-[100px] left-0 right-0">
                <div class="max-w-5xl mx-auto px-6 py-4 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-3">
                            <div class="text-2xl md:text-3xl font-bold text-blue-300">1,200+</div>
                            <div class="text-sm text-blue-100">Issues Resolved</div>
                        </div>
                        <div class="p-3 border-x border-white/10">
                            <div class="text-2xl md:text-3xl font-bold text-teal-300">24/7</div>
                            <div class="text-sm text-blue-100">Support Available</div>
                        </div>
                        <div class="p-3">
                            <div class="text-2xl md:text-3xl font-bold text-cyan-300">98%</div>
                            <div class="text-sm text-blue-100">Customer Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-8 right-8 z-20">
            <div class="relative group">
                <button @click="handleTrackReport"
                    class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-teal-500 text-white shadow-xl hover:shadow-2xl transition-all">
                    <v-icon name="io-search" scale="1.3" />
                    <span class="sr-only">Track Report</span>
                    <div
                        class="absolute -right-1 -top-1 w-5 h-5 bg-red-500 rounded-full border-2 border-white animate-pulse">
                    </div>
                </button>

                <!-- Tooltip -->
                <div
                    class="absolute right-16 bottom-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                    <div class="bg-gray-800 text-white text-sm px-3 py-2 rounded-lg whitespace-nowrap shadow-lg">
                        Track Report
                        <div
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1 w-2 h-2 bg-gray-800 rotate-45">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <AddReportModal :show="showReportModal" @close="showReportModal = false" @submitted="handleReportSubmitted" />
        <ReportSuccessModal v-if="trackingInfo" :show="showSuccessModal" :tracking-code="trackingInfo.code"
            :date-submitted="trackingInfo.date" @close="showSuccessModal = false" />
        <TrackReportModal :show="showTrackModal" @close="showTrackModal = false" />
    </main>
</template>
