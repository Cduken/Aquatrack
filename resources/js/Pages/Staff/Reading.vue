// Staff/Reading.vue
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
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <v-icon name="bi-speedometer2" class="text-blue-500" /> Meter Readings
                </h2>

                <!-- Search Section -->
                <div class="w-full mb-6">
                    <div class="flex gap-4 items-center w-full">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <v-icon name="bi-search" class="text-gray-400" />
                            </div>
                            <input v-model="searchQuery" type="text"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search by Account Number or Name" @input="debouncedSearch" />
                        </div>
                        <button @click="searchUsers" type="button"
                            class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap flex items-center gap-2 shadow-md"
                            :disabled="isSearching">
                            <v-icon name="bi-search" class="text-lg" />
                            <span v-if="!isSearching">Search</span>
                            <span v-else>Searching...</span>
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="isSearching" class="text-center py-4">
                    <div class="inline-flex items-center text-blue-600">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span>Searching customers...</span>
                    </div>
                </div>

                <!-- Search Results -->
                <div v-if="searchPerformed && !isSearching">
                    <div v-if="searchResults.length > 0" class="mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <v-icon name="bi-people-fill" class="text-blue-500" /> Search Results
                            </h3>
                            <div class="space-y-3">
                                <div v-for="user in searchResults" :key="user.id" @click="openReadingForm(user)"
                                    class="p-4 border rounded-md hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition duration-200 flex justify-between items-start shadow-sm">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800">{{ user.name }} {{ user.lastname }}</span>
                                            <span
                                                class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Serial
                                                # {{ user.serial_number }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                                            <v-icon name="bi-geo-alt" class="text-gray-400 text-xs" />
                                            {{ user.address }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                                            <v-icon name="bi-telephone" class="text-gray-400 text-xs" />
                                            {{ user.phone }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                                            <v-icon name="bi-tag" class="text-gray-400 text-xs" />
                                            {{ user.account_number }}
                                        </div>
                                    </div>
                                    <v-icon name="bi-chevron-right" class="text-gray-400" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div v-else class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <v-icon name="bi-exclamation-circle" class="text-3xl mb-2" />
                            <p class="text-lg font-medium">No customers found</p>
                            <p class="text-sm">Try a different search term</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Reading Modal -->
        <MeterReadingModal v-if="showReadingForm" :user="selectedUser" @close="closeReadingForm"
            @reading-submitted="handleReadingSubmitted" />
    </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { debounce } from 'lodash';
import StaffLayout from '@/Layouts/StaffLayout.vue';
import MeterReadingModal from '@/Components/Staff/Modals/MeterReadingModal.vue';

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const searchPerformed = ref(false);

// Meter reading form
const showReadingForm = ref(false);
const selectedUser = ref({});

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
            searchResults.value = response.data;
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

const debouncedSearch = debounce(searchUsers, 300);

const openReadingForm = (user) => {
    // Make sure we have all the user data
    selectedUser.value = {
        id: user.id,
        name: user.name,
        lastname: user.lastname,
        account_number: user.account_number,
        address: user.address,
        phone: user.phone,
        date_installed: user.date_installed || null,
        brand: user.brand || null,
        serial_number: user.serial_number || null,
        size: user.size || null
    };
    showReadingForm.value = true;
};

const closeReadingForm = () => {
    showReadingForm.value = false;
    selectedUser.value = {};
};

const handleReadingSubmitted = () => {
    closeReadingForm();
};

onMounted(() => {
    return () => {
        debouncedSearch.cancel();
    };
});
</script>
