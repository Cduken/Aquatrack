<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Header/Navigation.vue';
import ReportDetailsModal from '@/Components/Modals/ReportDetailsModal.vue';
import { ref } from 'vue';

defineProps({
    reports: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            meta: {},
            links: {}
        })
    }
});

const selectedReport = ref(null);
const showModal = ref(false);

const openModal = (report) => {
    selectedReport.value = report;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <Navbar>

        <Head title="Reports" />

            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Water Quality Reports</h1>
                            <p class="text-gray-600 mt-2">Track and monitor water quality issues in your area</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-blue-600">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-1/6">
                                            Location Details
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-1/6">
                                            Report Summary
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-1/6">
                                            Date Reported
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider w-1/6">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr v-for="report in reports.data" :key="report.id"
                                        class="hover:bg-blue-50 transition-colors">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <v-icon name="bi-geo-alt" class="text-blue-600" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ report.barangay }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ report.purok }} • {{ report.municipality }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="text-sm text-gray-900 max-w-xs overflow-hidden">
                                                <div class="line-clamp-2 break-words">
                                                    {{ report.description }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-medium">
                                                {{ new Date(report.created_at).toLocaleDateString('en-US', {
                                                    month: 'short',
                                                    day: 'numeric', year: 'numeric'
                                                }) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ new Date(report.created_at).toLocaleTimeString('en-US', {
                                                    hour:
                                                        '2-digit', minute: '2-digit'
                                                }) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="openModal(report)"
                                                class="text-blue-600 hover:text-blue-800 flex items-center justify-end w-full">
                                                <span>View Details</span>
                                                <v-icon name="bi-chevron-right" class="ml-1" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="reports.meta && reports.meta.last_page > 1"
                            class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link v-if="reports.links && reports.links.prev" :href="reports.links.prev"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                                </Link>
                                <Link v-if="reports.links && reports.links.next" :href="reports.links.next"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                                </Link>
                            </div>
                            <div v-if="reports.meta"
                                class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ reports.meta.from || 0 }}</span> to <span
                                            class="font-medium">{{ reports.meta.to || 0 }}</span> of <span
                                            class="font-medium">{{ reports.meta.total || 0 }}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                        aria-label="Pagination">
                                        <Link v-if="reports.meta.links" v-for="(link, index) in reports.meta.links"
                                            :key="index" :href="link.url" :disabled="!link.url" :class="[
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

                        <!-- Empty state -->
                        <div v-if="!reports.data || reports.data.length === 0" class="text-center py-12">
                            <v-icon name="bi-droplet" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new water quality report.
                            </p>

                        </div>
                    </div>
                </div>
            </div>

    </Navbar>

    <ReportDetailsModal :show="showModal" :report="selectedReport" @close="closeModal" />
</template>
