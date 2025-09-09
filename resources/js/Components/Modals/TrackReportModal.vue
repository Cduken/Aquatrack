<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, nextTick, onMounted, onUnmounted } from "vue";
import axios from "axios";
import QRCode from "qrcode";
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { computed } from "vue";
import html2canvas from "html2canvas";
import jsQR from "jsqr";
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
    BiZoomIn,
    FaCamera,
    BiArrowLeft,
} from "oh-vue-icons/icons";

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
    BiZoomIn,
    FaCamera,
    BiArrowLeft
);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    initialTrackingCode: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["close"]);
const reportDetails = ref(null);
const isLoading = ref(false);
const errorMessage = ref(null);
const qrCodeCanvas = ref(null);
const showLoadingDelay = ref(false);
const modalVisible = ref(false);
const modalRef = ref(null);

// QR Scanner state
const showQrScanner = ref(false);
const videoRef = ref(null);
const canvasRef = ref(null);
const stream = ref(null);
const scanning = ref(false);
const scannerError = ref(null);

// Media modal state
const mediaModal = ref({
    show: false,
    src: "",
    type: "image",
});

const form = useForm({
    tracking_code: props.initialTrackingCode,
});

onMounted(() => {
    if (props.show) {
        modalVisible.value = true;
        if (props.initialTrackingCode) {
            trackReport();
        }
    }
});

onUnmounted(() => {
    stopCamera();
});

watch(
    () => props.show,
    (newVal) => {
        modalVisible.value = newVal;
        if (newVal && props.initialTrackingCode) {
            form.tracking_code = props.initialTrackingCode;
            trackReport();
        }
    }
);

const trackReport = async () => {
    if (!form.tracking_code) return;

    isLoading.value = true;
    showLoadingDelay.value = true;
    errorMessage.value = null;
    reportDetails.value = null;

    try {
        const [response] = await Promise.all([
            axios.get(route("reports.find"), {
                params: { tracking_code: form.tracking_code },
            }),
            new Promise((resolve) => setTimeout(resolve, 800)),
        ]);

        reportDetails.value = response.data;
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorMessage.value = "Report not found with this tracking code.";
        } else {
            errorMessage.value = "Search failed. Please try again.";
        }
    } finally {
        isLoading.value = false;
        showLoadingDelay.value = false;
    }
};

// QR Scanner functions
const startQrScanner = async () => {
    try {
        scannerError.value = null;
        showQrScanner.value = true;

        stream.value = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "environment",
            },
        });

        await nextTick();

        if (videoRef.value) {
            videoRef.value.srcObject = stream.value;
            videoRef.value.play();
            scanning.value = true;
            scanQrCode();
        }
    } catch (error) {
        console.error("Camera access error:", error);
        scannerError.value = "Camera access denied. Please check permissions.";
        showQrScanner.value = false;
    }
};

const stopCamera = () => {
    if (stream.value) {
        stream.value.getTracks().forEach((track) => track.stop());
        stream.value = null;
    }
    scanning.value = false;
};

const closeQrScanner = () => {
    stopCamera();
    showQrScanner.value = false;
    scannerError.value = null;
};

const scanQrCode = () => {
    if (!scanning.value || !videoRef.value || !canvasRef.value) return;

    const video = videoRef.value;
    const canvas = canvasRef.value;
    const context = canvas.getContext("2d");

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = context.getImageData(
            0,
            0,
            canvas.width,
            canvas.height
        );
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            form.tracking_code = code.data;
            closeQrScanner();
            nextTick(() => {
                trackReport();
            });
            return;
        }
    }

    if (scanning.value) {
        requestAnimationFrame(scanQrCode);
    }
};

const generateQRCode = () => {
    if (!reportDetails.value?.tracking_code || !qrCodeCanvas.value) return;

    QRCode.toCanvas(
        qrCodeCanvas.value,
        reportDetails.value.tracking_code,
        {
            width: 140,
            margin: 1,
            color: {
                dark: "#1e293b",
                light: "#ffffff",
            },
        },
        (error) => {
            if (error) console.error("QR Code generation error:", error);
        }
    );
};

const resetForm = () => {
    form.reset();
    reportDetails.value = null;
    errorMessage.value = null;
};

watch(
    reportDetails,
    () => {
        if (reportDetails.value) {
            nextTick(() => {
                generateQRCode();
            });
        }
    },
    { deep: true }
);

