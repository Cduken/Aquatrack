<template>
    <div class="fixed inset-0 z-50 overflow-hidden">
        <!-- Backdrop -->
        <Transition name="fade">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeModal"></div>
        </Transition>

        <!-- Slide Panel -->
        <Transition name="slide">
            <div class="fixed inset-y-0 right-0 max-w-[50%] w-full bg-white shadow-2xl flex flex-col">
                <!-- Header -->
                <div
                    class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-[#062F64] to-[#2a5298] text-white">
                    <div class="flex items-center gap-3">
                        <v-icon name="bi-speedometer2" scale="1.5" class="text-white text-xl" />
                        <h3 class="text-xl font-semibold">Meter Reading Card</h3>
                    </div>
                    <button @click="closeModal" class="p-2 rounded-lg hover:bg-blue-800 transition-colors">
                        <v-icon name="bi-x-lg" class="text-white text-lg" />
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <!-- User Information Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-person" class="text-blue-500" />
                                    <span class="text-sm font-medium">Customer Name</span>
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ user.name }} {{ user.lastname }}
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-credit-card" class="text-blue-500" />
                                    <span class="text-sm font-medium">Account Number</span>
                                </div>
                                <div class="text-lg font-semibold text-gray-800">{{ user.account_number }}</div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-geo-alt" class="text-blue-500" />
                                    <span class="text-sm font-medium">Address</span>
                                </div>
                                <div class="text-gray-800">{{ user.address }}</div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-telephone" class="text-blue-500" />
                                    <span class="text-sm font-medium">Phone</span>
                                </div>
                                <div class="text-gray-800">{{ user.phone }}</div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-calendar-check" class="text-blue-500" />
                                    <span class="text-sm font-medium">Date Installed</span>
                                </div>
                                <div class="text-gray-800">{{ formatDate(user.date_installed) || 'Not available' }}
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-tag" class="text-blue-500" />
                                    <span class="text-sm font-medium">Brand</span>
                                </div>
                                <div class="text-gray-800">{{ user.brand || 'Not specified' }}</div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-upc-scan" class="text-blue-500" />
                                    <span class="text-sm font-medium">Serial No.</span>
                                </div>
                                <div class="text-gray-800">{{ user.serial_number || 'N/A' }}</div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-700 mb-2">
                                    <v-icon name="bi-rulers" class="text-blue-500" />
                                    <span class="text-sm font-medium">Size</span>
                                </div>
                                <div class="text-gray-800">{{ user.size || 'N/A' }} mm</div>
                            </div>
                        </div>

                        <!-- Meter Reading Form -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"></div>
                                <h4 class="text-lg font-semibold text-gray-800">New Meter Reading</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-calendar-month" class="text-blue-500" />
                                        Billing Month
                                    </label>
                                    <select v-model="newReading.billing_month"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        :disabled="isSubmitting">
                                        <option value="">Select Month</option>
                                        <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-calendar-date" class="text-blue-500" />
                                        Reading Date
                                    </label>
                                    <input v-model="newReading.reading_date" type="date"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        :disabled="isSubmitting">
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-123" class="text-blue-500" />
                                        Reading (m³)
                                    </label>
                                    <div class="relative">
                                        <input v-model="newReading.reading" type="number" step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12"
                                            :disabled="isSubmitting">
                                        <span
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">m³</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Readings -->
                            <div class="mt-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-2 h-6 bg-gradient-to-b from-gray-400 to-gray-500 rounded-full"></div>
                                    <h4 class="text-md font-semibold text-gray-700">Previous Readings</h4>
                                </div>

                                <div v-if="isLoadingPreviousReadings" class="text-center py-8">
                                    <div class="inline-flex items-center text-blue-600">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span>Loading previous readings...</span>
                                    </div>
                                </div>

                                <div v-else class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div v-if="previousReadings.length === 0" class="text-center py-8 text-gray-500">
                                        <v-icon name="bi-clock-history" class="text-3xl mb-2 opacity-50" />
                                        <p>No previous readings found</p>
                                    </div>

                                    <div v-else class="space-y-3 max-h-60 overflow-y-auto">
                                        <div v-for="(reading, index) in previousReadings" :key="index"
                                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="grid grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <div class="text-xs text-gray-500 font-medium">Month</div>
                                                    <div class="font-medium text-gray-800">{{ reading.billing_month }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 font-medium">Date</div>
                                                    <div class="text-gray-700">{{ formatDate(reading.reading_date) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 font-medium">Reading</div>
                                                    <div class="font-medium text-blue-600">{{ reading.reading }} m³
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 font-medium">Amount</div>
                                                    <div class="font-medium text-green-600">₱{{ reading.amount }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 p-6 bg-white">
                    <div class="flex gap-3 justify-end">
                        <button @click="closeModal" type="button"
                            class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 flex items-center gap-2 font-medium"
                            :disabled="isSubmitting">
                            <v-icon name="bi-x-circle" />
                            Cancel
                        </button>
                        <button @click="submitReading" type="button"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 transition-all duration-200 flex items-center gap-2 font-medium shadow-lg hover:shadow-xl"
                            :disabled="isSubmitting || !isFormValid">
                            <template v-if="!isSubmitting">
                                <v-icon name="bi-check-circle" />
                                Submit Reading
                            </template>
                            <template v-else>
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Submitting...
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close', 'reading-submitted']);

const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const newReading = ref({
    billing_month: '',
    reading_date: '',
    reading: '',
});

const previousReadings = ref([]);
const isLoadingPreviousReadings = ref(false);
const isSubmitting = ref(false);

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } catch (error) {
        return 'Invalid date';
    }
};

const isFormValid = computed(() => {
    return (
        newReading.value.billing_month &&
        newReading.value.reading_date &&
        newReading.value.reading
    );
});

const closeModal = () => {
    emit('close');
};

const fetchPreviousReadings = async () => {
    isLoadingPreviousReadings.value = true;
    try {
        const response = await axios.get(route('staff.reading.previous', { userId: props.user.id }));

        if (response.data.error) {
            if (response.status === 404) {
                Swal.fire({
                    icon: 'error',
                    title: 'Customer not found',
                    text: 'The customer record could not be located',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error loading readings',
                    text: 'There was a problem loading the previous meter readings',
                });
            }
            previousReadings.value = [];
        } else {
            previousReadings.value = response.data || [];
        }
    } catch (error) {
        console.error('Error fetching previous readings:', error);
        Swal.fire({
            icon: 'error',
            title: 'Failed to load readings',
            text: 'Please try again later',
        });
        previousReadings.value = [];
    } finally {
        isLoadingPreviousReadings.value = false;
    }
};

const submitReading = async () => {
    if (!isFormValid.value) return;

    isSubmitting.value = true;
    try {
        const result = await Swal.fire({
            title: 'Confirm Submission',
            text: `Submit meter reading for ${props.user.name} ${props.user.lastname}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            await axios.post(route('staff.reading.store'), {
                user_id: props.user.id,
                ...newReading.value
            });

            // Refresh previous readings
            await fetchPreviousReadings();

            // Reset form
            newReading.value.reading = '';

            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Meter reading submitted successfully',
                timer: 2000,
                showConfirmButton: false
            });

            emit('reading-submitted');
        }
    } catch (error) {
        console.error('Error submitting reading:', error);
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'There was an error submitting the meter reading',
        });
    } finally {
        isSubmitting.value = false;
    }
};

onMounted(() => {
    // Set default values for new reading
    const today = new Date();
    newReading.value = {
        billing_month: months[today.getMonth()],
        reading_date: today.toISOString().split('T')[0],
        reading: '',
    };

    // Fetch previous readings
    fetchPreviousReadings();
});
</script>

<style scoped>
/* Enhanced Slide animations */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1), opacity 0.4s ease;
}

.slide-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.slide-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}

/* Enhanced Fade animation for backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth transitions for all interactive elements */
button,
input,
select {
    transition: all 0.2s ease;
}

/* Hover effects for cards */
.bg-gradient-to-br {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.bg-gradient-to-br:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
</style>
