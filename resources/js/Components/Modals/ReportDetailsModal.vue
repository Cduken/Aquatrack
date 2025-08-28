<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    report: {
        type: Object,
        default: null,
    },
});

const report = computed(() => props.report);

const emit = defineEmits(["close"]);
const showVideoModal = ref(false);
const currentVideo = ref(null);
const map = ref(null);
const mapContainer = ref(null);
const heading = ref(0); // Default heading (north)
const mapLoaded = ref(false);

// Initialize Leaflet map
const initializeMap = () => {
    if (
        report.value &&
        report.value.latitude !== undefined &&
        report.value.latitude !== null &&
        report.value.longitude !== undefined &&
        report.value.longitude !== null &&
        mapContainer.value
    ) {
        // Convert to numbers if they are strings
        const lat = Number(report.value.latitude);
        const lon = Number(report.value.longitude);

        // Get heading if available (for arrow direction)
        if (
            report.value.heading !== undefined &&
            report.value.heading !== null
        ) {
            heading.value = Number(report.value.heading);
        }

        // Validate coordinates
        if (
            !isNaN(lat) &&
            !isNaN(lon) &&
            Math.abs(lat) <= 90 &&
            Math.abs(lon) <= 180
        ) {
            const L = window.L;
            if (L && !map.value) {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    if (mapContainer.value) {
                        map.value = L.map(mapContainer.value).setView(
                            [lat, lon],
                            15
                        );

                        L.tileLayer(
                            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                            {
                                maxZoom: 19,
                                attribution:
                                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            }
                        ).addTo(map.value);

                        // Create a simple marker with a standard icon
                        const markerIcon = L.icon({
                            iconUrl:
                                "data:image/svg+xml;base64," +
                                btoa(`
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" viewBox="0 0 30 40">
                                    <g transform="rotate(${heading.value} 15 15)">
                                        <path d="M15,1.5 C8.1,1.5 2.5,7.1 2.5,14 C2.5,23.8 15,38.5 15,38.5 C15,38.5 27.5,23.8 27.5,14 C27.5,7.1 21.9,1.5 15,1.5 Z" fill="#dc2626" stroke="#ffffff" stroke-width="2"/>
                                        <circle cx="15" cy="13" r="5" fill="#ffffff"/>
                                        <polygon points="15,5 12,15 15,12 18,15" fill="#ffffff"/>
                                    </g>
                                </svg>
                            `),
                            iconSize: [30, 40],
                            iconAnchor: [15, 40],
                            popupAnchor: [0, -40],
                        });

                        // Add marker
                        L.marker([lat, lon], { icon: markerIcon }).addTo(
                            map.value
                        );

                        // Force map to resize after a short delay
                        setTimeout(() => {
                            if (map.value) {
                                map.value.invalidateSize();
                                mapLoaded.value = true;
                            }
                        }, 100);
                    }
                }, 100);
            }
        }
    }
};

// Watch for changes in show prop to initialize map
watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            // Small delay to ensure the modal is fully rendered
            setTimeout(() => {
                initializeMap();
            }, 200);
        } else {
            mapLoaded.value = false;
        }
    }
);

onMounted(() => {
    if (props.show) {
        initializeMap();
    }
});

onUnmounted(() => {
    if (map.value) {
        map.value.remove();
        map.value = null;
    }
});

// Clean up map when modal closes
watch(
    () => props.show,
    (newVal) => {
        if (!newVal && map.value) {
            map.value.remove();
            map.value = null;
            mapLoaded.value = false;
        }
    }
);

const openVideoModal = (videoPath) => {
    currentVideo.value = "/storage/" + videoPath;
    showVideoModal.value = true;
};

const closeVideoModal = () => {
    showVideoModal.value = false;
    currentVideo.value = null;
};

const isVideoFile = (file) => {
    return (
        file.type === "video" ||
        (file.mime_type && file.mime_type.includes("video"))
    );
};

