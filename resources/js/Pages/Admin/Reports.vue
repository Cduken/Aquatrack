<template>
    <AdminLayout title="Reports">
        <div class="mx-auto w-full">
            <div class="lg:items-center lg:flex mb-4 hidden">
                <v-icon
                    name="md-reportproblem"
                    class="mr-2 text-amber-500"
                    scale="1.5"
                />
                <h1 class="text-2xl">Reports Management</h1>
            </div>

            <!-- Reports Stats Cards Grid -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
            >
                <!-- Total Reports Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400"
                        >
                            <v-icon name="bi-clipboard-data" class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p
                                class="text-sm font-medium text-gray-600 dark:text-gray-400"
                            >
                                Total Reports
                            </p>
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ reports.total || 0 }}
                            </h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div
                            class="flex items-center text-sm text-blue-600 dark:text-blue-400"
                        >
                            <v-icon name="bi-list-ul" class="w-4 h-4 mr-1" />
                            <span>All system reports</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400"
                        >
                            <v-icon name="bi-clock" class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p
                                class="text-sm font-medium text-gray-600 dark:text-gray-400"
                            >
                                Pending Reports
                            </p>
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ pendingReportsCount }}
                            </h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div
                            class="flex items-center text-sm text-yellow-600 dark:text-yellow-400"
                        >
                            <v-icon
                                name="bi-hourglass-split"
                                class="w-4 h-4 mr-1"
                            />
                            <span>{{ pendingPercentage }}% of total</span>
                        </div>
                    </div>
                </div>

                <!-- In Progress Reports Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400"
                        >
                            <v-icon name="bi-arrow-repeat" class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p
                                class="text-sm font-medium text-gray-600 dark:text-gray-400"
                            >
                                In Progress
                            </p>
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ inProgressReportsCount }}
                            </h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div
                            class="flex items-center text-sm text-blue-600 dark:text-blue-400"
                        >
                            <v-icon name="bi-gear" class="w-4 h-4 mr-1" />
                            <span>{{ inProgressPercentage }}% of total</span>
                        </div>
                    </div>
                </div>

                <!-- Resolved Reports Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400"
                        >
                            <v-icon name="bi-check-circle" class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p
                                class="text-sm font-medium text-gray-600 dark:text-gray-400"
                            >
                                Resolved Reports
                            </p>
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ resolvedReportsCount }}
                            </h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div
                            class="flex items-center text-sm text-green-600 dark:text-green-400"
                        >
                            <v-icon name="bi-check-lg" class="w-4 h-4 mr-1" />
                            <span>{{ resolvedPercentage }}% of total</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Section with Filters -->
            <div
                class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg mb-6"
            >
                <div
                    class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
                >
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only"
                                >Search</label
                            >
                            <div class="relative w-full">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                >
                                    <v-icon
                                        name="hi-solid-search"
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    />
                                </div>
                                <input
                                    v-model="filters.search"
                                    type="text"
                                    id="simple-search"
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search reports, names..."
                                    @keyup.enter="getReports"
                                />
                            </div>
                        </form>
                    </div>
                    <div
                        class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3"
                    >
                        <div
                            class="flex items-center w-full space-x-3 md:w-auto"
                        >
                            <div class="relative">
                                <button
                                    @click="
                                        showFilterDropdown = !showFilterDropdown
                                    "
                                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button"
                                >
                                    <v-icon
                                        name="hi-solid-filter"
                                        class="w-4 h-4 mr-2 text-gray-400"
                                    />
                                    Filter
                                    <v-icon
                                        name="hi-chevron-down"
                                        class="-mr-1 ml-1.5 w-5 h-5"
                                    />
                                </button>

                                <!-- Custom Filter Dropdown -->
                                <div
                                    v-if="showFilterDropdown"
                                    class="absolute z-10 top-full right-0 mt-1 w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700"
                                >
                                    <h6
                                        class="mb-3 text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        Status
                                    </h6>
                                    <ul class="space-y-2 text-sm">
                                        <li class="flex items-center">
                                            <input
                                                id="status-all"
                                                type="radio"
                                                name="status"
                                                value=""
                                                :checked="!filters.status"
                                                @change="updateStatusFilter('')"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="status-all"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >All Status</label
                                            >
                                        </li>
                                        <li class="flex items-center">
                                            <input
                                                id="status-pending"
                                                type="radio"
                                                name="status"
                                                value="pending"
                                                :checked="
                                                    filters.status === 'pending'
                                                "
                                                @change="
                                                    updateStatusFilter(
                                                        'pending'
                                                    )
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-yellow-600 focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="status-pending"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >Pending</label
                                            >
                                        </li>
                                        <li class="flex items-center">
                                            <input
                                                id="status-in_progress"
                                                type="radio"
                                                name="status"
                                                value="in_progress"
                                                :checked="
                                                    filters.status ===
                                                    'in_progress'
                                                "
                                                @change="
                                                    updateStatusFilter(
                                                        'in_progress'
                                                    )
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="status-in_progress"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >In Progress</label
                                            >
                                        </li>
                                        <li class="flex items-center">
                                            <input
                                                id="status-resolved"
                                                type="radio"
                                                name="status"
                                                value="resolved"
                                                :checked="
                                                    filters.status ===
                                                    'resolved'
                                                "
                                                @change="
                                                    updateStatusFilter(
                                                        'resolved'
                                                    )
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="status-resolved"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >Resolved</label
                                            >
                                        </li>
                                    </ul>
                                    <h6
                                        class="mb-3 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        User Type
                                    </h6>
                                    <ul class="space-y-2 text-sm">
                                        <li class="flex items-center">
                                            <input
                                                id="user-type-all"
                                                type="radio"
                                                name="userType"
                                                value="all"
                                                :checked="
                                                    !filters.userType ||
                                                    filters.userType === 'all'
                                                "
                                                @change="
                                                    updateUserTypeFilter('all')
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="user-type-all"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >All Users</label
                                            >
                                        </li>
                                        <li class="flex items-center">
                                            <input
                                                id="user-type-guest"
                                                type="radio"
                                                name="userType"
                                                value="guest"
                                                :checked="
                                                    filters.userType === 'guest'
                                                "
                                                @change="
                                                    updateUserTypeFilter(
                                                        'guest'
                                                    )
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="user-type-guest"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >Guest</label
                                            >
                                        </li>
                                        <li class="flex items-center">
                                            <input
                                                id="user-type-authenticated"
                                                type="radio"
                                                name="userType"
                                                value="authenticated"
                                                :checked="
                                                    filters.userType ===
                                                    'authenticated'
                                                "
                                                @change="
                                                    updateUserTypeFilter(
                                                        'authenticated'
                                                    )
                                                "
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                            />
                                            <label
                                                for="user-type-authenticated"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >Registered</label
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button
                                @click="resetFilters"
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                            >
                                <v-icon
                                    name="hi-refresh"
                                    class="w-4 h-4 mr-1"
                                />
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="relative overflow-x-auto mb-4 shadow-md sm:rounded-lg">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Reporter</th>
                            <th scope="col" class="px-6 py-3">User Type</th>
                            <th scope="col" class="px-6 py-3">Zone</th>
                            <th scope="col" class="px-6 py-3">Barangay</th>
                            <th scope="col" class="px-6 py-3">Priority</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="report in filteredReports"
                            :key="report.id"
                            class="border-b dark:border-gray-700 border-gray-200"
                            :class="getStatusRowClass(report.status)"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                #{{ report.id }}
                            </th>
                            <td
                                class="px-6 py-4 font-medium text-gray-900 dark:text-white"
                            >
                                {{
                                    report.reporter_name ||
                                    report.user?.name ||
                                    "N/A"
                                }}
                                <span
                                    v-if="report.user_id"
                                    class="text-xs text-gray-400 block"
                                >
                                    User ID: {{ report.user_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="userTypeClasses(report.user_id)"
                                    class="inline-flex items-center text-xs px-2 py-1 rounded-full"
                                >
                                    <v-icon
                                        :name="
                                            report.user_id
                                                ? 'hi-user'
                                                : 'hi-users'
                                        "
                                        class="w-3 h-3 mr-1"
                                    />
                                    {{
                                        report.user_id ? "Registered" : "Guest"
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ report.zone || "N/A" }}
                            </td>
                            <td class="px-6 py-4">
                                {{ report.barangay || "N/A" }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="priorityClasses(report.priority)"
                                    class="inline-flex items-center text-xs px-2 py-1 rounded-full"
                                >
                                    <v-icon
                                        :name="getPriorityIcon(report.priority)"
                                        class="w-3 h-3 mr-1"
                                    />
                                    {{ report.priority || "N/A" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="statusClasses(report.status)"
                                    class="inline-flex items-center text-xs px-2 py-1 rounded-full"
                                >
                                    <v-icon
                                        :name="getStatusIcon(report.status)"
                                        class="w-3 h-3 mr-1"
                                    />
                                    {{ formatStatus(report.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ formatDate(report.created_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    <button
                                        @click="openModal(report)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="View"
                                    >
                                        <v-icon name="hi-eye" class="w-5 h-5" />
                                    </button>
                                    <div class="relative">
                                        <button
                                            @click="openStatusModal(report)"
                                            class="text-yellow-500 hover:text-yellow-700 transition-colors flex items-center"
                                            title="Change Status"
                                        >
                                            <v-icon
                                                name="hi-pencil"
                                                class="w-5 h-5"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredReports.length === 0">
                            <td
                                colspan="9"
                                class="px-6 py-8 text-center text-sm text-gray-500"
                            >
                                <div
                                    class="flex flex-col items-center justify-center space-y-2"
                                >
                                    <v-icon
                                        name="bi-clipboard-data"
                                        class="w-10 h-10 text-gray-300 mb-2"
                                    />
                                    <span class="font-semibold text-gray-400"
                                        >No reports found</span
                                    >
                                    <span class="text-xs text-gray-300"
                                        >Try adjusting your filters or search
                                        keywords.</span
                                    >
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <Pagination
                v-if="reports.data && reports.data.length > 0"
                :items="reports"
                item-name="reports"
                class="mt-6"
            />

            <!-- Report Details Modal -->
            <ReportDetailsModal
                :show="showModal"
                :report="selectedReport"
                @close="closeModal"
            />


        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ReportDetailsModal from "@/Components/Modals/ReportDetailsModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const props = defineProps({
    reports: Object,
    filters: Object,
    canEdit: Boolean,
});

const filters = ref({
    userType: props.filters?.userType || "all",
    status: props.filters?.status || "all",
    search: props.filters?.search || "",
});

// State
const selectedReport = ref(null);
const showModal = ref(false);
const showFilterDropdown = ref(false);
const updatingStatus = ref(false);

// Computed properties for statistics (unchanged)
const pendingReportsCount = computed(() => {
    return props.reports.data.filter((report) => report.status === "pending")
        .length;
});

const inProgressReportsCount = computed(() => {
    return props.reports.data.filter(
        (report) => report.status === "in_progress"
    ).length;
});

const resolvedReportsCount = computed(() => {
    return props.reports.data.filter((report) => report.status === "resolved")
        .length;
});

const pendingPercentage = computed(() => {
    if (!props.reports.total || props.reports.total === 0) return 0;
    return Math.round((pendingReportsCount.value / props.reports.total) * 100);
});

const inProgressPercentage = computed(() => {
    if (!props.reports.total || props.reports.total === 0) return 0;
    return Math.round(
        (inProgressReportsCount.value / props.reports.total) * 100
    );
});

const resolvedPercentage = computed(() => {
    if (!props.reports.total || props.reports.total === 0) return 0;
    return Math.round((resolvedReportsCount.value / props.reports.total) * 100);
});

// Filtered reports (unchanged)
const filteredReports = computed(() => {
    return props.reports.data.filter((report) => {
        if (filters.value.userType === "guest" && report.user_id) return false;
        if (filters.value.userType === "authenticated" && !report.user_id)
            return false;
        if (
            filters.value.status !== "all" &&
            report.status !== filters.value.status
        )
            return false;

        if (filters.value.search) {
            const searchTerm = filters.value.search.toLowerCase();
            const matchesId = report.id.toString().includes(searchTerm);
            const matchesName = (
                report.reporter_name ||
                report.user?.name ||
                ""
            )
                .toLowerCase()
                .includes(searchTerm);
            if (!matchesId && !matchesName) return false;
        }
        return true;
    });
});

// Handle click outside to close filter dropdown only
const handleClickOutside = (event) => {
    if (!event.target.closest(".relative")) {
        showFilterDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

// Watch filters with debounce (unchanged)
watch(
    filters,
    debounce(() => {
        router.get(route("admin.reports"), filters.value, {
            preserveState: true,
            replace: true,
        });
    }, 300),
    { deep: true }
);

// Filter functions (unchanged)
const updateStatusFilter = (status) => {
    filters.value.status = status;
    showFilterDropdown.value = false;
};

const updateUserTypeFilter = (userType) => {
    filters.value.userType = userType;
    showFilterDropdown.value = false;
};

const resetFilters = () => {
    filters.value = {
        userType: "all",
        status: "all",
        search: "",
    };
    showFilterDropdown.value = false;
};

// Modal functions (unchanged)
const openModal = (report) => {
    selectedReport.value = report;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

// NEW: SweetAlert Status Modal
const openStatusModal = async (report) => {
    const { value: status } = await Swal.fire({
        title: "Change Report Status",
        text: `Current status: ${formatStatus(report.status)}`,
        icon: "question",
        input: "select",
        inputOptions: {
            pending: "Pending",
            in_progress: "In Progress",
            resolved: "Resolved",
        },
        inputValue: report.status,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Update Status",
        cancelButtonText: "Cancel",
        inputValidator: (value) => {
            if (!value) {
                return "You need to select a status!";
            }
            if (value === report.status) {
                return "This is already the current status!";
            }
        },
    });

    if (status && status !== report.status) {
        updateStatus(report, status);
    }
};

// Status update function
const updateStatus = async (report, newStatus) => {
    updatingStatus.value = true;
    try {
        await router.post(
            route("admin.reports.updateStatus", report.id),
            {
                status: newStatus,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: `Status updated to "${formatStatus(newStatus)}"`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
                onError: (error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Failed to update status. Please try again.",
                    });
                },
            }
        );
    } catch (error) {
        console.error("Update error:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "An unexpected error occurred. Please try again.",
        });
    } finally {
        updatingStatus.value = false;
    }
};

// Helper functions (unchanged)
const userTypeClasses = (userId) => ({
    "px-2 py-1 rounded-full text-xs font-medium": true,
    "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200":
        userId,
    "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200": !userId,
});

const priorityClasses = (priority) => ({
    "px-2 py-1 rounded-full text-xs font-medium": true,
    "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200":
        priority === "high",
    "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200":
        priority === "medium",
    "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200":
        priority === "low",
    "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200": !priority,
});

const statusClasses = (status) => ({
    "px-2 py-1 rounded-full text-xs font-medium": true,
    "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200":
        status === "pending",
    "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200":
        status === "in_progress",
    "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200":
        status === "resolved",
});

const getStatusRowClass = (status) => {
    return status === "pending"
        ? "bg-white dark:bg-gray-900"
        : status === "in_progress"
        ? "bg-blue-50 dark:bg-blue-900/20"
        : "bg-green-50 dark:bg-green-900/20";
};

const getStatusIcon = (status) => {
    switch (status) {
        case "pending":
            return "hi-clock";
        case "in_progress":
            return "bi-arrow-repeat";
        case "resolved":
            return "hi-check-circle";
        default:
            return "hi-question-mark-circle";
    }
};

const getPriorityIcon = (priority) => {
    switch (priority) {
        case "high":
            return "hi-exclamation";
        case "medium":
            return "hi-exclamation-circle";
        case "low":
            return "hi-information-circle";
        default:
            return "hi-question-mark-circle";
    }
};

const formatStatus = (status) => {
    const statusMap = {
        pending: "Pending",
        in_progress: "In Progress",
        resolved: "Resolved",
    };
    return statusMap[status] || status;
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "short", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}

tr {
    transition: opacity 0.2s ease;
}

/* Disabled state for buttons */
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
