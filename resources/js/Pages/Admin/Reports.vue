<template>
    <AdminLayout title="Reports">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">Reports</h1>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in reports" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ report.id
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.customer }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="priorityClasses(report.priority)">
                                        {{ report.priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(report.status)">
                                        {{ report.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-4">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                        </button>
                                        <button class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const reports = ref([
    {
        id: 1234,
        customer: 'John Doe',
        type: 'Billing Issue',
        priority: 'Medium',
        status: 'In Progress',
        date: '2023-05-01'
    },
    {
        id: 1235,
        customer: 'Jane Smith',
        type: 'Leakage',
        priority: 'High',
        status: 'Pending',
        date: '2023-05-03'
    },
    {
        id: 1236,
        customer: 'Robert Johnson',
        type: 'Quality Concern',
        priority: 'Low',
        status: 'Resolved',
        date: '2023-05-05'
    }
]);

const priorityClasses = (priority) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-yellow-100 text-yellow-800': priority === 'Medium',
        'bg-red-100 text-red-800': priority === 'High',
        'bg-green-100 text-green-800': priority === 'Low'
    };
};

const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-blue-100 text-blue-800': status === 'In Progress',
        'bg-gray-100 text-gray-800': status === 'Pending',
        'bg-green-100 text-green-800': status === 'Resolved'
    };
};
</script>

<style scoped>
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
