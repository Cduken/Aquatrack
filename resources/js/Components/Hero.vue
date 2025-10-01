// Hero.vue (updated modal section)
<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import ReportForm from "@/Components/Reports/ReportForm.vue";
import GlobalReportSuccessModal from "@/Components/Modals/GlobalReportSuccessModal.vue";
import TrackReportModal from "@/Components/Modals/TrackReportModal.vue";
import Navigation from "./Header/Navigation.vue";
import gsap from "gsap";

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
    trackingCode: {
        type: [String, null],
        default: null,
    },
    dateSubmitted: {
        type: [String, null],
        default: null,
    },
});

const showSuccessModal = ref(false);
const showTrackModal = ref(false);
const showReportModal = ref(false);
const trackingInfo = ref(null);

const zones = {
    "Zone 1": ["Poblacion Sur"],
    "Zone 2": ["Poblacion Centro"],
    "Zone 3": ["Poblacion Centro"],
    "Zone 4": ["Poblacion Norte"],
    "Zone 5": ["Candajec", "Buangan"],
    "Zone 6": ["Bonbon"],
    "Zone 7": ["Bonbon"],
    "Zone 8": ["Nahawan"],
    "Zone 9": ["Caboy", "Villaflor", "Cantuyoc"],
    "Zone 10": ["Bacani", "Mataub", "Comaang", "Tangaran"],
    "Zone 11": ["Cantuyoc", "Nahawan"],
    "Zone 12": ["Lajog", "Buacao"],
};

onMounted(() => {
    console.log("Received props on mount:", {
        trackingCode: props.trackingCode,
        dateSubmitted: props.dateSubmitted,
    });

    if (props.trackingCode && props.dateSubmitted) {
        trackingInfo.value = {
            code: props.trackingCode,
            date: props.dateSubmitted,
        };
        showSuccessModal.value = true;
    } else {
        console.log("No trackingCode or dateSubmitted, modal will not show.");
    }
    console.log("Modal state:", {
        showSuccessModal: showSuccessModal.value,
        trackingInfo: trackingInfo.value,
    });

    const tl = gsap.timeline();



    tl.fromTo(
        ".hero-content",
        { x: -50, opacity: 0 },
        { x: 0, opacity: 1, duration: 0.8, ease: "power2.out" }
    );
    tl.fromTo(
        "h1 span",
        { x: -30, opacity: 0 },
        {
            x: 0,
            opacity: 1,
            duration: 0.6,
            stagger: 0.15,
            ease: "back.out(1.7)",
        },
        "-=0.3"
    );
    tl.fromTo(
        ".subheadline",
        { x: -20, opacity: 0 },
        { x: 0, opacity: 1, duration: 0.5, ease: "power2.out" },
        "-=0.2"
    );
    tl.fromTo(
        ".cta-button",
        { y: 20, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, ease: "back.out(1.7)" },
        "-=0.2"
    );
    tl.fromTo(
        ".hero-image",
        { x: 50, opacity: 0 },
        { x: 0, opacity: 1, duration: 0.8, ease: "power2.out" },
        "-=0.6"
    );
});

const handleTrackReport = () => {
    const trackingCode = trackingInfo.value?.code || props.trackingCode || "";
    if (trackingCode) {
        trackingInfo.value = { code: trackingCode, date: props.dateSubmitted };
    }
    showSuccessModal.value = false;
    showTrackModal.value = true;
};

const openReportModal = () => {
    showReportModal.value = true;
    // Prevent body scroll when modal is open
    document.body.style.overflow = "hidden";
};

const closeReportModal = () => {
    showReportModal.value = false;
    // Restore body scroll when modal is closed
    document.body.style.overflow = "auto";
};

const handleReportSuccess = (data) => {
    console.log("Handling report success:", data);
    showReportModal.value = false;
    document.body.style.overflow = "auto";
    trackingInfo.value = {
        code: data.trackingCode,
        date: data.dateSubmitted,
    };
    showSuccessModal.value = true;
    console.log("Modal state after handleReportSuccess:", {
        showSuccessModal: showSuccessModal.value,
        trackingInfo: trackingInfo.value,
    });
};
</script>

