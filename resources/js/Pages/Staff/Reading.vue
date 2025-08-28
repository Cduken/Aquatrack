<template>
    <StaffLayout title="Meter Readings">
        <div class="w-full bg-white rounded-2xl shadow-sm p-6">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <v-icon
                            name="bi-droplet"
                            class="text-blue-600 text-2xl"
                        />
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl">
                        Meter Readings
                    </h1>
                </div>
                <p class="text-gray-600 text-lg sm:text-xl">
                    Search for customers to record their water meter readings
                </p>
            </div>

            <!-- Search Section -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-8 border border-blue-100"
            >
                <h2
                    class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-3 sm:text-2xl"
                >
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <v-icon name="bi-search-heart" class="text-blue-600" />
                    </div>
                    Find Customer
                </h2>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch">
                    <div class="relative flex-1 w-full">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                        >
                            <v-icon
                                name="bi-search"
                                class="text-gray-400 text-lg"
                            />
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            class="w-full pl-12 pr-10 py-3 border-2 border-gray-200 rounded-xl text-lg leading-6 bg-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all duration-200 sm:py-4"
                            placeholder="Search by name, account number, or serial number..."
                            @input="debouncedSearch"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            type="button"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                        >
                            <v-icon name="bi-x-lg" class="text-lg" />
                        </button>
                    </div>
                    <button
                        @click="searchUsers"
                        type="button"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 min-w-[120px] sm:py-4 sm:px-8"
                        :disabled="isSearching"
                        :class="{
                            'opacity-50 cursor-not-allowed': isSearching,
                        }"
                    >
                        <v-icon
                            v-if="!isSearching"
                            name="bi-search"
                            class="text-lg"
                        />
                        <v-icon
                            v-else
                            name="bi-arrow-repeat"
                            class="text-lg animate-spin"
                        />
                        <span class="font-medium">{{
                            isSearching ? "Searching..." : "Search"
                        }}</span>
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isSearching" class="text-center py-12">
                <div class="flex flex-col items-center justify-center">
                    <div class="p-4 bg-blue-100 rounded-full mb-4">
                        <v-icon
                            name="bi-arrow-repeat"
                            class="text-blue-600 text-2xl animate-spin"
                        />
                    </div>
                    <p class="text-blue-600 font-medium text-lg">
                        Searching customers...
                    </p>
                    <p class="text-gray-500 text-sm">
                        Please wait while we find matching records
                    </p>
                </div>
            </div>

            <!-- Search Results -->
            <div v-if="searchPerformed && !isSearching" class="space-y-6">
                <div v-if="searchResults.length > 0" class="mb-6">
                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1"
                    >
                        <div class="p-4 border-b border-gray-100">
                            <h3
                                class="text-lg font-semibold text-gray-800 flex items-center gap-2 sm:text-xl"
                            >
                                <v-icon
                                    name="bi-people-fill"
                                    class="text-green-500"
                                />
                                Found {{ searchResults.length }} customer{{
                                    searchResults.length !== 1 ? "s" : ""
                                }}
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div
                                v-for="user in searchResults"
                                :key="user.id"
                                @click="openReadingForm(user)"
                                class="p-4 sm:p-6 cursor-pointer transition-all duration-200 hover:bg-blue-50 hover:border-l-4 hover:border-l-blue-400 group"
                            >
                                <div
                                    class="flex flex-col sm:flex-row items-start justify-between gap-4"
                                >
                                    <div class="flex-1">
                                        <div
                                            class="flex items-center gap-3 mb-3 sm:mb-4"
                                        >
                                            <div
                                                class="p-2 rounded-lg transition-colors"
                                            >
                                                <img
                                                    v-if="user.avatar_url"
                                                    :src="user.avatar_url"
                                                    :alt="user.name"
                                                    class="w-10 h-10 rounded-lg object-cover border-2 border-blue-500 shadow-sm sm:w-12 sm:h-12"
                                                />
                                                <v-icon
                                                    v-else
                                                    name="bi-person-circle"
                                                    scale="1.8"
                                                    class="text-blue-600 text-2xl sm:text-3xl"
                                                />
                                            </div>
                                            <div>
                                                <h4
                                                    class="text-lg font-semibold text-gray-800 group-hover:text-blue-700 transition-colors sm:text-xl"
                                                >
                                                    {{ user.name }}
                                                    {{ user.lastname }}
                                                </h4>
                                                <span
                                                    class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium sm:text-base"
                                                >
                                                    Serial #{{
                                                        user.serial_number
                                                    }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-col gap-2 text-gray-600 text-sm sm:text-base"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <v-icon
                                                    name="bi-tag"
                                                    class="text-gray-400"
                                                />
                                                <span class="font-medium"
                                                    >Account:</span
                                                >
                                                <span class="text-gray-800">{{
                                                    user.account_number
                                                }}</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <v-icon
                                                    name="bi-geo-alt"
                                                    class="text-gray-400"
                                                />
                                                <span class="font-medium"
                                                    >Address:</span
                                                >
                                                <span
                                                    class="text-gray-800 truncate"
                                                    >{{ user.address }}</span
                                                >
                                            </div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <v-icon
                                                    name="bi-telephone"
                                                    class="text-gray-400"
                                                />
                                                <span class="font-medium"
                                                    >Phone:</span
                                                >
                                                <span class="text-gray-800">{{
                                                    user.phone
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 ml-0 sm:ml-4 mt-2 sm:mt-0"
                                    >
                                        <span
                                            class="text-sm text-blue-600 font-medium sm:text-base"
                                            >Record Reading</span
                                        >
                                        <v-icon
                                            name="bi-chevron-right"
                                            class="text-gray-400 group-hover:text-blue-600 transition-colors transform group-hover:translate-x-1"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div
                    v-else
                    class="text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl border border-gray-200"
                >
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-4 bg-gray-100 rounded-full mb-4">
                            <v-icon
                                name="bi-search"
                                class="text-gray-400 text-2xl"
                            />
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-700 mb-2 sm:text-2xl"
                        >
                            No customers found
                        </h3>
                        <p class="text-gray-500 mb-4 sm:text-lg">
                            We couldn't find any customers matching your search
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Reading Modal -->
        <MeterReadingModal
            v-if="showReadingForm"
            :user="selectedUser"
            @close="closeReadingForm"
            @reading-submitted="handleReadingSubmitted"
        />
    </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { debounce } from "lodash";
import StaffLayout from "@/Layouts/StaffLayout.vue";
import MeterReadingModal from "@/Components/Staff/Modals/MeterReadingModal.vue";

// Search functionality
const searchQuery = ref("");
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
        const response = await axios.get(route("staff.reading.search"), {
            params: { query: searchQuery.value },
        });

        if (response.data && Array.isArray(response.data)) {
            searchResults.value = response.data;
        } else {
            searchResults.value = [];
        }
    } catch (error) {
        console.error("Error searching users:", error);
        searchResults.value = [];
    } finally {
        isSearching.value = false;
    }
};

const debouncedSearch = debounce(searchUsers, 300);

const clearSearch = () => {
    searchQuery.value = "";
    searchResults.value = [];
    searchPerformed.value = false;
};

const openReadingForm = (user) => {
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
        size: user.size || null,
    };
    showReadingForm.value = true;
};

const closeReadingForm = () => {
    showReadingForm.value = false;
    selectedUser.value = {};
};

const handleReadingSubmitted = () => {
    searchUsers();
};

onMounted(() => {
    return () => {
        debouncedSearch.cancel();
    };
});
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom gradients */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:border-l-4:hover {
    border-left-width: 4px;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    h1 {
        font-size: 2rem;
    }
    p {
        font-size: 1rem;
    }
    .flex-col {
        flex-direction: column;
    }
    .p-6 {
        padding: 1rem;
    }
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .min-w-[px] {
        min-width: 100px;
    }
    .truncate {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

/* Animation for chevron */
.transform {
    transition: transform 0.2s ease-in-out;
}
</style>
