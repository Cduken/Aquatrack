<template>
    <AdminLayout title="Reports">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">Reports</h1>

                <!-- Filter and Search Section -->
                <div class="mt-4 mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <!-- User Type Filter -->
                        <div class="w-full sm:w-48">
                            <label for="user-type" class="block text-sm font-medium text-gray-700 mb-1">User
                                Type</label>
                            <select id="user-type" v-model="filters.userType"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="all">All Users</option>
                                <option value="guest">Guest Reports</option>
                                <option value="authenticated">Authenticated Users</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full sm:w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" v-model="filters.status"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full sm:w-64">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <v-icon name="bi-search" class="text-gray-400" />
                            </div>
                            <input type="text" id="search" v-model="filters.search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search by ID or name..." />
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reporter
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in filteredReports" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ report.id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ report.reporter_name || report.user?.name }}
                                    <span v-if="report.user_id" class="text-xs text-gray-400 block">
                                        User ID: {{ report.user_id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="userTypeClasses(report.user_id)">
                                        {{ report.user_id ? 'Registered' : 'Guest' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ report.barangay }}, {{ report.municipality }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="priorityClasses(report.priority)">
                                        {{ report.priority || 'N/A' }}
                                    </span>
                                </td>

                                <!-- STATUS CELL -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 relative">
                                    <transition enter-active-class="transition duration-150 ease-out"
                                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                                        leave-active-class="transition duration-100 ease-in"
                                        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95"
                                        mode="out-in">
                                        <template v-if="editingRowId === report.id" :key="'edit-'+report.id">
                                            <select v-model="report.status"
                                                @change="updateStatus(report); closeEditDropdown()"
                                                class=" rounded-md text-sm"
                                                :class="{
                                                    'bg-blue-100 text-blue-800': report.status === 'in_progress',
                                                    'bg-gray-100 text-gray-800': report.status === 'pending',
                                                    'bg-green-100 text-green-800': report.status === 'resolved'
                                                }">
                                                <option value="pending">Pending</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="resolved">Resolved</option>
                                            </select>
                                        </template>

                                        <template v-else :key="'view-'+report.id">
                                            <span :class="statusClasses(report.status)">
                                                {{ formatStatus(report.status) }}
                                            </span>
                                        </template>
                                    </transition>
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(report.created_at) }}
                                </td>

                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-4">
                                        <button class="text-blue-600 hover:text-blue-800" title="View"
                                            @click="openModal(report)">
                                            <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                        </button>
                                        <button class="text-yellow-500 hover:text-yellow-700" title="Edit"
                                            @click="toggleEditDropdown(report.id)">
                                            <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div v-if="filteredReports.length === 0" class="text-center py-12">
                        <v-icon name="bi-exclamation-circle" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>

                    <!-- Pagination -->
                    <div v-if="reports.meta && reports.meta.last_page > 1"
                        class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link v-if="reports.links.prev" :href="reports.links.prev"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                            </Link>
                            <Link v-if="reports.links.next" :href="reports.links.next"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ reports.meta.from || 0 }}</span> to
                                    <span class="font-medium">{{ reports.meta.to || 0 }}</span> of
                                    <span class="font-medium">{{ reports.meta.total || 0 }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    <Link v-for="(link, index) in reports.meta.links" :key="index" :href="link.url"
                                        :disabled="!link.url" :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            index === 0 ? 'rounded-l-md' : '',
                                            index === reports.meta.links.length - 1 ? 'rounded-r-md' : ''
                                        ]" v-html="link.label" />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Details Modal -->
        <ReportDetailsModal :show="showModal" :report="selectedReport" @close="closeModal" />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ReportDetailsModal from '@/Components/Modals/ReportDetailsModal.vue';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    reports: Object,
    filters: Object,
    canEdit: Boolean
});

const filters = ref({
    userType: props.filters?.userType || 'all',
    status: props.filters?.status || 'all',
    search: props.filters?.search || ''
});

// State
const selectedReport = ref(null);
const showModal = ref(false);
const updatingStatus = ref(false);
const editingRowId = ref(null);

// Open / Close Modals
const openModal = (report) => { selectedReport.value = report; showModal.value = true; };
const closeModal = () => { showModal.value = false; };

// Edit dropdown handling
const toggleEditDropdown = (id) => {
    editingRowId.value = editingRowId.value === id ? null : id;
};
const closeEditDropdown = () => { editingRowId.value = null; };

document.addEventListener("click", (e) => {
    if (!e.target.closest("td")) editingRowId.value = null;
});

const updateStatus = async (report) => {
    updatingStatus.value = true;
    try {
        await router.post(route('admin.reports.updateStatus', report.id), {
            status: report.status
        }, { preserveScroll: true });
    } finally {
        updatingStatus.value = false;
    }
};

// Filtering logic
const filteredReports = computed(() => {
    return props.reports.data.filter(report => {
        if (filters.value.userType === 'guest' && report.user_id) return false;
        if (filters.value.userType === 'authenticated' && !report.user_id) return false;
        if (filters.value.status !== 'all' && report.status !== filters.value.status) return false;

        if (filters.value.search) {
            const searchTerm = filters.value.search.toLowerCase();
            const matchesId = report.id.toString().includes(searchTerm);
            const matchesName = (report.reporter_name || report.user?.name || '').toLowerCase().includes(searchTerm);
            if (!matchesId && !matchesName) return false;
        }
        return true;
    });
});

// Watch filters
watch(filters, debounce(() => {
    router.get(route('admin.reports'), filters.value, { preserveState: true, replace: true });
}, 300), { deep: true });

// Helpers
const userTypeClasses = (userId) => ({
    'px-2 py-1 rounded-full text-xs font-semibold': true,
    'bg-purple-100 text-purple-800': userId,
    'bg-gray-100 text-gray-800': !userId
});

const priorityClasses = (priority) => ({
    'px-2 py-1 rounded-full text-xs font-semibold': true,
    'bg-yellow-100 text-yellow-800': priority === 'medium',
    'bg-red-100 text-red-800': priority === 'high',
    'bg-green-100 text-green-800': priority === 'low',
    'bg-gray-100 text-gray-800': !priority
});

const statusClasses = (status) => ({
    'px-2 py-1 rounded-full text-xs font-semibold': true,
    'bg-blue-100 text-blue-800': status === 'in_progress',
    'bg-gray-100 text-gray-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'resolved'
});

const formatStatus = (status) => ({
    'pending': 'Pending',
    'in_progress': 'In Progress',
    'resolved': 'Resolved'
}[status] || status);

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};
</script>
