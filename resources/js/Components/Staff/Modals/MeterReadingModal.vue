<template>
    <div class="fixed inset-0 z-50 overflow-hidden">
        <!-- Backdrop -->
        <Transition name="fade">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeModal"></div>
        </Transition>

        <!-- Slide Panel -->
        <Transition name="slide">
            <div class="fixed inset-y-0 right-0 w-[50vw] max-w-2xl bg-white shadow-2xl flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-blue-700 bg-gradient-to-r from-[#062F64] to-[#2a5298] text-white">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-700 rounded-lg">
                            <v-icon name="bi-speedometer2" scale="1.2" class="text-white" />
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Meter Reading Card</h3>
                            <p class="text-blue-100 text-sm mt-1">Record water consumption for billing</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="p-2 rounded-lg hover:bg-blue-800 transition-colors">
                        <v-icon name="bi-x-lg" class="text-white text-lg" />
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <!-- Customer Summary Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-2xl border border-blue-100 shadow-sm mb-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-blue-100 rounded-xl">
                                        <v-icon name="bi-person-circle" class="text-blue-600 text-2xl" />
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800">{{ user.name }} {{ user.lastname }}</h2>
                                        <p class="text-gray-600 mt-1">{{ user.address }}</p>
                                        <div class="flex gap-4 mt-2">
                                            <span class="flex items-center gap-1 text-sm text-gray-500">
                                                <v-icon name="bi-credit-card" class="text-blue-500" />
                                                #{{ user.account_number }}
                                            </span>
                                            <span class="flex items-center gap-1 text-sm text-gray-500">
                                                <v-icon name="bi-telephone" class="text-blue-500" />
                                                {{ user.phone }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">
                                        <v-icon name="bi-check-circle" class="mr-1" /> Active
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Installed: {{ formatDate(user.date_installed) || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Meter Details Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <v-icon name="bi-tag" class="text-blue-500" />
                                    <span class="text-xs font-medium">Brand</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-800">{{ user.brand || 'Not specified' }}</div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <v-icon name="bi-upc-scan" class="text-blue-500" />
                                    <span class="text-xs font-medium">Serial No.</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-800">{{ user.serial_number || 'N/A' }}</div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <v-icon name="bi-rulers" class="text-blue-500" />
                                    <span class="text-xs font-medium">Size</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-800">{{ user.size || 'N/A' }} mm</div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <v-icon name="bi-calendar-check" class="text-blue-500" />
                                    <span class="text-xs font-medium">Last Reading</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-800">{{ lastReadingDate || 'No records' }}</div>
                            </div>
                        </div>

                        <!-- Meter Reading Form -->
                        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm mb-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-1 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"></div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">New Meter Reading</h4>
                                    <p class="text-sm text-gray-500">Enter current meter values</p>
                                </div>
                            </div>

                            <!-- Year Transition Warning -->
                            <div v-if="showYearTransitionWarning" class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 mb-4 flex items-start gap-3">
                                <div class="p-2 bg-yellow-100 rounded-lg mt-0.5">
                                    <v-icon name="bi-exclamation-triangle" class="text-yellow-600" />
                                </div>
                                <div>
                                    <div class="font-medium text-yellow-800">Year Transition Detected</div>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        You're entering a reading for January after December. Please ensure this is correct
                                        as it represents a new billing year.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-123" class="text-blue-500" />
                                        Previous Reading (m³)
                                    </label>
                                    <div class="relative">
                                        <input v-model="newReading.previous_reading" type="number" step="0.01"
                                            class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12 bg-gray-50"
                                            disabled>
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">m³</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Last recorded reading</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-123" class="text-blue-500" />
                                        Current Reading (m³) *
                                    </label>
                                    <div class="relative">
                                        <input v-model="newReading.reading" type="number" step="0.01"
                                            class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12"
                                            :class="{'border-red-300': readingError}"
                                            :disabled="isSubmitting"
                                            @input="calculateConsumptionAndAmount"
                                            placeholder="Enter current reading">
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">m³</span>
                                    </div>
                                    <p v-if="readingError" class="text-xs text-red-500">{{ readingError }}</p>
                                    <p v-else class="text-xs text-gray-500">Must be higher than previous reading</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-lightning-charge" class="text-blue-500" />
                                        Consumption (m³)
                                    </label>
                                    <div class="relative">
                                        <input v-model="newReading.consumption" type="number" step="0.01"
                                            class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12 bg-gray-50"
                                            disabled>
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">m³</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Calculated automatically</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <v-icon name="bi-currency-dollar" class="text-blue-500" />
                                        Amount (₱)
                                    </label>
                                    <div class="relative">
                                        <input v-model="newReading.amount" type="number" step="0.01"
                                            class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12 bg-gray-50"
                                            disabled>
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₱</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Based on consumption rate</p>
                                </div>
                            </div>

                            <!-- Summary Card -->
                            <div v-if="newReading.consumption > 0" class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-blue-700 font-medium">Billing Summary</div>
                                        <div class="text-2xl font-bold text-blue-800 mt-1">₱{{ newReading.amount.toFixed(2) }}</div>
                                        <div class="text-sm text-blue-600 mt-1">{{ newReading.consumption }} m³ consumption</div>
                                    </div>
                                    <div class="p-3 bg-blue-100 rounded-xl">
                                        <v-icon name="bi-receipt" class="text-blue-600 text-2xl" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Previous Readings Section -->
                        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1 h-6 bg-gradient-to-b from-gray-400 to-gray-500 rounded-full"></div>
                                    <h4 class="text-md font-semibold text-gray-700">Reading History</h4>
                                </div>

                                <!-- Year Filter -->
                                <div class="flex items-center gap-2">
                                    <v-icon name="bi-filter" class="text-gray-500" />
                                    <select v-model="selectedYear"
                                        class="px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm">
                                        <option value="">All Years</option>
                                        <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                </div>
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
                                    <span>Loading reading history...</span>
                                </div>
                            </div>

                            <div v-else>
                                <div v-if="filteredPreviousReadings.length === 0" class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl">
                                    <v-icon name="bi-clock-history" class="text-3xl mb-2 opacity-50" />
                                    <p>No previous readings found</p>
                                    <p class="text-sm mt-1">Start by submitting a new reading above</p>
                                </div>

                                <div v-else class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                                    <div v-for="(reading, index) in filteredPreviousReadings" :key="index"
                                        class="bg-gray-50 p-4 rounded-xl border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors group">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="p-2 bg-white rounded-lg border border-gray-200 group-hover:border-blue-200">
                                                    <v-icon name="bi-droplet" class="text-blue-500" />
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-800">{{ reading.billing_month }} {{ reading.year }}</div>
                                                    <div class="text-sm text-gray-600">{{ formatDate(reading.reading_date) }}</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-semibold text-blue-600">{{ reading.reading }} m³</div>
                                                <div class="text-sm text-gray-600">{{ reading.consumption }} m³ consumed</div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200 group-hover:border-blue-200">
                                            <div class="text-sm text-gray-600">Amount billed</div>
                                            <div class="font-medium text-green-600">₱{{ reading.amount.toFixed(2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 p-6 bg-white sticky bottom-0 shadow-lg">
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
                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
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
import { ref, computed, onMounted, watch } from 'vue';
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
    previous_reading: 0,
    consumption: 0,
    amount: 0
});

const previousReadings = ref([]);
const selectedYear = ref('');
const availableYears = computed(() => {
    const years = new Set(previousReadings.value.map(reading => reading.year));
    return [...years].sort((a, b) => b - a);
});

const filteredPreviousReadings = computed(() => {
    return sortedPreviousReadings.value.filter(reading =>
        !selectedYear.value || reading.year === selectedYear.value
    );
});

const sortedPreviousReadings = computed(() => {
    return [...previousReadings.value].sort((a, b) => {
        const dateA = new Date(`${a.billing_month} 1, ${a.year}`);
        const dateB = new Date(`${b.billing_month} 1, ${b.year}`);
        return dateB - dateA;
    });
});

const isLoadingPreviousReadings = ref(false);
const isSubmitting = ref(false);
const showYearTransitionWarning = ref(false);

// Computed property for last reading date
const lastReadingDate = computed(() => {
    if (previousReadings.value.length === 0) return null;
    const latest = sortedPreviousReadings.value[0];
    return formatDate(latest.reading_date);
});

// Computed property for reading validation error
const readingError = computed(() => {
    if (!newReading.value.reading) return '';
    const current = parseFloat(newReading.value.reading);
    const previous = parseFloat(newReading.value.previous_reading);

    if (current < previous) {
        return `Current reading must be higher than previous reading (${previous} m³)`;
    }

    return '';
});

// Watch for changes in previous readings to update the previous reading value
watch(previousReadings, (newVal) => {
    if (newVal.length > 0) {
        updatePreviousReading();
    } else {
        newReading.value.previous_reading = 0;
    }
}, { deep: true });

const updatePreviousReading = () => {
    if (!newReading.value.billing_month) {
        newReading.value.previous_reading = 0;
        return;
    }

    const selectedDate = new Date(newReading.value.reading_date);
    const selectedYear = selectedDate.getFullYear();
    const selectedMonthIndex = months.indexOf(newReading.value.billing_month);

    // Find the most recent reading before the selected date
    const readingsBeforeCurrent = previousReadings.value.filter(reading => {
        const readingDate = new Date(`${reading.billing_month} 1, ${reading.year}`);
        return readingDate < selectedDate;
    });

    // Check for year transition (December to January)
    const hasDecemberReading = previousReadings.value.some(r => r.billing_month === 'December' && parseInt(r.year) === selectedYear - 1);
    showYearTransitionWarning.value = (selectedMonthIndex === 0 && hasDecemberReading);

    if (readingsBeforeCurrent.length > 0) {
        // Get the latest reading before the selected date
        const latestReading = readingsBeforeCurrent.reduce((latest, current) => {
            const latestDate = new Date(`${latest.billing_month} 1, ${latest.year}`);
            const currentDate = new Date(`${current.billing_month} 1, ${current.year}`);
            return currentDate > latestDate ? current : latest;
        });

        newReading.value.previous_reading = latestReading.reading;
    } else {
        newReading.value.previous_reading = 0;
    }

    // Recalculate consumption and amount
    calculateConsumptionAndAmount();
};

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
        newReading.value.reading !== '' &&
        parseFloat(newReading.value.reading) >= parseFloat(newReading.value.previous_reading)
    );
});

const closeModal = () => {
    emit('close');
};

const calculateConsumptionAndAmount = () => {
    const currentReading = parseFloat(newReading.value.reading) || 0;
    const previousReading = parseFloat(newReading.value.previous_reading) || 0;

    // Calculate consumption
    newReading.value.consumption = Math.max(0, currentReading - previousReading);

    // Calculate amount only if there's consumption
    if (newReading.value.consumption > 0) {
        const consumption = newReading.value.consumption;
        let amount = 0;

        if (consumption <= 10) {
            amount = 132.00;
        } else if (consumption <= 20) {
            amount = 132.00 + (consumption - 10) * 14;
        } else if (consumption <= 30) {
            amount = 132.00 + (10 * 14) + ((consumption - 20) * 14.85);
        } else if (consumption <= 40) {
            amount = 132.00 + (10 * 14) + (10 * 14.85) + ((consumption - 30) * 16);
        } else {
            amount = 132.00 + (10 * 14) + (10 * 14.85) + (10 * 16) + ((consumption - 40) * 17.25);
        }

        newReading.value.amount = parseFloat(amount.toFixed(2));
    } else {
        newReading.value.amount = 0;
    }
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

    // Validate that current reading is not less than previous reading
    if (parseFloat(newReading.value.reading) < parseFloat(newReading.value.previous_reading)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Reading',
            text: 'Current reading cannot be less than previous reading',
        });
        return;
    }

    // Additional validation for year transition
    const selectedDate = new Date(newReading.value.reading_date);
    const selectedYear = selectedDate.getFullYear();
    const currentMonthIndex = months.indexOf(newReading.value.billing_month);
    const hasDecemberReading = previousReadings.value.some(r => r.billing_month === 'December' && parseInt(r.year) === selectedYear - 1);

    if (currentMonthIndex === 0 && hasDecemberReading) {
        // This is January and there's a December reading from the previous year - confirm this is intentional
        const result = await Swal.fire({
            title: 'Year Transition Confirmation',
            html: `You're entering a reading for <strong>January ${selectedYear}</strong> after a <strong>December ${selectedYear - 1}</strong> reading.<br><br>
                   This indicates a new billing year. Is this correct?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, new year',
            cancelButtonText: 'No, cancel'
        });

        if (!result.isConfirmed) {
            return;
        }
    }

    isSubmitting.value = true;
    try {
        const result = await Swal.fire({
            title: 'Confirm Submission',
            html: `Submit meter reading for ${props.user.name} ${props.user.lastname}?<br>
                   <strong>Billing Month:</strong> ${newReading.value.billing_month}<br>
                   <strong>Reading:</strong> ${newReading.value.reading} m³<br>
                   ${newReading.value.consumption > 0 ? `<strong>Consumption:</strong> ${newReading.value.consumption} m³<br>` : ''}
                   ${newReading.value.amount > 0 ? `<strong>Amount:</strong> ₱${newReading.value.amount.toFixed(2)}` : ''}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            const response = await axios.post(route('staff.reading.store'), {
                user_id: props.user.id,
                billing_month: newReading.value.billing_month,
                reading_date: newReading.value.reading_date,
                reading: newReading.value.reading
            });

            if (response.data.error) {
                throw new Error(response.data.error);
            }

            // Refresh previous readings to get the updated list
            await fetchPreviousReadings();

            // Reset only the current reading field, keep others
            newReading.value.reading = '';
            newReading.value.consumption = 0;
            newReading.value.amount = 0;
            showYearTransitionWarning.value = false;

            // Show success message but don't close the modal
            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Meter reading submitted successfully',
                timer: 2000,
                showConfirmButton: false
            });

            // Emit event to parent if needed
            emit('reading-submitted');
        }
    } catch (error) {
        console.error('Error submitting reading:', error);
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: error.response?.data?.error || error.message || 'There was an error submitting the meter reading',
        });
    } finally {
        isSubmitting.value = false;
    }
};

onMounted(() => {
    // Set default values for new reading
    const today = new Date();
    const currentMonth = months[today.getMonth()];
    newReading.value = {
        billing_month: currentMonth,
        reading_date: today.toISOString().split('T')[0],
        reading: '',
        previous_reading: 0,
        consumption: 0,
        amount: 0
    };

    // Set default year filter to current year
    selectedYear.value = today.getFullYear().toString();

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
