<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch, nextTick, onMounted } from 'vue';
import axios from 'axios';
import QRCode from 'qrcode';
import { OhVueIcon, addIcons } from 'oh-vue-icons';
import { computed } from 'vue';
import html2canvas from 'html2canvas';
import {
    FaSearch,
    IoClose,
    HiStatusOnline,
    BiCalendarDate,
    MdDescriptionOutlined,
    FaMapMarkerAlt,
    FaQrcode,
    BiDownload,
    BiImages,
    BiExclamationTriangle,
    BiPlayCircleFill,
    BiZoomIn
} from 'oh-vue-icons/icons';

addIcons(
    FaSearch,
    IoClose,
    HiStatusOnline,
    BiCalendarDate,
    MdDescriptionOutlined,
    FaMapMarkerAlt,
    FaQrcode,
    BiDownload,
    BiImages,
    BiExclamationTriangle,
    BiPlayCircleFill,
    BiZoomIn
);

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close']);
const reportDetails = ref(null);
const isLoading = ref(false);
const errorMessage = ref(null);
const qrCodeCanvas = ref(null);
const showLoadingDelay = ref(false);
const modalVisible = ref(false);
const modalRef = ref(null);

// Media modal state
const mediaModal = ref({
    show: false,
    src: '',
    type: 'image' // 'image' or 'video'
});

const form = useForm({
    tracking_code: ''
});

// Animation handling
onMounted(() => {
    if (props.show) {
        modalVisible.value = true;
    }
});

watch(() => props.show, (newVal) => {
    modalVisible.value = newVal;
});