const statusClass = computed(() => {
    if (!report.value || !report.value.status) return "";
    switch (report.value.status.toLowerCase()) {
        case "in progress":
            return "bg-blue-100 text-blue-800";
        case "resolved":
            return "bg-green-100 text-green-800";
        case "pending":
            return "bg-yellow-100 text-yellow-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
});

const statusLabel = computed(() => {
    if (!report.value || !report.value.status) return "";
    return (
        report.value.status.charAt(0).toUpperCase() +
        report.value.status.slice(1)
    );
});

// Safely get coordinates with fallback
const getLatitude = computed(() => {
    return report.value &&
        report.value.latitude !== undefined &&
        report.value.latitude !== null
        ? Number(report.value.latitude).toFixed(6)
        : "N/A";
});

const getLongitude = computed(() => {
    return report.value &&
        report.value.longitude !== undefined &&
        report.value.longitude !== null
        ? Number(report.value.longitude).toFixed(6)
        : "N/A";
});

// Format the heading for display
const getHeading = computed(() => {
    if (!report.value || !report.value.heading) return "N/A";

    const directions = ["N", "NE", "E", "SE", "S", "SW", "W", "NW"];
    const index = Math.round((Number(report.value.heading) % 360) / 45) % 8;
    return `${directions[index]} (${Math.round(report.value.heading)}°)`;
});
</script>

<template>
    <!-- Single transition wrapper for both overlay and panel -->
    <transition name="modal">
        <div v-if="show" class="fixed inset-0 z-[1000] overflow-hidden">
            <!-- Overlay -->
            <div
                class="absolute inset-0 bg-black/50 transition-opacity duration-300"
                @click="emit('close')"
            ></div>

            <!-- Sliding panel container -->
            <div class="fixed inset-y-0 right-0 w-full max-w-2xl flex">
                <!-- Panel with transform class for animation -->
                <div
                    class="relative w-full h-full transform transition-transform duration-300 ease-in-out"
                >
                    <div class="h-full flex flex-col bg-white shadow-xl">
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between px-4 py-6 bg-gradient-to-r from-[#062F64] to-[#1E4272]"
                        >
                            <div class="flex items-center space-x-2">
                                <v-icon
                                    name="bi-file-earmark-text"
                                    class="text-white"
                                />
                                <span class="text-white font-medium"
                                    >Report Details</span
                                >
                            </div>
                            <button
                                @click="emit('close')"
                                class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-white/10"
                            >
                                <v-icon name="bi-x-lg" class="h-6 w-6" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto p-4">
                            <div v-if="report" class="space-y-4 text-sm">
                                <!-- Location Information -->
                                <div
                                    class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-geo-alt"
                                            class="mr-2 text-blue-600"
                                        />
                                        Location Information
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    >
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-building"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Municipality
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.municipality }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-map"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Province
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.province }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-geo"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Barangay
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.barangay }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-signpost"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Purok/Street
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.purok }}
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-start col-span-2 bg-blue-50 p-3 rounded-lg border border-blue-100"
                                        >
                                            <div class="flex items-center mr-3">
                                                <v-icon
                                                    name="bi-geo-alt-fill"
                                                    class="text-red-500 text-lg"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p
                                                    class="text-xs text-gray-500 mb-1"
                                                >
                                                    GPS Coordinates
                                                </p>
                                                <div
                                                    class="flex items-center gap-2 flex-wrap"
                                                >
                                                    <span
                                                        class="font-medium bg-white py-1 px-2 rounded border"
                                                    >
                                                        Lat: {{ getLatitude }}
                                                    </span>
                                                    <span
                                                        class="font-medium bg-white py-1 px-2 rounded border"
                                                    >
                                                        Lon: {{ getLongitude }}
                                                    </span>
                                                    <span
                                                        v-if="report.heading"
                                                        class="font-medium bg-white py-1 px-2 rounded border flex items-center"
                                                    >
                                                        <v-icon
                                                            name="bi-compass"
                                                            class="mr-1 text-blue-500"
                                                        />
                                                        {{ getHeading }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                class="ml-2 flex items-center justify-center bg-red-100 p-2 rounded-full"
                                            >
                                                <v-icon
                                                    name="bi-signpost-split"
                                                    class="text-red-600 text-lg"
                                                    animation="spin"
                                                    speed="slow"
                                                    hover
                                                />
                                            </div>
                                        </div>
                                        <!-- Map Section -->
                                        <div class="col-span-2 mt-4">
                                            <div
                                                v-if="
                                                    report.latitude &&
                                                    report.longitude
                                                "
                                                ref="mapContainer"
                                                class="w-full h-48 rounded-lg border border-gray-300 shadow-sm relative"
                                                style="min-height: 200px"
                                            >
                                                <!-- Loading indicator -->
                                                <div
                                                    v-if="!mapLoaded"
                                                    class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-80 z-10"
                                                >
                                                    <div class="text-center">
                                                        <v-icon
                                                            name="bi-compass"
                                                            class="text-blue-500 text-2xl mb-2 animate-spin"
                                                        />
                                                        <p
                                                            class="text-sm text-gray-600"
                                                        >
                                                            Loading map...
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- Arrow indicator explanation -->
                                                <div
                                                    v-if="mapLoaded"
                                                    class="absolute bottom-2 left-2 bg-white bg-opacity-80 px-2 py-1 rounded text-xs flex items-center"
                                                >
                                                    <div
                                                        class="w-4 h-4 mr-1 relative"
                                                    >
                                                        <div
                                                            class="w-4 h-4 bg-red-600 rounded-full"
                                                        ></div>
                                                        <div
                                                            class="absolute top-0 left-1.5 w-1 h-2 bg-white"
                                                        ></div>
                                                    </div>
                                                    <span
                                                        >Device location with
                                                        direction
                                                        indicator</span
                                                    >
                                                </div>
                                            </div>
                                            <div
                                                v-else
                                                class="w-full h-48 rounded-lg border border-gray-300 bg-gray-50 flex items-center justify-center"
                                            >
                                                <div
                                                    class="text-center text-gray-500"
                                                >
                                                    <v-icon
                                                        name="bi-geo-alt"
                                                        class="text-3xl mb-2"
                                                    />
                                                    <p class="text-sm">
                                                        No GPS coordinates
                                                        available
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reporter Information -->
                                <div
                                    class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-person"
                                            class="mr-2 text-blue-600"
                                        />
                                        Reporter Information
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    >
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-person-badge"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Reporter Name
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.reporter_name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            v-if="report.reporter_phone"
                                            class="flex items-start"
                                        >
                                            <v-icon
                                                name="bi-telephone"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Phone Number
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.reporter_phone }}
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            v-if="report.user"
                                            class="flex items-start"
                                        >
                                            <v-icon
                                                name="bi-person-check"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Registered User
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.user.name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Report Metadata -->
                                <div
                                    class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-info-circle"
                                            class="mr-2 text-blue-600"
                                        />
                                        Report Information
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    >
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-tag"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Report ID
                                                </p>
                                                <p class="font-medium">
                                                    #{{ report.id }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-calendar"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Submitted
                                                </p>
                                                <p class="font-medium">
                                                    {{
                                                        new Date(
                                                            report.created_at
                                                        ).toLocaleString()
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-check-circle"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Status
                                                </p>
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                                    :class="statusClass"
                                                >
                                                    {{ statusLabel }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-upc-scan"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Tracking Code
                                                </p>
                                                <p class="font-medium">
                                                    {{ report.tracking_code }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div
                                    class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-card-text"
                                            class="mr-2 text-blue-600"
                                        />
                                        Description
                                    </h3>
                                    <div
                                        class="bg-white p-3 rounded border border-gray-200"
                                    >
                                        <p
                                            class="text-gray-700 whitespace-pre-line text-sm"
                                        >
                                            {{ report.description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Media Section -->
                                <div
                                    v-if="report.photos && report.photos.length"
                                    class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-images"
                                            class="mr-2 text-blue-600"
                                        />
                                        Media
                                    </h3>
                                    <div
                                        class="grid grid-cols-2 sm:grid-cols-3 gap-3"
                                    >
                                        <div
                                            v-for="(
                                                media, index
                                            ) in report.photos"
                                            :key="index"
                                            class="relative group overflow-hidden rounded-lg border border-gray-200 h-32 shadow-sm transition-all duration-300 hover:shadow-md hover:border-blue-300"
                                        >
                                            <!-- Video Thumbnail -->
                                            <template v-if="isVideoFile(media)">
                                                <div
                                                    class="w-full h-full bg-gray-800 flex items-center justify-center relative"
                                                >
                                                    <!-- Video element that will play on hover -->
                                                    <video
                                                        muted
                                                        loop
                                                        class="absolute inset-0 w-full h-full object-cover group-hover:opacity-100 transition-opacity duration-300"
                                                    >
                                                        <source
                                                            :src="
                                                                '/storage/' +
                                                                media.path
                                                            "
                                                            type="video/mp4"
                                                        />
                                                    </video>

                                                    <!-- Play button overlay -->
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-all z-10"
                                                    >
                                                        <v-icon
                                                            name="bi-play-circle-fill"
                                                            class="text-white text-4xl"
                                                        />
                                                    </div>
                                                </div>
                                                <button
                                                    @click="
                                                        openVideoModal(
                                                            media.path
                                                        )
                                                    "
                                                    class="absolute inset-0 w-full h-full flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all z-20"
                                                >
                                                    <span class="sr-only"
                                                        >Play video</span
                                                    >
                                                </button>
                                            </template>

                                            <!-- Image Thumbnail -->
                                            <template v-else>
                                                <img
                                                    :src="
                                                        '/storage/' + media.path
                                                    "
                                                    :alt="`Report photo ${
                                                        index + 1
                                                    }`"
                                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                />
                                                <a
                                                    :href="
                                                        '/storage/' + media.path
                                                    "
                                                    target="_blank"
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100"
                                                >
                                                    <v-icon
                                                        name="bi-zoom-in"
                                                        class="text-white bg-black/50 p-1.5 rounded-full"
                                                    />
                                                </a>
                                            </template>

                                            <div
                                                class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded"
                                            >
                                                {{
                                                    (
                                                        media.size /
                                                        1024 /
                                                        1024
                                                    ).toFixed(2)
                                                }}
                                                MB
                                                <span v-if="isVideoFile(media)"
                                                    >(Video)</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="border-t border-gray-200 px-4 py-3 bg-gray-50"
                        >
                            <button
                                @click="emit('close')"
                                type="button"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                <v-icon name="bi-x-lg" class="mr-2" />
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>

    <!-- Video Modal -->
    <transition name="modal">
        <div
            v-if="showVideoModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
        >
            <div class="relative w-full max-w-4xl mx-4">
                <button
                    @click="closeVideoModal"
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors duration-200 p-1 rounded-full hover:bg-white/10"
                >
                    <v-icon name="bi-x-lg" scale="1.5" />
                </button>

                <div
                    class="aspect-video w-full bg-black rounded-lg overflow-hidden"
                >
                    <video
                        v-if="currentVideo"
                        controls
                        autoplay
                        class="w-full h-full"
                    >
                        <source :src="currentVideo" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Custom scrollbar for the content */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #e2e8f0 #f8fafc;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f8fafc;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #e2e8f0;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: #cbd5e1;
}

/* Modal transition styles */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .transform,
.modal-leave-active .transform {
    transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}

.modal-enter-from .transform {
    transform: translateX(100%);
}

.modal-leave-to .transform {
    transform: translateX(100%);
}

/* Custom arrow marker styles */
.custom-arrow-container {
    background: transparent !important;
    border: none !important;
}
</style>
