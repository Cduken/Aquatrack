<template>
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-[0.98] hover:scale-100">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <v-icon name="bi-speedometer2" class="text-blue-500" />
                    Meter Reading for {{ user.name }}
                </h3>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition">
                    <v-icon name="bi-x-lg" />
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <!-- User Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-person" class="text-gray-400" /> Customer Name
                        </label>
                        <div class="mt-1 text-gray-800 font-medium">{{ user.name }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-credit-card" class="text-gray-400" /> Account Number
                        </label>
                        <div class="mt-1 text-gray-800 font-medium">{{ user.account_number }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-geo-alt" class="text-gray-400" /> Address
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.address }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-telephone" class="text-gray-400" /> Phone
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.phone }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-calendar-check" class="text-gray-400" /> Date Installed
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.date_installed || 'Not available' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-tag" class="text-gray-400" /> Brand
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.brand || 'Not specified' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-upc-scan" class="text-gray-400" /> Serial No.
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.serial_number || 'N/A' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1 flex items-center gap-1">
                            <v-icon name="bi-rulers" class="text-gray-400" /> Size
                        </label>
                        <div class="mt-1 text-gray-800">{{ user.size || 'N/A' }}</div>
                    </div>
                </div>

                <!-- Meter Reading Form -->
                <div class="border-t pt-6">
                    <h4 class="text-md font-medium mb-4 flex items-center gap-2 text-blue-600">
                        <v-icon name="bi-pencil-square" /> New Meter Reading
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                <v-icon name="bi-calendar-month" class="text-gray-400" /> Billing Month
                            </label>
                            <select v-model="newReading.billing_month"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                :disabled="isSubmitting">
                                <option value="">Select Month</option>
                                <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                <v-icon name="bi-calendar-date" class="text-gray-400" /> Reading Date
                            </label>
                            <input v-model="newReading.reading_date" type="date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                :disabled="isSubmitting">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                <v-icon name="bi-123" class="text-gray-400" /> Reading (m³)
                            </label>
                            <div class="relative">
                                <input v-model="newReading.reading" type="number" step="0.01"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                    :disabled="isSubmitting">
                                <span class="absolute right-3 top-2 text-gray-400 text-sm">m³</span>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Readings Table -->
                    <div class="mt-8">
                        <h4 class="text-md font-medium mb-4 flex items-center gap-2 text-gray-700">
                            <v-icon name="bi-clock-history" /> Previous Readings
                        </h4>

                        <div v-if="isLoadingPreviousReadings" class="text-center py-4">
                            <div class="inline-flex items-center text-blue-600">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Loading previous readings...</span>
                            </div>
                        </div>

                        <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1">
                                                <v-icon name="bi-calendar-month" /> Month
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1">
                                                <v-icon name="bi-calendar-date" /> Date
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1">
                                                <v-icon name="bi-speedometer2" /> Reading
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1">
                                                <v-icon name="bi-graph-up" /> Consumption
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1">
                                                <v-icon name="bi-cash-stack" /> Amount
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(reading, index) in previousReadings" :key="index" :class="{'bg-blue-50': index % 2 === 0}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ reading.billing_month }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(reading.reading_date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ reading.reading }} m³
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ reading.consumption }} m³
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                            ₱{{ reading.amount }}
                                        </td>
                                    </tr>
                                    <tr v-if="previousReadings.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No previous readings found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-white border-t p-4 flex justify-end gap-3 z-10">
                <button @click="closeModal" type="button"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition flex items-center gap-1"
                    :disabled="isSubmitting">
                    <v-icon name="bi-x-circle" /> Cancel
                </button>
                <button @click="submitReading" type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:bg-blue-300 transition flex items-center gap-1 shadow-md"
                    :disabled="isSubmitting || !isFormValid">
                    <template v-if="!isSubmitting">
                        <v-icon name="bi-check-circle" /> Submit Reading
                    </template>
                    <template v-else>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Submitting...
                    </template>
                </button>
            </div>
        </div>
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
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
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
            text: `Submit meter reading for ${props.user.name}?`,
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
/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 8px;
}
.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}
.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
