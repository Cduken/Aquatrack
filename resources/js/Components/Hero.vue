<script setup>
import { Link } from "@inertiajs/vue3";
import AddReportModal from "@/Components/Modals/AddReportModal.vue";
import { ref, onMounted } from "vue";
import ReportSuccessModal from "@/Components/Modals/ReportSuccessModal.vue";
import TrackReportModal from "@/Components/Modals/TrackReportModal.vue";
import Swal from "sweetalert2";
import Navigation from "./Header/Navigation.vue";
import gsap from "gsap";

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },

    canRegister: {
        type: Boolean,
        default: true,
    },
});

const showReportModal = ref(false);
const showSuccessModal = ref(false);
const showTrackModal = ref(false);
const trackingInfo = ref(null);

// GSAP animations on page load
onMounted(() => {
    const tl = gsap.timeline();

    // Animate background elements (faster)
    tl.fromTo(
        ".bg-gradient-to-br",
        { opacity: 0 },
        { opacity: 1, duration: 0.8, ease: "power2.inOut" }
    );

    // Animate floating water drops (faster)
    tl.fromTo(
        ".water-drop-top",
        { scale: 0, opacity: 0 },
        { scale: 1, opacity: 0.1, duration: 0.8, ease: "back.out(1.7)" },
        "-=0.6"
    );

    tl.fromTo(
        ".water-drop-bottom",
        { scale: 0, opacity: 0 },
        { scale: 1, opacity: 0.1, duration: 0.8, ease: "back.out(1.7)" },
        "-=0.6"
    );

    // Animate main content (faster)
    tl.fromTo(
        ".main-content",
        { y: 30, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, ease: "power2.out" },
        "-=0.4"
    );

    // Animate headline with gradient text reveal (faster)
    tl.fromTo(
        "h1 span",
        { backgroundPosition: "100% 50%" },
        {
            backgroundPosition: "0% 50%",
            duration: 1,
            stagger: 0.15,
            ease: "power2.inOut",
        },
        "-=0.3"
    );

    // Animate subheadline (faster)
    tl.fromTo(
        ".subheadline",
        { y: 15, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" },
        "-=0.3"
    );

    // Animate CTA buttons (faster)
    tl.fromTo(
        ".cta-button",
        { y: 20, opacity: 0 },
        {
            y: 0,
            opacity: 1,
            duration: 0.5,
            stagger: 0.1,
            ease: "back.out(1.7)",
        },
        "-=0.2"
    );

    // Animate stats section (faster)
    tl.fromTo(
        ".stats-section",
        { y: 25, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" },
        "-=0.2"
    );

    // Stagger animation for stats numbers (faster)
    tl.fromTo(
        ".stat-item",
        { y: 15, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.4, stagger: 0.1, ease: "power2.out" },
        "-=0.2"
    );

    // Animate floating track button (faster)
    tl.fromTo(
        ".track-button",
        { scale: 0, opacity: 0 },
        { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.7)" },
        "-=0.2"
    );

    // Add continuous subtle animations
    gsap.to(".water-drop-top", {
        y: 15,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
    });

    gsap.to(".water-drop-bottom", {
        y: -15,
        duration: 4,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
    });
});

const handleTrackReport = () => {
    showTrackModal.value = true;
};

const handleReportSubmitted = async (response) => {
    try {
        showReportModal.value = false;

        if (response.success) {
            trackingInfo.value = {
                code: response.trackingCode,
                date: response.dateSubmitted,
            };
            showSuccessModal.value = true;

            // Show SweetAlert notification
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Report submitted successfully!",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                background: "#4BB543",
                iconColor: "white",
                color: "white",
                timerProgressBar: true,
            });
        } else {
            throw new Error(response.message || "Failed to submit report");
        }
    } catch (error) {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: error.message,
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            background: "#FF3333",
            iconColor: "white",
            color: "white",
            timerProgressBar: true,
        });
    }
};
</script>

