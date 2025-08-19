<template>
    <StaffLayout title="Reading">
        <div class="w-full bg-white shadow-md rounded-lg p-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <v-icon name="bi-droplet" class="text-blue-500" /> AquaTrack
                </h1>
            </div>

            <!-- Content Section -->
            <div>
                <h2 class="text-xl font-semibold mb-6">Reading</h2>

                <!-- Search Section -->
                <div class="w-full mb-6">
                    <div class="flex gap-4 items-center w-full">
                        <div class="relative flex-grow">
                            <input v-model="searchQuery" type="text"
                                class="block w-full pr-3 py-4 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search by Account Number or Name" @input="debouncedSearch" />
                        </div>
                        <button @click="searchUsers" type="button"
                            class="px-6 py-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap flex items-center gap-2"
                            :disabled="isSearching">
                            <v-icon name="bi-search" class="text-lg" />
                            <span v-if="!isSearching">Search</span>
                            <span v-else>Searching...</span>
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="isSearching" class="text-center py-4">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span>Searching...</span>
                    </div>
                </div>

                <!-- Search Results -->
                <div v-if="searchPerformed && !isSearching">
                    <div v-if="searchResults.length > 0" class="mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-700 mb-3">Search Results</h3>
                            <div class="space-y-2">
                                <div v-for="user in searchResults" :key="user.id" @click="openReadingForm(user)"
                                    class="p-3 border rounded-md hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition">
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ user.name }}</span>
                                        <span class="text-gray-600">Account #: {{ user.account_number }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ user.address }}</div>
                                    <div class="text-sm text-gray-500">{{ user.phone }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div v-else class="text-center py-4 text-gray-500">
                        No customers found matching your search.
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Reading Form Modal -->
        <div v-if="showReadingForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="border-b p-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Meter Reading for {{ selectedUser.name }}</h3>
                    <button @click="closeReadingForm" class="text-gray-500 hover:text-gray-700">
                        <v-icon name="bi-x-lg" />
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <!-- User Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Number</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.account_number }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.address }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.phone }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Installed</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.date_installed }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Brand</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.brand }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Serial No.</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.serial_number }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Size</label>
                            <div class="mt-1 p-2 border rounded-md bg-gray-50">{{ selectedUser.size }}</div>
                        </div>
                    </div>

                    <!-- Meter Reading Form -->
                    <div class="border-t pt-4">
                        <h4 class="text-md font-medium mb-4">New Meter Reading</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Billing Month</label>
                                <select v-model="newReading.billing_month"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    :disabled="isSubmitting">
                                    <option value="">Select Month</option>
                                    <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Meter Reading</label>
                                <input v-model="newReading.reading_date" type="date"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    :disabled="isSubmitting">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reading</label>
                                <input v-model="newReading.reading" type="number" step="0.01"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    :disabled="isSubmitting">
                            </div>
                        </div>

                        <!-- Previous Readings Table -->
                        <div class="mt-8">
                            <h4 class="text-md font-medium mb-4">Previous Readings</h4>

                            <div v-if="isLoadingPreviousReadings" class="text-center py-4">
                                <div class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span>Loading previous readings...</span>
                                </div>
                            </div>

                            <div v-else class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Month</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Reading</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Consumption</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(reading, index) in previousReadings" :key="index">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                reading.billing_month }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                formatDate(reading.reading_date) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                reading.reading }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                reading.consumption }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{
                                                reading.amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="border-t p-4 flex justify-end gap-3">
                    <button @click="closeReadingForm" type="button"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                        :disabled="isSubmitting">
                        Cancel
                    </button>
                    <button @click="submitReading" type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:bg-blue-300"
                        :disabled="isSubmitting || !isFormValid">
                        <span v-if="!isSubmitting">Submit Reading</span>
                        <span v-else class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { debounce } from 'lodash';

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const searchPerformed = ref(false);

// Meter reading form
const showReadingForm = ref(false);
const selectedUser = ref({});
const newReading = ref({
    billing_month: '',
    reading_date: '',
    reading: '',
});
const previousReadings = ref([]);
const isLoadingPreviousReadings = ref(false);
const isSubmitting = ref(false);

const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

const isFormValid = computed(() => {
    return (
        newReading.value.billing_month &&
        newReading.value.reading_date &&
        newReading.value.reading
    );
});

// Define searchUsers first
const searchUsers = async () => {
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        searchPerformed.value = false;
        return;
    }

    isSearching.value = true;
    searchPerformed.value = true;

    try {
        const response = await axios.get(route('staff.reading.search'), {
            params: { query: searchQuery.value }
        });

        if (response.data && Array.isArray(response.data)) {
            searchResults.value = response.data.map(user => ({
                ...user,
                address: `${user.zone || ''}, ${user.barangay || ''}`
            }));
        } else {
            searchResults.value = [];
        }
    } catch (error) {
        console.error('Error searching users:', error);
        searchResults.value = [];
    } finally {
        isSearching.value = false;
    }
};

// Then define debouncedSearch after searchUsers is defined
const debouncedSearch = debounce(searchUsers, 300);

const openReadingForm = async (user) => {
    selectedUser.value = user;
    showReadingForm.value = true;

    // Set default values for new reading
    const today = new Date();
    newReading.value = {
        billing_month: months[today.getMonth()],
        reading_date: today.toISOString().split('T')[0],
        reading: '',
    };

    // Fetch previous readings
    await fetchPreviousReadings(user.id);
};

const fetchPreviousReadings = async (userId) => {
    isLoadingPreviousReadings.value = true;
    try {
        const response = await axios.get(route('staff.reading.previous', { userId }));

        if (response.data.error) {
            // Handle specific errors from backend
            if (response.status === 404) {
                alert('Customer not found');
            } else {
                alert('Error loading previous readings');
            }
            previousReadings.value = [];
        } else {
            previousReadings.value = response.data || [];
        }
    } catch (error) {
        console.error('Error fetching previous readings:', error);
        alert('Failed to load previous readings. Please try again.');
        previousReadings.value = [];
    } finally {
        isLoadingPreviousReadings.value = false;
    }
};

const closeReadingForm = () => {
    showReadingForm.value = false;
    selectedUser.value = {};
    previousReadings.value = [];
};

const submitReading = async () => {
    if (!isFormValid.value) return;

    isSubmitting.value = true;
    try {
        await axios.post(route('staff.reading.store'), {
            user_id: selectedUser.value.id,
            ...newReading.value
        });

        // Refresh previous readings
        await fetchPreviousReadings(selectedUser.value.id);

        // Reset form
        newReading.value.reading = '';

        alert('Meter reading submitted successfully!');
    } catch (error) {
        console.error('Error submitting reading:', error);
        alert('Failed to submit reading. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
};

// Cleanup debounce on unmount
onMounted(() => {
    return () => {
        debouncedSearch.cancel();
    };
});
</script>

<style scoped>
/* Custom styles if needed */
</style>
