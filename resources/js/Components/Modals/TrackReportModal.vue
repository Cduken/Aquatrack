<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';
import QRCode from 'qrcode';
import { OhVueIcon, addIcons } from 'oh-vue-icons';
import {
    FaSearch,
    IoClose,
    HiStatusOnline,
    BiCalendarDate,
    MdDescriptionOutlined,
    FaMapMarkerAlt,
    FaQrcode,
    BiDownload
} from 'oh-vue-icons/icons';

addIcons(
    FaSearch,
    IoClose,
    HiStatusOnline,
    BiCalendarDate,
    MdDescriptionOutlined,
    FaMapMarkerAlt,
    FaQrcode,
    BiDownload
);

const emit = defineEmits(['close']);
const reportDetails = ref(null);
const isLoading = ref(false);
const errorMessage = ref(null);
const qrCodeCanvas = ref(null);
const showLoadingDelay = ref(false); // New loading state for delay

const form = useForm({
    tracking_code: ''
});

const trackReport = async () => {
    isLoading.value = true;
    showLoadingDelay.value = true; // Show loading animations
    errorMessage.value = null;
    reportDetails.value = null; // Clear previous results

    try {
        // Add minimum 1 second loading delay
        const [response] = await Promise.all([
            axios.get(route('reports.find'), {
                params: { tracking_code: form.tracking_code }
            }),
            new Promise(resolve => setTimeout(resolve, 2000)) // Minimum 1 second delay
        ]);

        reportDetails.value = response.data;
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorMessage.value = 'No report found with this tracking code.';
        } else {
            errorMessage.value = 'An error occurred while tracking the report.';
        }
    } finally {
        isLoading.value = false;
        showLoadingDelay.value = false; // Hide loading animation
    }
};

const generateQRCode = () => {
    if (!reportDetails.value?.tracking_code || !qrCodeCanvas.value) return;

    QRCode.toCanvas(qrCodeCanvas.value, reportDetails.value.tracking_code,
        {
            width: 200,
            margin: 1,
            color: {
                dark: '#000',
                light: '#ffffff'
            }
        },
        (error) => {
            if (error) console.error('QR Code generation error:', error);
        }
    );
};

const resetForm = () => {
    form.reset();
    reportDetails.value = null;
    errorMessage.value = null;
};

watch(reportDetails, () => {
    if (reportDetails.value) {
        nextTick(() => {
            generateQRCode();
        });
    }
}, { deep: true });
</script>

<template>
    <Modal @close="emit('close')" max-width="7xl" :isResizable="true" :initialWidth="'90vw'">
        <template #title>
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                    <v-icon name="fa-search" scale="1.1" />
                    <span>Tracking</span>
                </h3>
            </div>
        </template>

        <div class="px-6 py-5">
            <form @submit.prevent="trackReport" class="space-y-4">
                <div>
                    <label for="tracking_code" class="block text-sm font-medium text-gray-700 mb-1">
                        Enter Tracking Code
                    </label>
                    <div class="relative">
                        <input v-model="form.tracking_code" id="tracking_code" type="text" required
                            class="block w-full rounded-md border-gray-300 shadow-sm pl-10 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="AQT-20230801-ABCD">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <v-icon name="fa-qrcode" class="text-gray-400" />
                        </div>
                    </div>
                    <p v-if="errorMessage" class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <v-icon name="io-close" class="text-red-500" />
                        <span>{{ errorMessage }}</span>
                    </p>
                </div>
            </form>

            <div v-if="isLoading || showLoadingDelay" class="mt-6 text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                <p class="mt-3 text-sm text-gray-500">Searching for report...</p>
            </div>

            <!-- Report Details Section -->
            <div v-if="reportDetails" class="mt-8 border-t border-gray-200 pt-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <v-icon name="hi-status-online" class="text-blue-500" />
                    <span>Report Details</span>
                </h4>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">TRACKING CODE</p>
                                <p class="text-lg font-mono text-gray-900 mt-1">{{ reportDetails.tracking_code }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">REPORTED NAME</p>
                                <p class="text-lg text-gray-900 mt-1">{{ reportDetails.reporter_name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                    <v-icon name="hi-status-online" class="text-gray-400" scale="0.8" />
                                    <span>IN STATUS</span>
                                </p>
                                <p class="text-lg font-medium mt-1" :class="{
                                    'text-green-600': reportDetails.status === 'resolved',
                                    'text-yellow-600': reportDetails.status === 'pending',
                                    'text-red-600': reportDetails.status === 'cancelled'
                                }">
                                    {{ reportDetails.status }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                    <v-icon name="bi-calendar-date" class="text-gray-400" scale="0.8" />
                                    <span>DATE SUBMITTED</span>
                                </p>
                                <p class="text-lg text-gray-900 mt-1">{{ new
                                    Date(reportDetails.created_at).toLocaleString() }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p
                                class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                <v-icon name="md-description-outlined" class="text-gray-400" scale="0.8" />
                                <span>DESCRIPTION</span>
                            </p>
                            <p class="text-gray-900 mt-2 whitespace-pre-line">{{ reportDetails.description }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p
                                class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                <v-icon name="fa-map-marker-alt" class="text-gray-400" scale="0.8" />
                                <span>LOCATION</span>
                            </p>
                            <p class="text-gray-900 mt-2">
                                {{ reportDetails.purok }}, {{ reportDetails.barangay }},
                                {{ reportDetails.municipality }}, {{ reportDetails.province }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center border border-gray-200 rounded-lg p-6 bg-white">
                        <h5 class="text-sm font-medium text-gray-500 mb-3">Tracking QR Code</h5>
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <canvas ref="qrCodeCanvas" class="w-48 h-48"></canvas>
                        </div>
                        <p class="mt-4 text-xs text-gray-500 text-center">
                            Scan this code to view report details
                        </p>

                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-between w-full">
                <button type="button" @click="reportDetails ? resetForm() : emit('close')"
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <v-icon name="io-close" class="mr-2" scale="0.9" />
                    {{ reportDetails ? 'Search Again' : 'Cancel' }}
                </button>
                <button v-if="!reportDetails" type="submit" @click="trackReport" :disabled="isLoading"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <v-icon name="fa-search" class="mr-2" scale="0.9" />
                    <span v-if="!isLoading">Track</span>
                    <span v-else>Tracking...</span>
                </button>
            </div>
        </template>
    </Modal>
</template>