<template>
    <main class="relative w-full min-h-[85vh] overflow-hidden">
        <Navigation />

        <!-- Background elements - image with gradient overlay -->
        <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/images/AquatrackIMG.jpg')"
        ></div>
        <div
            class="absolute inset-0 bg-gradient-to-br from-[#062F64]/80 to-[#1E4272]/80"
        >
            <div
                class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 to-transparent"
            ></div>
        </div>

        <!-- Floating water drop decoration -->
        <div
            class="water-drop-top absolute top-1/4 left-1/4 w-40 h-40 md:w-64 md:h-64 rounded-full bg-blue-500 opacity-10 blur-3xl"
        ></div>
        <div
            class="water-drop-bottom absolute bottom-1/3 right-1/4 w-60 h-60 md:w-96 md:h-96 rounded-full bg-teal-500 opacity-10 blur-3xl"
        ></div>

        <!-- Main content -->
        <div
            class="relative z-10 container mx-auto px-4 sm:px-6 h-[95vh] lg:h-[85vh] flex flex-col justify-center md:justify-start md:pt-32"
        >
            <div class="main-content max-w-4xl mx-auto text-center px-2">
                <!-- Main headline -->
                <h1
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-md text-white leading-tight tracking-[-0.03em] mb-4 md:mb-6"
                >
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200 bg-[length:200%_100%] block sm:inline"
                        >Track</span
                    >,
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200 bg-[length:200%_100%] block sm:inline"
                        >Report</span
                    >,
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-200 bg-[length:200%_100%] block sm:inline"
                        >Resolve</span
                    >
                </h1>

                <!-- Subheadline -->
                <p
                    class="subheadline text-sm sm:text-md md:text-lg text-[#A2A8AF] max-w-[90%] sm:max-w-[550px] mx-auto leading-relaxed mb-6 md:mb-10"
                >
                    AquaTrack empowers you to monitor water usage, report issues
                    instantly, and track resolutions - all in one seamless
                    platform.
                </p>

                <!-- CTA Buttons -->

                <Link
                    :href="route('reports.index')"
                    class="button-glow cta-button inline-flex items-center rounded-full px-6 py-3 sm:px-8 sm:py-3 gap-2 text-sm sm:text-[16px] font-semibold text-white bg-gradient-to-r from-blue-400/60 to-cyan-400/60 shadow-lg shadow-blue-900/30 transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:from-blue-400/80 hover:to-cyan-400/80 hover:scale-105 hover:shadow-2xl hover:shadow-blue-900/50 hover:transition-duration-300"
                >
                    <v-icon
                        name="oi-report"
                        scale="1.1 sm:1.2"
                        class="text-white"
                    />
                    <span>Report Water Issue</span>
                </Link>
            </div>

            <!-- Stats section remains at the bottom -->
            <div
                class="stats-section lg:mb-10 md:mb-5 sm:mb-0 md:absolute lg:bottom-4 md:bottom-5 md:left-0 md:right-0 md:mt-0"
            >
                <div
                    class="max-w-4xl mx-auto p-3 lg:p-4 md:p-3 sm:p-2 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10"
                >
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 text-center">
                        <div class="stat-item p-2 sm:p-3">
                            <div
                                class="text-lg sm:text-xl md:text-2xl font-semibold text-blue-300"
                            >
                                1,200+
                            </div>
                            <div class="text-xs sm:text-sm text-blue-100">
                                Issues Resolved
                            </div>
                        </div>
                        <div
                            class="stat-item p-2 sm:p-3 border-x border-white/10"
                        >
                            <div
                                class="text-lg sm:text-xl md:text-2xl font-semibold text-teal-300"
                            >
                                342
                            </div>
                            <div class="text-xs sm:text-sm text-blue-100">
                                Customers
                            </div>
                        </div>
                        <div class="stat-item p-2 sm:p-3">
                            <div
                                class="text-lg sm:text-xl md:text-2xl font-semibold text-cyan-300"
                            >
                                98%
                            </div>
                            <div class="text-xs sm:text-sm text-blue-100">
                                Customer Satisfaction
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="track-button fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-20"
        >
            <div class="relative group">
                <button
                    @click="handleTrackReport"
                    class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-500 to-teal-500 text-white shadow-xl hover:shadow-2xl transition-all"
                >
                    <v-icon name="io-search" scale="1.1 sm:1.3" />
                    <span class="sr-only">Track Report</span>
                    <div
                        class="absolute -right-1 -top-1 w-4 h-4 sm:w-5 sm:h-5 bg-red-500 rounded-full border-2 border-white animate-pulse"
                    ></div>
                </button>

                <!-- Tooltip - hidden on mobile -->
                <div
                    class="hidden sm:block absolute right-14 bottom-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                >
                    <div
                        class="bg-gray-800 text-white text-sm px-3 py-2 rounded-lg whitespace-nowrap shadow-lg"
                    >
                        Track Report
                        <div
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1 w-2 h-2 bg-gray-800 rotate-45"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <AddReportModal
            :show="showReportModal"
            @close="showReportModal = false"
            @submitted="handleReportSubmitted"
        />
        <ReportSuccessModal
            v-if="trackingInfo"
            :show="showSuccessModal"
            :tracking-code="trackingInfo.code"
            :date-submitted="trackingInfo.date"
            @close="showSuccessModal = false"
        />
        <TrackReportModal
            :show="showTrackModal"
            @close="showTrackModal = false"
        />
    </main>
</template>

<style scoped>
@keyframes glow {
    0%,
    100% {
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.6);
    }
    50% {
        box-shadow: 0 0 25px rgba(56, 189, 248, 0.9);
    }
}
.button-glow {
    animation: glow 2s infinite;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .water-drop-top {
        top: 20%;
        left: 10%;
    }

    .water-drop-bottom {
        bottom: 25%;
        right: 10%;
    }
}
</style>