<template>
    <main id="home" class="relative w-full min-h-screen overflow-hidden">
        <Navigation />

        <div
            class="relative px-4 sm:px-8 lg:px-[120px] flex flex-col lg:flex-row gap-8 lg:gap-16 items-center justify-center min-h-[80vh] py-12 lg:py-0"
        >
            <div
                class="hero-content flex flex-col max-w-[800px] order-2 lg:order-1 text-center lg:text-left lg:w-3/5"
            >
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl mb-4 leading-snug font-light"
                >
                    <span
                        class="block text-transparent bg-clip-text bg-gradient-to-r from-[#062F64] to-[#1E4272] floating-text"
                    >
                        Clarin Water
                    </span>
                    <span
                        class="block text-transparent leading-snug bg-clip-text bg-gradient-to-r from-[#090979] to-[#00D4FF] floating-text"
                        style="animation-delay: 0.2s"
                    >
                        Management System
                    </span>
                </h1>

                <p
                    class="subheadline text-gray-600 max-w-[600px] mb-6 text-lg leading-relaxed floating-text mx-auto lg:mx-0"
                    style="animation-delay: 0.6s"
                >
                    Manage your water services with ease. Report issues
                    instantly, track monthly readings, view bills, and access
                    analytics—all in one place designed for the people of
                    Clarin, Bohol.
                </p>

                <div
                    class="cta-button floating-text"
                    style="animation-delay: 0.8s"
                >
                    <button
                        @click="openReportModal"
                        class="inline-flex items-center gap-2 border p-3 px-6 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 bg-white"
                    >
                        <v-icon name="bi-droplet" scale="1" />
                        Report Issues
                    </button>
                </div>
            </div>

            <div
                class="hero-image w-full lg:w-2/5 flex items-center justify-center order-1 lg:order-2"
            >
                <div
                    class="max-w-[500px] max-h-[500px] w-full bg-gray-200 rounded-2xl shadow-2xl"
                >
                    <div class="p-4 sm:p-6">
                        <img
                            class="rounded-2xl w-full h-auto"
                            src="/images/AquatrackIMG.jpg"
                            alt="Water District Office"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Track Report Floating Button -->
        <div class="fixed bottom-8 right-8 z-20 group">
            <button
                @click="handleTrackReport"
                class="relative flex items-center justify-center w-16 h-16 rounded-full bg-[#0D3468] text-white shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 hover:scale-110"
                title="Track Report"
            >
                <v-icon name="io-search" scale="1.5" />
                <div
                    class="absolute -right-1 -top-1 w-5 h-5 bg-red-500 rounded-full border-2 border-white animate-pulse"
                ></div>

                <div
                    class="absolute right-full mr-4 top-1/2 -translate-y-1/2 whitespace-nowrap px-3 py-2 rounded-xl text-sm bg-gray-900/90 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-sm border border-white/10"
                >
                    Track Report
                    <div
                        class="absolute left-full top-1/2 -translate-y-1/2 border-4 border-transparent border-l-gray-900/90"
                    ></div>
                </div>
            </button>
        </div>

        <!-- Clean Report Modal -->
        <div v-if="showReportModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"
                @click="closeReportModal"
            ></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <!-- Modal Panel -->
                <div
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[1000px] max-h-[100vh] overflow-hidden transform transition-all duration-300"
                >
                    <!-- Modal Header -->
                    <div
                        class="bg-gradient-to-r from-[#062F64] to-[#1E4272] px-6 py-4 sticky top-0 z-10"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-md text-white">
                                    Report Water Issue
                                </h2>
                                <p class="text-blue-100 text-sm mt-1">
                                    Report water emergencies, leaks, or other
                                    issues in your area
                                </p>
                            </div>
                            <button
                                @click="closeReportModal"
                                class="p-2 hover:bg-white/10 rounded-full transition-colors duration-200 group"
                            >
                                <v-icon
                                    name="hi-x"
                                    class="h-6 w-6 text-white group-hover:text-gray-200 transition-colors"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="overflow-y-auto max-h-[calc(85vh-80px)]">
                        <ReportForm
                            :zones="zones"
                            @submitted="handleReportSuccess"
                            @cancel="closeReportModal"
                        />
                    </div>
                </div>
            </div>
        </div>

        <GlobalReportSuccessModal
            v-if="showSuccessModal && trackingInfo"
            :show="showSuccessModal"
            :tracking-code="trackingInfo.code"
            :date-submitted="trackingInfo.date"
            @close="showSuccessModal = false"
            @track-report="handleTrackReport"
        />

        <TrackReportModal
            :show="showTrackModal"
            :initial-tracking-code="trackingInfo?.code || ''"
            @close="showTrackModal = false"
        />
    </main>
</template>

<style scoped>
.floating-text {
    animation: floatUp 1s ease-out forwards;
    opacity: 0;
}

@keyframes floatUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hide scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Hide scrollbar for the report form modal */
.overflow-y-auto {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}
.overflow-y-auto::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

/* Responsive modal: full width/height on small screens */
@media (max-width: 640px) {
    .modal-panel {
        border-radius: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        margin: 0 !important;
    }
    .modal-panel > div {
        border-radius: 0 !important;
    }
}

@media (max-width: 1024px) {
    .hero-content {
        text-align: center;
        max-width: 100%;
    }

    .hero-image {
        width: 100%;
    }

    h1 {
        font-size: 3rem !important;
    }
}

@media (max-width: 640px) {
    h1 {
        font-size: 2.5rem !important;
        line-height: 1.1;
    }

    .subheadline {
        font-size: 1rem !important;
        margin-bottom: 1.5rem;
        max-width: 100%;
    }

    .cta-button a {
        width: auto;
        min-width: 200px;
    }

    .hero-image {
        max-width: 350px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 2rem !important;
    }

    .subheadline {
        font-size: 0.9rem !important;
    }

    .hero-image {
        max-width: 300px;
    }
}

html {
    scroll-behavior: smooth;
}
</style>
