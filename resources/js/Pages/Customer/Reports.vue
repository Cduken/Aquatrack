<template>
    <CustomerLayout title="Customer Reports">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">My Reports</h1>
                    <button @click="showAddModal = true"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <v-icon name="bi-plus-lg" class="mr-2" />
                        Add Report
                    </button>
                </div>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking
                                    Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in reports.data" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ report.id
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ report.tracking_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ report.barangay }}, {{ report.municipality }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="line-clamp-2">{{ report.description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(report.status)">
                                        {{ formatStatus(report.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(report.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <button class="text-blue-600 hover:text-blue-800" title="View"
                                            @click="openReportDetails(report)">
                                            <v-icon name="bi-eye-fill" class="w-6 h-6" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div v-if="reports.data.length === 0" class="text-center py-12">
                        <v-icon name="bi-file-earmark-excel" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            You haven't submitted any reports yet. Click "Add Report" to get started.
                        </p>
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
                                    Showing <span class="font-medium">{{ reports.meta.from }}</span> to
                                    <span class="font-medium">{{ reports.meta.to }}</span> of
                                    <span class="font-medium">{{ reports.meta.total }}</span> results
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

        <!-- Add Report Modal -->
        <AddReportModal :show="showAddModal" @close="showAddModal = false" @success="handleReportAdded" />

        <!-- Report Details Modal -->
        <ReportDetailsModal :show="showDetailsModal" :report="selectedReport" @close="showDetailsModal = false" />
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import AddReportModal from '@/Components/Modals/AddReportModal.vue';
import ReportDetailsModal from '@/Components/Modals/ReportDetailsModal.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const page = usePage();

const props = defineProps({
    reports: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            meta: {},
            links: {}
        })
    },
    flash: Object // Add flash as a prop
});

// Watch for flash messages
watch(() => page.props.flash, (newFlash) => {
    if (newFlash?.success) {
        toast.success(newFlash.success);
        if (newFlash.trackingCode) {
            toast.info(`Your tracking code: ${newFlash.trackingCode}`);
        }
    }
}, { immediate: true });

const showAddModal = ref(false);
const showDetailsModal = ref(false);
const selectedReport = ref(null);

const openReportDetails = (report) => {
    selectedReport.value = report;
    showDetailsModal.value = true;
};

const handleReportAdded = () => {
    showAddModal.value = false;
};

const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-blue-100 text-blue-800': status === 'in_progress',
        'bg-green-100 text-green-800': status === 'resolved',
        'bg-gray-100 text-gray-800': status === 'pending'
    };
};

const formatStatus = (status) => {
    const statusMap = {
        'pending': 'Pending',
        'in_progress': 'In Progress',
        'resolved': 'Resolved'
    };
    return statusMap[status] || status;
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

table {
    width: 100%;
    table-layout: auto;
}

.w-full {
    width: 100%;
}

.p-6 {
    padding: 1.5rem;
}
</style>