const closeModal = () => {
    closeQrScanner();
    mediaModal.value.show = false;
    modalVisible.value = false;
    setTimeout(() => {
        emit("close");
    }, 200);
};

const openMediaModal = (src, type) => {
    mediaModal.value = {
        show: true,
        src,
        type,
    };
};

const closeMediaModal = () => {
    mediaModal.value.show = false;
    document.querySelectorAll("video").forEach((video) => {
        video.pause();
    });
};

const getVideoMimeType = (src) => {
    const extension = src.split(".").pop().toLowerCase();
    switch (extension) {
        case "mp4":
            return "video/mp4";
        case "webm":
            return "video/webm";
        case "ogg":
            return "video/ogg";
        default:
            return "video/mp4";
    }
};

const downloadReportAsImage = async () => {
    try {
        if (!modalRef.value || !reportDetails.value) return;

        isLoading.value = true;

        const downloadBtn = modalRef.value.querySelector(".download-btn");
        const resetBtn = modalRef.value.querySelector(".reset-btn");
        const trackBtn = modalRef.value.querySelector(".track-btn");

        if (downloadBtn) downloadBtn.style.visibility = "hidden";
        if (resetBtn) resetBtn.style.visibility = "hidden";
        if (trackBtn) trackBtn.style.visibility = "hidden";

        const canvas = await html2canvas(modalRef.value, {
            scale: 2,
            logging: false,
            useCORS: true,
            scrollY: -window.scrollY,
            windowWidth: modalRef.value.scrollWidth,
            windowHeight: modalRef.value.scrollHeight,
        });

        const link = document.createElement("a");
        link.download = `water-report-${reportDetails.value.tracking_code}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();

        if (downloadBtn) downloadBtn.style.visibility = "visible";
        if (resetBtn) resetBtn.style.visibility = "visible";
        if (trackBtn) trackBtn.style.visibility = "visible";
    } catch (error) {
        console.error("Error generating report image:", error);
    } finally {
        isLoading.value = false;
    }
};

const formattedStatus = computed(() => {
    if (!reportDetails.value?.status) return "";
    switch (reportDetails.value.status) {
        case "in_progress":
            return "In Progress";
        case "pending":
            return "Pending";
        case "resolved":
            return "Resolved";
        default:
            return (
                reportDetails.value.status.charAt(0).toUpperCase() +
                reportDetails.value.status.slice(1).toLowerCase()
            );
    }
});

const formattedPriority = computed(() => {
    if (!reportDetails.value?.priority) return "Not Specified";
    return (
        reportDetails.value.priority.charAt(0).toUpperCase() +
        reportDetails.value.priority.slice(1)
    );
});

const isVideoFile = (media) => {
    return (
        media.type === "video" ||
        (media.mime_type && media.mime_type.includes("video"))
    );
};
</script>

<template>
    <Transition name="modal-backdrop">
        <div
            v-if="modalVisible"
            class="fixed inset-0 z-[500] flex items-center justify-center p-3"
        >
            <!-- Backdrop -->
            <div
                class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-all duration-300"
                @click="closeModal"
            ></div>

            <!-- Modal -->
            <Transition name="modal-content">
                <div
                    v-if="modalVisible"
                    ref="modalRef"
                    class="relative w-full max-w-4xl max-h-[95vh] bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300"
                    @click.stop
                >
                    <!-- Compact Header -->
                    <div
                        class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 bg-blue-500/20 rounded-xl border border-blue-400/30"
                                >
                                    <v-icon
                                        name="fa-search"
                                        class="text-blue-400"
                                        scale="1.1"
                                    />
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">
                                        Track Water Report
                                    </h3>
                                    <p class="text-slate-300 text-sm">
                                        Enter tracking code to view status
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="closeModal"
                                class="p-2 hover:bg-white/10 rounded-xl text-white/70 hover:text-white transition-all"
                            >
                                <v-icon name="io-close" scale="1.2" />
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="max-h-[calc(95vh-140px)] overflow-y-auto">
                        <div class="p-6 space-y-6">
                            <!-- Search Form -->
                            <form
                                @submit.prevent="trackReport"
                                class="space-y-4"
                            >
                                <div class="space-y-2">
                                    <label
                                        class="block text-sm font-semibold text-slate-700"
                                    >
                                        Tracking Code
                                    </label>
                                    <div class="relative">
                                        <input
                                            v-model="form.tracking_code"
                                            type="text"
                                            required
                                            class="w-full h-12 pl-12 pr-14 text-base bg-slate-50 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:bg-white transition-all placeholder:text-slate-400 font-mono"
                                            placeholder="AQT2025080-1ABCD"
                                        />
                                        <div
                                            class="absolute left-3 top-1/2 -translate-y-1/2 p-1.5 bg-slate-300 rounded-lg"
                                        >
                                            <v-icon
                                                name="fa-qrcode"
                                                scale="0.9"
                                                class="text-slate-600"
                                            />
                                        </div>
                                        <button
                                            type="button"
                                            @click="startQrScanner"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-slate-700 hover:bg-slate-800 text-white rounded-lg transition-all hover:scale-105"
                                            title="Scan QR Code"
                                        >
                                            <v-icon
                                                name="fa-camera"
                                                scale="0.85"
                                            />
                                        </button>
                                    </div>
                                    <Transition name="error-slide">
                                        <div
                                            v-if="errorMessage"
                                            class="flex items-center space-x-2 p-3 bg-red-50 border border-red-200 rounded-lg"
                                        >
                                            <v-icon
                                                name="io-close"
                                                class="text-red-500"
                                                scale="0.8"
                                            />
                                            <span
                                                class="text-red-700 text-sm font-medium"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </Transition>
                                </div>
                            </form>

                            <!-- Loading State -->
                            <Transition name="loading-bounce">
                                <div
                                    v-if="isLoading || showLoadingDelay"
                                    class="flex flex-col items-center justify-center py-12 space-y-4"
                                >
                                    <div class="relative">
                                        <div
                                            class="w-16 h-16 border-4 border-slate-200 rounded-full"
                                        ></div>
                                        <div
                                            class="absolute inset-0 w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
                                        ></div>
                                    </div>
                                    <div class="text-center">
                                        <h4
                                            class="text-base font-semibold text-slate-700"
                                        >
                                            Searching...
                                        </h4>
                                        <p class="text-sm text-slate-500">
                                            Please wait
                                        </p>
                                    </div>
                                </div>
                            </Transition>

                            <!-- Report Found -->
                            <Transition name="content-slide">
                                <div v-if="reportDetails" class="space-y-6">
                                    <!-- Status Banner -->
                                    <div
                                        class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4 border border-green-200"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <div
                                                    class="p-2 bg-green-100 rounded-lg"
                                                >
                                                    <v-icon
                                                        name="hi-status-online"
                                                        class="text-green-600"
                                                        scale="1.1"
                                                    />
                                                </div>
                                                <div>
                                                    <h4
                                                        class="text-lg font-bold text-slate-800"
                                                    >
                                                        Report Found
                                                    </h4>
                                                    <p
                                                        class="text-sm text-slate-600"
                                                    >
                                                        Code:
                                                        <span
                                                            class="font-mono font-semibold"
                                                            >{{
                                                                reportDetails.tracking_code
                                                            }}</span
                                                        >
                                                    </p>
                                                </div>
                                            </div>
                                            <span
                                                class="px-3 py-1 rounded-lg text-sm font-semibold"
                                                :class="{
                                                    'bg-green-100 text-green-700':
                                                        reportDetails.status ===
                                                        'resolved',
                                                    'bg-yellow-100 text-yellow-700':
                                                        reportDetails.status ===
                                                        'pending',
                                                    'bg-blue-100 text-blue-700':
                                                        reportDetails.status ===
                                                        'in_progress',
                                                }"
                                            >
                                                {{ formattedStatus }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content Grid -->
                                    <div
                                        class="grid grid-cols-1 lg:grid-cols-4 gap-6"
                                    >
                                        <!-- Main Content -->
                                        <div class="lg:col-span-3 space-y-4">
                                            <!-- Reporter & Priority -->
                                            <div
                                                class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                            >
                                                <div
                                                    class="bg-slate-50 rounded-xl p-4"
                                                >
                                                    <div
                                                        class="flex items-center space-x-2 mb-2"
                                                    >
                                                        <v-icon
                                                            name="hi-status-online"
                                                            class="text-slate-500"
                                                            scale="0.8"
                                                        />
                                                        <span
                                                            class="text-xs font-semibold text-slate-500 uppercase"
                                                            >Reporter</span
                                                        >
                                                    </div>
                                                    <p
                                                        class="font-semibold text-slate-800"
                                                    >
                                                        {{
                                                            reportDetails.reporter_name
                                                        }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="bg-slate-50 rounded-xl p-4"
                                                >
                                                    <div
                                                        class="flex items-center space-x-2 mb-2"
                                                    >
                                                        <v-icon
                                                            name="bi-exclamation-triangle"
                                                            class="text-slate-500"
                                                            scale="0.8"
                                                        />
                                                        <span
                                                            class="text-xs font-semibold text-slate-500 uppercase"
                                                            >Priority</span
                                                        >
                                                    </div>
                                                    <span
                                                        class="inline-flex px-2 py-1 rounded-lg text-sm font-semibold"
                                                        :class="{
                                                            'bg-red-100 text-red-700':
                                                                reportDetails.priority ===
                                                                'high',
                                                            'bg-yellow-100 text-yellow-700':
                                                                reportDetails.priority ===
                                                                'medium',
                                                            'bg-green-100 text-green-700':
                                                                reportDetails.priority ===
                                                                'low',
                                                        }"
                                                    >
                                                        {{ formattedPriority }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div
                                                class="bg-slate-50 rounded-xl p-4"
                                            >
                                                <div
                                                    class="flex items-center space-x-2 mb-3"
                                                >
                                                    <v-icon
                                                        name="md-description-outlined"
                                                        class="text-slate-500"
                                                        scale="0.8"
                                                    />
                                                    <span
                                                        class="text-xs font-semibold text-slate-500 uppercase"
                                                        >Report Description</span
                                                    >
                                                </div>
                                                <p
                                                    class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"
                                                >
                                                    {{
                                                        reportDetails.description
                                                    }}
                                                </p>
                                            </div>

                                            <!-- Location -->
                                            <div
                                                class="bg-slate-50 rounded-xl p-4"
                                            >
                                                <div
                                                    class="flex items-center space-x-2 mb-3"
                                                >
                                                    <v-icon
                                                        name="fa-map-marker-alt"
                                                        class="text-slate-500"
                                                        scale="0.8"
                                                    />
                                                    <span
                                                        class="text-xs font-semibold text-slate-500 uppercase"
                                                        >Location</span
                                                    >
                                                </div>
                                                <p
                                                    class="text-slate-700 text-sm"
                                                >
                                                    {{ reportDetails.purok }},
                                                    {{
                                                        reportDetails.barangay
                                                    }},
                                                    {{
                                                        reportDetails.municipality
                                                    }},
                                                    {{
                                                        reportDetails.province
                                                    }}, {{ reportDetails.zone }}
                                                </p>
                                            </div>

                                            <!-- Media -->
                                            <div
                                                v-if="
                                                    reportDetails.photos &&
                                                    reportDetails.photos.length
                                                "
                                                class="bg-slate-50 rounded-xl p-4"
                                            >
                                                <div
                                                    class="flex items-center space-x-2 mb-3"
                                                >
                                                    <v-icon
                                                        name="bi-images"
                                                        class="text-slate-500"
                                                        scale="0.8"
                                                    />
                                                    <span
                                                        class="text-xs font-semibold text-slate-500 uppercase"
                                                        >Media</span
                                                    >
                                                </div>
                                                <div
                                                    class="grid grid-cols-3 sm:grid-cols-4 gap-3"
                                                >
                                                    <div
                                                        v-for="(
                                                            media, index
                                                        ) in reportDetails.photos"
                                                        :key="index"
                                                        class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-200 hover:border-blue-300 transition-all hover:shadow-md"
                                                    >
                                                        <template
                                                            v-if="
                                                                isVideoFile(
                                                                    media
                                                                )
                                                            "
                                                        >
                                                            <div
                                                                class="w-full h-full bg-slate-600 flex items-center justify-center"
                                                            >
                                                                <video
                                                                    class="absolute inset-0 w-full h-full object-cover"
                                                                >
                                                                    <source
                                                                        :src="
                                                                            '/storage/' +
                                                                            media.path
                                                                        "
                                                                        :type="
                                                                            media.mime_type
                                                                        "
                                                                    />
                                                                </video>
                                                                <div
                                                                    class="absolute inset-0 bg-black/40 flex items-center justify-center"
                                                                >
                                                                    <v-icon
                                                                        name="bi-play-circle-fill"
                                                                        class="text-white text-xl"
                                                                    />
                                                                </div>
                                                            </div>
                                                            <button
                                                                @click="
                                                                    openMediaModal(
                                                                        '/storage/' +
                                                                            media.path,
                                                                        'video'
                                                                    )
                                                                "
                                                                class="absolute inset-0"
                                                            ></button>
                                                        </template>
                                                        <template v-else>
                                                            <img
                                                                :src="
                                                                    '/storage/' +
                                                                    media.path
                                                                "
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                                            />
                                                            <div
                                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center"
                                                            >
                                                                <div
                                                                    class="opacity-0 group-hover:opacity-100 transition-opacity"
                                                                >
                                                                    <v-icon
                                                                        name="bi-zoom-in"
                                                                        class="text-white bg-black/50 p-1 rounded-full"
                                                                    />
                                                                </div>
                                                            </div>
                                                            <a
                                                                :href="
                                                                    '/storage/' +
                                                                    media.path
                                                                "
                                                                target="_blank"
                                                                class="absolute inset-0"
                                                            ></a>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- QR Code Sidebar -->
                                        <div class="space-y-4">
                                            <div
                                                class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-4 text-white"
                                            >
                                                <div
                                                    class="flex items-center space-x-2 mb-3"
                                                >
                                                    <v-icon
                                                        name="fa-qrcode"
                                                        class="text-blue-400"
                                                        scale="0.9"
                                                    />
                                                    <span
                                                        class="text-sm font-semibold"
                                                        >QR Code</span
                                                    >
                                                </div>
                                                <div
                                                    class="bg-white p-3 rounded-lg mb-3"
                                                >
                                                    <canvas
                                                        ref="qrCodeCanvas"
                                                        class="w-full max-w-[140px] mx-auto"
                                                    ></canvas>
                                                </div>
                                                <p
                                                    class="text-xs text-slate-300 text-center"
                                                >
                                                    Scan to view details
                                                </p>
                                            </div>
                                            <button
                                                @click="downloadReportAsImage"
                                                class="download-btn w-full py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all hover:shadow-md hover:scale-[1.02] flex items-center justify-center space-x-2"
                                            >
                                                <v-icon
                                                    name="bi-download"
                                                    scale="0.9"
                                                />
                                                <span class="text-sm"
                                                    >Download</span
                                                >
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="border-t border-slate-200 px-6 py-3 bg-slate-50/80 backdrop-blur-sm"
                    >
                        <div class="flex justify-between items-center">
                            <button
                                v-if="reportDetails"
                                @click="resetForm"
                                class="reset-btn flex items-center space-x-2 px-4 py-2 border-2 border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition-all"
                            >
                                <v-icon name="bi-arrow-left" scale="0.8" />
                                <span class="text-sm">Search Again</span>
                            </button>
                            <button
                                v-else
                                @click="closeModal"
                                class="flex items-center space-x-2 px-4 py-2 border-2 border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition-all"
                            >
                                <v-icon name="io-close" scale="0.8" />
                                <span class="text-sm">Cancel</span>
                            </button>
                            <button
                                v-if="!reportDetails"
                                @click="trackReport"
                                :disabled="
                                    isLoading || !form.tracking_code.trim()
                                "
                                class="track-btn flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-slate-400 disabled:to-slate-500 text-white font-medium rounded-lg transition-all hover:shadow-md disabled:shadow-none disabled:cursor-not-allowed"
                            >
                                <v-icon name="fa-search" scale="0.8" />
                                <span class="text-sm">{{
                                    isLoading ? "Searching..." : "Track Report"
                                }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>

    <!-- QR Scanner Modal -->
    <Transition name="modal-backdrop">
        <div
            v-if="showQrScanner"
            class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        >
            <div
                class="relative w-full max-w-sm bg-white rounded-2xl overflow-hidden shadow-2xl"
            >
                <div
                    class="bg-gradient-to-r from-slate-800 to-slate-900 px-4 py-3"
                >
                    <div class="flex items-center justify-between">
                        <h3
                            class="text-base font-semibold text-white flex items-center space-x-2"
                        >
                            <v-icon
                                name="fa-camera"
                                class="text-blue-400"
                                scale="0.9"
                            />
                            <span>Scan QR Code</span>
                        </h3>
                        <button
                            @click="closeQrScanner"
                            class="text-white/80 hover:text-white p-1 rounded-lg transition-all"
                        >
                            <v-icon name="io-close" scale="1.1" />
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    <div
                        v-if="scannerError"
                        class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg"
                    >
                        <p
                            class="text-red-600 text-sm flex items-center space-x-2"
                        >
                            <v-icon
                                name="bi-exclamation-triangle"
                                class="text-red-500"
                                scale="0.8"
                            />
                            <span>{{ scannerError }}</span>
                        </p>
                    </div>

                    <div
                        class="relative bg-slate-900 rounded-xl overflow-hidden aspect-square"
                    >
                        <video
                            ref="videoRef"
                            autoplay
                            playsinline
                            muted
                            class="w-full h-full object-cover"
                        ></video>

                        <div
                            class="absolute inset-0 flex items-center justify-center"
                        >
                            <div
                                class="w-40 h-40 border-2 border-white/30 rounded-xl relative"
                            >
                                <div
                                    class="absolute -top-1 -left-1 w-6 h-6 border-l-3 border-t-3 border-blue-400 rounded-tl-xl"
                                ></div>
                                <div
                                    class="absolute -top-1 -right-1 w-6 h-6 border-r-3 border-t-3 border-blue-400 rounded-tr-xl"
                                ></div>
                                <div
                                    class="absolute -bottom-1 -left-1 w-6 h-6 border-l-3 border-b-3 border-blue-400 rounded-bl-xl"
                                ></div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-6 h-6 border-r-3 border-b-3 border-blue-400 rounded-br-xl"
                                ></div>
                                <div
                                    class="absolute inset-x-0 top-0 h-px bg-blue-400 animate-scan-line"
                                ></div>
                            </div>
                        </div>

                        <canvas ref="canvasRef" class="hidden"></canvas>
                    </div>

                    <p class="mt-3 text-xs text-slate-600 text-center">
                        Position QR code within the frame
                    </p>
                </div>
            </div>
        </div>
    </Transition>

    <!-- Media Modal -->
    <Transition name="modal-backdrop">
        <div
            v-if="mediaModal.show"
            class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
        >
            <div class="relative w-full max-w-4xl">
                <button
                    @click="closeMediaModal"
                    class="absolute -top-10 right-0 p-2 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all"
                >
                    <v-icon name="io-close" scale="1.2" />
                </button>

                <div class="bg-white rounded-xl overflow-hidden shadow-2xl">
                    <div
                        v-if="mediaModal.type === 'video'"
                        class="aspect-video w-full"
                    >
                        <video
                            controls
                            autoplay
                            class="w-full h-full rounded-xl"
                        >
                            <source
                                :src="mediaModal.src"
                                :type="getVideoMimeType(mediaModal.src)"
                            />
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <img
                        v-else
                        :src="mediaModal.src"
                        class="max-h-[80vh] w-full object-contain rounded-xl"
                    />
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Enhanced animations */
.modal-backdrop-enter-active,
.modal-backdrop-leave-active {
    transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.modal-backdrop-enter-from,
.modal-backdrop-leave-to {
    opacity: 0;
}

.modal-content-enter-active,
.modal-content-leave-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal-content-enter-from,
.modal-content-leave-to {
    opacity: 0;
    transform: translateY(-15px) scale(0.96);
}

.content-slide-enter-active,
.content-slide-leave-active {
    transition: all 0.35s ease-out;
}

.content-slide-enter-from,
.content-slide-leave-to {
    opacity: 0;
    transform: translateY(8px);
}

.loading-bounce-enter-active,
.loading-bounce-leave-active {
    transition: all 0.25s ease;
}

.loading-bounce-enter-from,
.loading-bounce-leave-to {
    opacity: 0;
    transform: scale(0.94);
}

.error-slide-enter-active,
.error-slide-leave-active {
    transition: all 0.25s ease;
}

.error-slide-enter-from,
.error-slide-leave-to {
    opacity: 0;
    transform: translateX(-8px);
}

/* Scan line animation */
@keyframes scan-line {
    0% {
        top: 0;
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        top: 100%;
        opacity: 0;
    }
}

.animate-scan-line {
    animation: scan-line 2s linear infinite;
}

/* Custom border widths for scanner corners */
.border-l-3 {
    border-left-width: 3px;
}

.border-t-3 {
    border-top-width: 3px;
}

.border-r-3 {
    border-right-width: 3px;
}

.border-b-3 {
    border-bottom-width: 3px;
}

/* Hover effects */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

.group:hover .group-hover\:bg-black\/20 {
    background-color: rgba(0, 0, 0, 0.2);
}

/* Compact scrollbar */
.max-h-\[calc\(95vh-140px\)\]::-webkit-scrollbar {
    width: 4px;
}

.max-h-\[calc\(95vh-140px\)\]::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-\[calc\(95vh-140px\)\]::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 6px;
}

.max-h-\[calc\(95vh-140px\)\]::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Button disabled state improvements */
button:disabled {
    cursor: not-allowed;
}

/* Form focus improvements */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
