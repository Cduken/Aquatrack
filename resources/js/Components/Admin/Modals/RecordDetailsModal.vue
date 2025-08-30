<!-- RecordDetailsModal.vue -->
<script setup>
import { ref, computed, onMounted } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    record: {
        type: Object,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);

const statusClass = computed(() => {
    if (!props.record || !props.record.status) return "";
    switch (props.record.status.toLowerCase()) {
        case "paid":
            return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
        case "pending":
            return "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200";
        case "overdue":
            return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
        default:
            return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200";
    }
});

const statusLabel = computed(() => {
    if (!props.record || !props.record.status) return "";
    return (
        props.record.status.charAt(0).toUpperCase() +
        props.record.status.slice(1)
    );
});

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// Calculate due date (assuming 15 days from reading date)
const dueDate = computed(() => {
    if (!props.record || !props.record.reading_date) return "N/A";
    const readingDate = new Date(props.record.reading_date);
    readingDate.setDate(readingDate.getDate() + 15);
    return formatDate(readingDate.toISOString().split("T")[0]);
});

// Check if record is overdue
const isOverdue = computed(() => {
    if (!props.record || props.record.status !== "Pending") return false;
    const due = new Date(props.record.reading_date);
    due.setDate(due.getDate() + 15);
    return new Date() > due;
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
                    <div
                        class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl"
                    >
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between px-4 py-6 bg-gradient-to-r from-[#062F64] to-[#1E4272]"
                        >
                            <div class="flex items-center space-x-2">
                                <v-icon
                                    name="bi-clipboard-data"
                                    class="text-amber-300"
                                    scale="1.5"
                                />
                                <span class="text-white font-[200] text-xl"
                                    >Concessioner's Record Details</span
                                >
                            </div>
                            <button
                                @click="emit('close')"
                                class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-white/10"
                            >
                                <v-icon name="bi-x-lg" class="h-6 w-6" />
                            </button>
                        </div>

                        <!-- Loading state -->
                        <div
                            v-if="loading"
                            class="flex-1 flex items-center justify-center"
                        >
                            <div class="text-center">
                                <div
                                    class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"
                                ></div>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Loading record details...
                                </p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div
                            v-else-if="record"
                            class="flex-1 overflow-y-auto p-4"
                        >
                            <div class="space-y-4 text-sm">
                                <!-- Customer Information -->
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-person"
                                            class="mr-2 text-blue-600"
                                        />
                                        Customer Information
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
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Customer Name
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ record.user.name }}
                                                    {{ record.user.lastname }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-hash"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Account Number
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    #{{
                                                        record.user
                                                            .account_number
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-upc-scan"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Serial Number
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        record.user
                                                            .serial_number
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-geo-alt"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Address
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ record.user.province }}, {{ record.user.municipality }}, {{ record.user.barangay }}, {{ record.user.zone }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-telephone"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Contact Number
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        record.user.phone ||
                                                        "N/A"
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-envelope"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Email
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        record.user.email ||
                                                        "N/A"
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reading Information -->
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-speedometer2"
                                            class="mr-2 text-blue-600"
                                        />
                                        Reading Information
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    >
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-calendar-check"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Reading Date
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        formatDate(
                                                            record.reading_date
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-calendar-x"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Due Date
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                    :class="{
                                                        'text-red-600 dark:text-red-400':
                                                            isOverdue,
                                                    }"
                                                >
                                                    {{ dueDate }}
                                                    <span
                                                        v-if="isOverdue"
                                                        class="text-xs ml-1"
                                                        >(Overdue)</span
                                                    >
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-water"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Current Reading (m³)
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ record.reading }} m³
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-arrow-left-right"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Consumption (m³)
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ record.consumption }} m³
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <v-icon
                                                name="bi-cash-coin"
                                                class="mr-2 mt-0.5 text-blue-500"
                                            />
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    Amount
                                                </p>
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    ₱{{ record.amount }}
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
                                                    class="text-xs text-gray-500 dark:text-gray-400"
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
                                    </div>
                                </div>

                                <!-- Notes Section -->
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg shadow-sm"
                                >
                                    <h3
                                        class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                                    >
                                        <v-icon
                                            name="bi-card-text"
                                            class="mr-2 text-blue-600"
                                        />
                                        Additional Notes
                                    </h3>
                                    <div
                                        class="bg-white dark:bg-gray-600 p-3 rounded border border-gray-200 dark:border-gray-500"
                                    >
                                        <p
                                            class="text-gray-700 dark:text-gray-300 whitespace-pre-line text-sm"
                                        >
                                            {{
                                                record.notes ||
                                                "No additional notes"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="border-t border-gray-200 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-700"
                        >
                            <div class="flex justify-between items-center">
                                <div
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Record ID: #{{ record.id }}
                                </div>
                                <button
                                    @click="emit('close')"
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                >
                                    <v-icon name="bi-x-lg" class="mr-2" />
                                    Close
                                </button>
                            </div>
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

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: #cbd5e1;
}

/* Dark mode scrollbar */
.dark .overflow-y-auto {
    scrollbar-color: #4b5563 #1f2937;
}

.dark .overflow-y-auto::-webkit-scrollbar-track {
    background: #1f2937;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #4b5563;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: #6b7280;
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
