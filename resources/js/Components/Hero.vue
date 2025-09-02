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

onMounted(() => {
    const tl = gsap.timeline();

    tl.fromTo(
        ".bg-gradient-to-br",
        { opacity: 0 },
        { opacity: 1, duration: 0.8, ease: "power2.inOut" }
    );
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
    tl.fromTo(
        ".main-content",
        { y: 30, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, ease: "power2.out" },
        "-=0.4"
    );
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
    tl.fromTo(
        ".subheadline",
        { y: 15, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" },
        "-=0.3"
    );
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
    tl.fromTo(
        ".stats-section",
        { y: 25, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" },
        "-=0.2"
    );
    tl.fromTo(
        ".stat-item",
        { y: 15, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.4, stagger: 0.1, ease: "power2.out" },
        "-=0.2"
    );
    tl.fromTo(
        ".track-button",
        { scale: 0, opacity: 0 },
        { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.7)" },
        "-=0.2"
    );

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
    <main id="home" class="relative w-full min-h-screen overflow-hidden">
        <Navigation />

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

        <div
            class="water-drop-top absolute top-1/4 left-1/4 w-40 h-40 md:w-64 md:h-64 lg:w-96 lg:h-96 rounded-full bg-blue-500 opacity-10 blur-3xl"
        ></div>
        <div
            class="water-drop-bottom absolute bottom-1/3 right-1/4 w-60 h-60 md:w-96 md:h-96 lg:w-128 lg:h-128 rounded-full bg-teal-500 opacity-10 blur-3xl"
        ></div>

        <div
            class="relative z-10 min-h-screen container mx-auto px-4 sm:px-6 flex flex-col justify-center pt-16 lg:pt-32"
        >
            <div
                class="main-content max-w-4xl mx-auto text-center px-2 flex-1 flex flex-col justify-center"
            >
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-[80px] font-[200] text-white leading-tight tracking-[-0.05em] mb-6 md:mb-8"
                >
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200 bg-[length:200%_100%] inline-block"
                        >Track,</span>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200 bg-[length:200%_100%] inline-block"
                        >Report,
                    </span>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-200 bg-[length:200%_100%] inline-block"
                        >Resolve</span
                    >
                </h1>

                <p
                    class="subheadline text-base sm:text-lg md:text-xl text-[#A2A8AF] max-w-[95%] sm:max-w-[600px] mx-auto leading-relaxed mb-8 md:mb-10 px-4"
                >
                    AquaTrack empowers you to monitor water usage, report issues
                    instantly, and track resolutions - all in one seamless
                    platform.
                </p>

                <div class="flex justify-center">
                    <Link
                        :href="route('reports.index')"
                        class="button-glow cta-button inline-flex items-center justify-center rounded-full px-6 py-3 sm:px-8 sm:py-4 gap-3 text-base sm:text-lg font-semibold text-white bg-gradient-to-r from-blue-400/60 to-cyan-400/60 shadow-lg shadow-blue-900/30 transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:from-blue-400/80 hover:to-cyan-400/80 hover:scale-105 hover:shadow-2xl hover:shadow-blue-900/50 mb-12 md:mb-16"
                    >
                        <v-icon
                            name="oi-report"
                            scale="1.2 sm:1.4"
                            class="text-white"
                        />
                        <span>Report Water Issue</span>
                    </Link>
                </div>
            </div>

            <div class="stats-section w-full px-2 pb-12 md:pb-14">
                <div
                    class="max-w-3xl bottom-10 mx-auto p-4 sm:p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10"
                >
                    <div class="grid grid-cols-3 gap-4 sm:gap-6 text-center">
                        <div class="stat-item p-3 sm:p-4">
                            <div
                                class="text-xl sm:text-2xl md:text-3xl font-semibold text-blue-300 mb-1"
                            >
                                1,200+
                            </div>
                            <div
                                class="text-sm sm:text-base text-blue-100 leading-tight"
                            >
                                Issues Resolved
                            </div>
                        </div>
                        <div
                            class="stat-item p-3 sm:p-4 border-x border-white/20"
                        >
                            <div
                                class="text-xl sm:text-2xl md:text-3xl font-semibold text-teal-300 mb-1"
                            >
                                342
                            </div>
                            <div
                                class="text-sm sm:text-base text-blue-100 leading-tight"
                            >
                                Customers
                            </div>
                        </div>
                        <div class="stat-item p-3 sm:p-4">
                            <div
                                class="text-xl sm:text-2xl md:text-3xl font-semibent text-cyan-300 mb-1"
                            >
                                98%
                            </div>
                            <div
                                class="text-sm sm:text-base text-blue-100 leading-tight"
                            >
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
            <button
                @click="handleTrackReport"
                class="flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-500 to-teal-500 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110"
            >
                <v-icon name="io-search" scale="1.3 sm:1.5" />
                <span class="sr-only">Track Report</span>
                <div
                    class="absolute -right-1 -top-1 w-5 h-5 sm:w-6 sm:h-6 bg-red-500 rounded-full border-2 border-white animate-pulse"
                ></div>
            </button>
        </div>

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

/* Mobile optimizations */
@media (max-width: 640px) {
    .water-drop-top {
        top: 10%;
        left: 10%;
        width: 16vw;
        height: 16vw;
        max-width: 5rem;
        max-height: 5rem;
    }
    .water-drop-bottom {
        bottom: 20%;
        right: 10%;
        width: 20vw;
        height: 20vw;
        max-width: 6rem;
        max-height: 6rem;
    }

    h1 {
        font-size: 2.5rem !important;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .subheadline {
        font-size: 1rem;
        line-height: 1.4;
        margin-bottom: 2rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .cta-button {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        margin-bottom: 2rem;
    }

    .stats-section {
        padding: 0 0.5rem 1.5rem;
    }

    .stats-section .grid {
        gap: 0.75rem;
    }

    .stat-item {
        padding: 0.75rem 0.5rem;
    }

    .stat-item div:first-child {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }

    .stat-item div:last-child {
        font-size: 0.875rem;
        line-height: 1.2;
    }

    .track-button button {
        width: 3.5rem;
        height: 3.5rem;
    }
}

/* Tablet optimizations */
@media (min-width: 641px) and (max-width: 1024px) {
    .water-drop-top {
        top: 15%;
        left: 15%;
        width: 12vw;
        height: 12vw;
        max-width: 8rem;
        max-height: 8rem;
    }
    .water-drop-bottom {
        bottom: 20%;
        right: 15%;
        width: 16vw;
        height: 16vw;
        max-width: 10rem;
        max-height: 10rem;
    }
}

/* Desktop optimizations */
@media (min-width: 1025px) {
    .water-drop-top {
        width: 24rem;
        height: 24rem;
    }
    .water-drop-bottom {
        width: 32rem;
        height: 32rem;
    }
}
</style>