const trackReport = async () => {
    isLoading.value = true;
    showLoadingDelay.value = true;
    errorMessage.value = null;
    reportDetails.value = null;

    try {
        const [response] = await Promise.all([
            axios.get(route('reports.find'), {
                params: { tracking_code: form.tracking_code }
            }),
            new Promise(resolve => setTimeout(resolve, 1000))
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
        showLoadingDelay.value = false;
    }
};

const generateQRCode = () => {
    if (!reportDetails.value?.tracking_code || !qrCodeCanvas.value) return;

    QRCode.toCanvas(qrCodeCanvas.value, reportDetails.value.tracking_code,
        {
            width: 200,
            margin: 1,
            color: {
                dark: '#1e40af',
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

const closeModal = () => {
    mediaModal.value.show = false;
    modalVisible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
};

// Open media in modal
const openMediaModal = (src, type) => {
    mediaModal.value = {
        show: true,
        src,
        type
    };
};

// Close media modal
const closeMediaModal = () => {
    mediaModal.value.show = false;
    // Pause any playing videos when closing
    document.querySelectorAll('video').forEach(video => {
        video.pause();
    });
};

// Helper to get video mime type
const getVideoMimeType = (src) => {
    const extension = src.split('.').pop().toLowerCase();
    switch (extension) {
        case 'mp4': return 'video/mp4';
        case 'webm': return 'video/webm';
        case 'ogg': return 'video/ogg';
        default: return 'video/mp4';
    }
};

const downloadReportAsImage = async () => {
    try {
        if (!modalRef.value || !reportDetails.value) return;

        isLoading.value = true;

        // Hide elements we don't want in the screenshot
        const downloadBtn = modalRef.value.querySelector('.download-btn');
        const cancelBtn = modalRef.value.querySelector('.cancel-btn');
        const trackBtn = modalRef.value.querySelector('.track-btn');

        if (downloadBtn) downloadBtn.style.visibility = 'hidden';
        if (cancelBtn) cancelBtn.style.visibility = 'hidden';
        if (trackBtn) trackBtn.style.visibility = 'hidden';

        const canvas = await html2canvas(modalRef.value, {
            scale: 2,
            logging: false,
            useCORS: true,
            scrollY: -window.scrollY,
            windowWidth: modalRef.value.scrollWidth,
            windowHeight: modalRef.value.scrollHeight
        });

        // Create download link
        const link = document.createElement('a');
        link.download = `water-report-${reportDetails.value.tracking_code}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();

        // Restore visibility
        if (downloadBtn) downloadBtn.style.visibility = 'visible';
        if (cancelBtn) cancelBtn.style.visibility = 'visible';
        if (trackBtn) trackBtn.style.visibility = 'visible';
    } catch (error) {
        console.error('Error generating report image:', error);
    } finally {
        isLoading.value = false;
    }
};

const formattedStatus = computed(() => {
    if (!reportDetails.value?.status) return '';
    switch (reportDetails.value.status) {
        case 'in_progress':
            return 'In Progress';
        case 'pending':
            return 'Pending';
        case 'resolved':
            return 'Resolved';
        default:
            return reportDetails.value.status.charAt(0).toUpperCase() + reportDetails.value.status.slice(1).toLowerCase();
    }
});

const formattedPriority = computed(() => {
    if (!reportDetails.value?.priority) return 'Not Specified';
    return reportDetails.value.priority.charAt(0).toUpperCase() + reportDetails.value.priority.slice(1);
});

const isVideoFile = (media) => {
    return media.type === 'video' ||
        (media.mime_type && media.mime_type.includes('video'));
};
</script>

<template>
    <Transition name="modal">
        <div v-if="modalVisible" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Overlay with fade effect -->
            <Transition name="fade">
                <div v-if="modalVisible" class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"
                    @click="closeModal"></div>
            </Transition>

            <!-- Modal container with slide-down animation -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <Transition name="slide-down">
                    <div v-if="modalVisible" ref="modalRef"
                        class="relative transform overflow-hidden rounded-2xl text-left shadow-xl transition-all w-full max-w-4xl bg-white"
                        @click.stop>

                        <!-- Header with modern gradient -->
                        <div class="px-6 py-4 bg-gradient-to-r from-[#062F64] to-[#1E4272]">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                                    <v-icon name="fa-search" scale="1.1" />
                                    <span>Track Water Report</span>
                                </h3>
                                <button @click="closeModal" class="text-white hover:text-blue-200 transition-colors">
                                    <v-icon name="io-close" scale="1.2" />
                                </button>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5">
                            <!-- Search Form -->
                            <form @submit.prevent="trackReport" class="space-y-4">
                                <div>
                                    <label for="tracking_code" class="block text-sm font-medium text-gray-700 mb-1">
                                        Enter Tracking Code
                                    </label>
                                    <div class="relative">
                                        <input v-model="form.tracking_code" id="tracking_code" type="text" required
                                            class="block w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 shadow-sm pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="AQT2025080-1ABCD">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <v-icon name="fa-qrcode" class="text-gray-400" />
                                        </div>
                                    </div>
                                    <Transition name="fade">
                                        <p v-if="errorMessage"
                                            class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <v-icon name="io-close" class="text-red-500" />
                                            <span>{{ errorMessage }}</span>
                                        </p>
                                    </Transition>
                                </div>
                            </form>

                            <!-- Loading State with bounce animation -->
                            <Transition name="bounce">
                                <div v-if="isLoading || showLoadingDelay" class="mt-6 text-center py-8">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="relative">
                                            <div
                                                class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <v-icon name="fa-water" class="text-blue-500 text-2xl animate-pulse" />
                                            </div>
                                        </div>
                                        <p class="text-lg text-gray-600">Searching for your water report...</p>
                                    </div>
                                </div>
                            </Transition>

                            <!-- Report Details with fade-in animation -->
                            <Transition name="fade">
                                <div v-if="reportDetails" class="mt-8 border-t border-gray-200 pt-6">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                        <v-icon name="hi-status-online" class="text-blue-500" />
                                        <span>Report Details</span>
                                    </h4>

                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        <!-- Left Column -->
                                        <div class="lg:col-span-2 space-y-4">
                                            <!-- Top Row -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                    <p
                                                        class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        TRACKING CODE</p>
                                                    <p class="text-lg font-mono font-bold text-gray-900 mt-1">{{
                                                        reportDetails.tracking_code }}</p>
                                                </div>
                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                    <p
                                                        class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        REPORTED NAME</p>
                                                    <p class="text-lg font-semibold text-gray-900 mt-1">{{
                                                        reportDetails.reporter_name }}</p>
                                                </div>
                                            </div>

                                            <!-- Middle Row -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                    <p
                                                        class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                                        <v-icon name="hi-status-online" class="text-gray-400"
                                                            scale="0.8" />
                                                        <span>STATUS</span>
                                                    </p>
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                            :class="{
                                                                'bg-green-100 text-green-800': reportDetails.status === 'resolved',
                                                                'bg-yellow-100 text-yellow-800': reportDetails.status === 'pending',
                                                                'bg-blue-100 text-blue-800': reportDetails.status === 'in_progress'
                                                            }">
                                                            {{ formattedStatus }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                    <p
                                                        class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                                        <v-icon name="bi-exclamation-triangle" class="text-gray-400"
                                                            scale="0.8" />
                                                        <span>PRIORITY</span>
                                                    </p>
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                            :class="{
                                                                'bg-red-100 text-red-800': reportDetails.priority === 'high',
                                                                'bg-yellow-100 text-yellow-800': reportDetails.priority === 'medium',
                                                                'bg-green-100 text-green-800': reportDetails.priority === 'low'
                                                            }">
                                                            {{ formattedPriority }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                <p
                                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                                    <v-icon name="md-description-outlined" class="text-gray-400"
                                                        scale="0.8" />
                                                    <span>DESCRIPTION</span>
                                                </p>
                                                <p class="text-gray-700 mt-2 whitespace-pre-line">{{
                                                    reportDetails.description }}</p>
                                            </div>

                                            <!-- Location -->
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                <p
                                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                                    <v-icon name="fa-map-marker-alt" class="text-gray-400"
                                                        scale="0.8" />
                                                    <span>LOCATION</span>
                                                </p>
                                                <p class="text-gray-700 mt-2">
                                                    {{ reportDetails.purok }}, {{ reportDetails.barangay }},
                                                    {{ reportDetails.municipality }}, {{ reportDetails.province }}
                                                </p>
                                            </div>

                                            <!-- Photos/Videos Section -->
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200"
                                                v-if="reportDetails.photos && reportDetails.photos.length">
                                                <p
                                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                                    <v-icon name="bi-images" class="text-gray-400" scale="0.8" />
                                                    <span>MEDIA</span>
                                                </p>
                                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                    <div v-for="(media, index) in reportDetails.photos" :key="index"
                                                        class="relative group overflow-hidden rounded-lg border border-gray-200 h-32">
                                                        <!-- Video Thumbnail with Play Button -->
                                                        <template v-if="isVideoFile(media)">
                                                            <div
                                                                class="w-full h-full bg-gray-800 flex items-center justify-center">
                                                                <video
                                                                    class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100">
                                                                    <source :src="'/storage/' + media.path"
                                                                        :type="media.mime_type">
                                                                </video>
                                                                <div
                                                                    class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-all">
                                                                    <v-icon name="bi-play-circle-fill"
                                                                        class="text-white text-4xl" />
                                                                </div>
                                                            </div>
                                                            <a href="#"
                                                                @click.prevent="openMediaModal('/storage/' + media.path, 'video')"
                                                                class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all">
                                                            </a>
                                                        </template>

                                                        <!-- Image Thumbnail -->
                                                        <template v-else>
                                                            <img :src="'/storage/' + media.path"
                                                                :alt="`Report photo ${index + 1}`"
                                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                            <a :href="'/storage/' + media.path" target="_blank"
                                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                                <v-icon name="bi-zoom-in"
                                                                    class="text-white bg-black/50 p-1.5 rounded-full" />
                                                            </a>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column - QR Code -->
                                        <div class="flex flex-col items-center justify-start space-y-4">
                                            <div
                                                class="w-full bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                                <h5 class="text-sm font-medium text-blue-700 mb-3 text-center">Tracking
                                                    QR Code</h5>
                                                <div
                                                    class="bg-white p-4 rounded-lg border border-blue-200 shadow-inner">
                                                    <canvas ref="qrCodeCanvas"
                                                        class="w-full h-auto max-w-[200px] mx-auto"></canvas>
                                                </div>
                                                <p class="mt-4 text-xs text-blue-600 text-center">
                                                    Scan this code to view report details
                                                </p>
                                            </div>

                                            <button
                                                class="download-btn flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors w-full"
                                                @click="downloadReportAsImage">
                                                <v-icon name="bi-download" />
                                                <span>Download Report</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-between border-t border-gray-200">
                            <button @click="reportDetails ? resetForm() : closeModal()"
                                class="cancel-btn inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                                <v-icon name="io-close" class="mr-2" scale="0.9" />
                                {{ reportDetails ? 'Search Again' : 'Cancel' }}
                            </button>
                            <button v-if="!reportDetails" type="submit" @click="trackReport" :disabled="isLoading"
                                class="track-btn inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition-all">
                                <v-icon name="fa-search" class="mr-2" scale="0.9" />
                                <span v-if="!isLoading">Track Report</span>
                                <span v-else>Tracking...</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </Transition>

    <!-- Media Modal -->
    <Transition name="modal">
        <div v-if="mediaModal.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90">
            <div class="relative w-full max-w-4xl mx-4">
                <button @click="closeMediaModal" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                    <v-icon name="io-close" scale="1.5" />
                </button>

                <div v-if="mediaModal.type === 'video'" class="aspect-video w-full">
                    <video controls autoplay class="w-full h-full">
                        <source :src="mediaModal.src" :type="getVideoMimeType(mediaModal.src)">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <img v-else :src="mediaModal.src" class="max-h-[80vh] mx-auto object-contain">
            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Slide down animation (like SweetAlert) */
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.slide-down-enter-from,
.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Fade animation */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Modal transition */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* Bounce animation for loading */
.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0.9);
        opacity: 0;
    }

    50% {
        transform: scale(1.05);
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Media hover effects */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

.group:hover .group-hover\:bg-black\/20 {
    background-color: rgba(0, 0, 0, 0.2);
}

.group:hover .group-hover\:bg-black\/10 {
    background-color: rgba(0, 0, 0, 0.1);
}

/* Video hover transition */
video {
    transition: opacity 0.3s ease;
}
</style>
