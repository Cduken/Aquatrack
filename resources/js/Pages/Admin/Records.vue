<template>
    <AdminLayout title="Records">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">Customer Records</h1>

                <!-- Filters and Search -->
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="relative flex-grow max-w-md">
                        <input v-model="filters.search" type="text" placeholder="Search customers..."
                            class="pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full"
                            @keyup.enter="getRecords">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <v-icon name="bi-search" class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <select v-model="filters.status"
                            class="border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Overdue">Overdue</option>
                        </select>

                        <select v-model="filters.perPage"
                            class="border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>

                        <button @click="getRecords"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            <v-icon name="bi-arrow-repeat" class="w-5 h-5 mr-1" />
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="mt-6 text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-600">Loading records...</p>
                </div>

                <!-- Records Table -->
                <div v-else class="mt-6">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('id')">
                                        ID
                                        <SortIndicator :field="'id'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('name')">
                                        Customer
                                        <SortIndicator :field="'name'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Account No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('reading')">
                                        Last Reading
                                        <SortIndicator :field="'reading'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('consumption')">
                                        Consumption
                                        <SortIndicator :field="'consumption'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('amount')">
                                        Amount
                                        <SortIndicator :field="'amount'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('reading_date')">
                                        Reading Date
                                        <SortIndicator :field="'reading_date'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        @click="sort('status')">
                                        Status
                                        <SortIndicator :field="'status'" :current-field="sortField"
                                            :direction="sortDirection" />
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="record in records.data" :key="record.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{
                                        record.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ record.user.name }} {{ record.user.lastname }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ record.user.account_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.reading }}
                                        m³</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.consumption
                                    }} m³</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ record.amount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                        formatDate(record.reading_date) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span :class="statusClasses(record.status)">
                                            {{ record.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-2">
                                            <Link :href="route('admin.records.show', record.id)"
                                                class="text-blue-600 hover:text-blue-800" title="View">
                                            <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                            </Link>
                                            <Link :href="route('admin.records.edit', record.id)"
                                                class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                            </Link>
                                            <button @click="confirmDelete(record)"
                                                class="text-red-600 hover:text-red-800" title="Delete">
                                                <v-icon name="fa-trash" class="w-5 h-5" />
                                            </button>
                                            <button @click="updateStatus(record)"
                                                class="text-green-600 hover:text-green-700" title="Update Status">
                                                <v-icon name="bi-arrow-repeat" class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <Pagination v-if="records.data && records.data.length > 0" :items="records" item-name="records"
                        class="mt-6" />

                    <!-- Empty State -->
                    <div v-if="records.data.length === 0" class="mt-6 text-center py-8 bg-gray-50 rounded-lg">
                        <v-icon name="bi-inbox" class="w-12 h-12 text-gray-400 mx-auto" />
                        <p class="mt-2 text-gray-600">No records found</p>
                        <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import SortIndicator from '@/Components/SortIndicator.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import Swal from 'sweetalert2';

// Props
const props = defineProps({
    records: Object,
    filters: Object,
    sortField: String,
    sortDirection: String,
});

// Reactive data
const loading = ref(false);

// Local filters
const filters = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    perPage: props.filters.perPage || 10,
});

// Local sorting
const sortField = ref(props.sortField || 'id');
const sortDirection = ref(props.sortDirection || 'desc');

// Watch for filter changes and debounce the search
let searchTimeout = null;
watch(() => filters.value, (newFilters) => {
    // Clear previous timeout
    if (searchTimeout) clearTimeout(searchTimeout);

    // Set new timeout for search input
    if (newFilters.search !== undefined) {
        searchTimeout = setTimeout(() => {
            getRecords();
        }, 500);
    } else {
        getRecords();
    }
}, { deep: true });

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';

    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// Status badge classes
const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-green-100 text-green-800': status === 'Paid',
        'bg-yellow-100 text-yellow-800': status === 'Pending',
        'bg-red-100 text-red-800': status === 'Overdue'
    };
};

// Sort functionality
const sort = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }

    getRecords();
};

// Get records with current filters and sorting
const getRecords = () => {
    router.get(route('admin.records.index'), {
        search: filters.value.search,
        status: filters.value.status,
        perPage: filters.value.perPage,
        sort: sortField.value,
        direction: sortDirection.value,
    }, {
        preserveState: true,
        replace: true,
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
    });
};

// Update status functionality
const updateStatus = (record) => {
    Swal.fire({
        title: 'Update Status',
        input: 'select',
        inputOptions: {
            Paid: 'Paid',
            Pending: 'Pending',
            Overdue: 'Overdue',
        },
        inputValue: record.status || 'Pending',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showLoaderOnConfirm: true,
        preConfirm: (status) => {
            return router.put(route('admin.records.update', record.id), { status }, {
                preserveScroll: true,
                onError: (errors) => {
                    Swal.showValidationMessage(
                        errors.message || 'Failed to update status. Please try again.'
                    );
                },
            });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Updated!', 'The status has been updated.', 'success');
        }
    });
};

// Delete record functionality with SweetAlert
const confirmDelete = (record) => {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the record for ${record.user.name} ${record.user.lastname}. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return router.delete(route('admin.records.destroy', record.id), {
                preserveScroll: true,
                onError: (errors) => {
                    Swal.showValidationMessage(
                        errors.message || 'Failed to delete record. Please try again.'
                    );
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'The record has been deleted successfully.',
                'success'
            );
        }
    });
};
</script>
