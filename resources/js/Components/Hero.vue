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
const trackingInfo = ref(null);
const showReportSlider = ref(false);

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

    gsap.to(".purple-float", {
        y: -20,
        x: 10,
        duration: 5,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
    });

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

    // gsap.to(".hero-image", {
    //     y: -10,
    //     duration: 3,
    //     repeat: -1,
    //     yoyo: true,
    //     ease: "sine.inOut",
    // });
});

const handleTrackReport = () => {
    // Use the trackingCode from props or default to an empty string
    const trackingCode = trackingInfo.value?.code || props.trackingCode || "";
    if (trackingCode) {
        trackingInfo.value = { code: trackingCode, date: props.dateSubmitted };
    }
    showSuccessModal.value = false;
    showTrackModal.value = true;
};

const openReportSlider = () => {
    showReportSlider.value = true;
};

const handleReportSuccess = (data) => {
    console.log("Handling report success:", data);
    showReportSlider.value = false;
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
        <!-- <div
            class="purple-float absolute left-[300px] top-0 w-72 h-72 rounded-full bg-[#00D4FF]/10 blur-3xl"
        ></div>
        <div
            class="purple-float absolute left-40 bottom-40 w-48 h-48 rounded-full bg-purple-400/20 blur-3xl"
            style="animation-delay: 1s"
        ></div> -->

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
                        class="block text-transparent leading-snug bg-clip-text bg-gradient-to-r from-[#090979] to-[#00D4FF]    floating-text"
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
                        @click="openReportSlider"
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

        <div class="fixed bottom-8 right-8 z-20 group">
            <button
                @click="handleTrackReport"
                class="relative flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-white shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 hover:scale-110"
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

        <div v-if="showReportSlider" class="fixed inset-0 z-50 overflow-hidden">
            <div
                class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showReportSlider = false"
            ></div>

            <div
                class="fixed inset-y-0 right-0 max-w-full flex"
                :class="{ 'sm:pl-10': showReportSlider }"
            >
                <div
                    class="relative w-screen max-w-3xl md:max-w-4xl transform transition-transform duration-500 ease-in-out"
                    :class="{
                        'translate-x-0': showReportSlider,
                        'translate-x-full': !showReportSlider,
                    }"
                >
                    <div
                        class="h-full flex flex-col bg-white shadow-xl overflow-y-auto"
                    >
                        <div class="bg-[#062F64] px-4 py-5 sm:px-6 sticky top-0 z-50">
                            <div class="flex items-center justify-between">
                                <h2
                                    class="text-xl font-semibold text-white leading-7"
                                >
                                    Report Water Issue
                                </h2>
                                <button
                                    type="button"
                                    class="rounded-md bg-[#062F64] text-gray-300 hover:text-white focus:outline-none"
                                    @click="showReportSlider = false"
                                >
                                    <span class="sr-only">Close</span>
                                    <v-icon
                                        name="hi-x"
                                        class="h-6 w-6 text-white"
                                    />
                                </button>
                            </div>
                            <div class="mt-1">
                                <p class="text-sm text-gray-300">
                                    Report water emergencies, leaks, or other
                                    issues in your area
                                </p>
                            </div>
                        </div>

                        <div class="relative flex-1">
                            <ReportForm
                                :zones="zones"
                                @submitted="handleReportSuccess"
                                @cancel="showReportSlider = false"
                            />
                        </div>
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
/* @keyframes floatUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
} */

/* @keyframes imageFloat {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
} */

.floating-text {
    animation: floatUp 1s ease-out forwards;
    opacity: 0;
}
/*
.floating-image {
    animation: imageFloat 6s ease-in-out infinite;
} */

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.5s ease-in-out;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
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

@supports (backdrop-filter: blur(10px)) {
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
}

@supports not (backdrop-filter: blur(10px)) {
    .backdrop-blur-sm {
        background-color: rgba(15, 23, 42, 0.8);
    }
}
</style>
