<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true
    },
    report: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);
</script>

<template>
    <!-- Single transition wrapper for both overlay and panel -->
    <transition name="modal">
        <div v-if="show" class="fixed inset-0 z-40 overflow-hidden">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50 transition-opacity duration-300" @click="emit('close')"></div>

            <!-- Sliding panel container -->
            <div class="fixed inset-y-0 right-0 w-full max-w-md flex">
                <!-- Panel with transform class for animation -->
                <div class="relative w-full h-full transform transition-transform duration-300 ease-in-out">
                    <div class="h-full flex flex-col bg-white shadow-xl">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-6 bg-blue-600">
                            <div class="flex items-center space-x-2">
                                <v-icon name="bi-file-earmark-text" class="text-white" />
                                <span class="text-white font-medium">Report Details</span>
                            </div>
                            <button @click="emit('close')" class="text-white hover:text-gray-200">
                                <v-icon name="bi-x-lg" class="h-6 w-6" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto p-4">
                            <div v-if="report" class="space-y-4 text-sm">
                                <!-- Location Information -->
                                <div class="bg-gray-100 border border-gray-200 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                        <v-icon name="bi-geo-alt" class="mr-2 text-blue-600" />
                                        Location Information
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="flex items-start">
                                            <v-icon name="bi-building" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Municipality</p>
                                                <p class="font-medium">{{ report.municipality }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon name="bi-map" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Province</p>
                                                <p class="font-medium">{{ report.province }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon name="bi-geo" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Barangay</p>
                                                <p class="font-medium">{{ report.barangay }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon name="bi-signpost" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Purok/Street</p>
                                                <p class="font-medium">{{ report.purok }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reporter Information -->
                                <div class="bg-gray-100 border border-gray-200 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                        <v-icon name="bi-person" class="mr-2 text-blue-600" />
                                        Reporter Information
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="flex items-start">
                                            <v-icon name="bi-person-badge" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Reporter Name</p>
                                                <p class="font-medium">{{ report.reporter_name }}</p>
                                            </div>
                                        </div>
                                        <div v-if="report.reporter_phone" class="flex items-start">
                                            <v-icon name="bi-telephone" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Phone Number</p>
                                                <p class="font-medium">{{ report.reporter_phone }}</p>
                                            </div>
                                        </div>
                                        <div v-if="report.user" class="flex items-start">
                                            <v-icon name="bi-person-check" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Registered User</p>
                                                <p class="font-medium">{{ report.user.name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Report Metadata -->
                                <div class="bg-gray-100 border border-gray-200 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                        <v-icon name="bi-info-circle" class="mr-2 text-blue-600" />
                                        Report Information
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="flex items-start">
                                            <v-icon name="bi-tag" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Report ID</p>
                                                <p class="font-medium">#{{ report.id }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon name="bi-calendar" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Submitted</p>
                                                <p class="font-medium">{{ new Date(report.created_at).toLocaleString()
                                                    }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon name="bi-check-circle" class="mr-2 mt-0.5 text-gray-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Status</p>
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Verified
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="bg-gray-100 border border-gray-200 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                        <v-icon name="bi-card-text" class="mr-2 text-blue-600" />
                                        Description
                                    </h3>
                                    <p class="text-gray-700 whitespace-pre-line text-sm">{{ report.description }}</p>
                                </div>

                                <!-- Photos Section -->
                                <div v-if="report.photos && report.photos.length"
                                    class="bg-gray-100 border border-gray-200 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                        <v-icon name="bi-images" class="mr-2 text-blue-600" />
                                        Photos
                                    </h3>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        <div v-for="(photo, index) in report.photos" :key="index"
                                            class="relative group overflow-hidden rounded-lg border border-gray-200">
                                            <img :src="'/storage/' + photo.path" :alt="`Report photo ${index + 1}`"
                                                class="w-full h-32 object-cover hover:scale-105 transition-transform duration-300">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                <a :href="'/storage/' + photo.path" target="_blank"
                                                    class="text-white bg-blue-600 bg-opacity-80 p-1.5 rounded-full hover:bg-opacity-100">
                                                    <v-icon name="bi-zoom-in" class="h-4 w-4" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
                            <button @click="emit('close')" type="button"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <v-icon name="bi-x-lg" class="mr-2" />
                                Close
                            </button>
                        </div>
                    </div>
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
</style>
