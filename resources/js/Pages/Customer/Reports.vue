<template>
    <CustomerLayout title="Customer Reports">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">My Reports</h1>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>


                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in reports" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ report.id
                                    }}</td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.description }}</td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(report.status)">
                                        {{ report.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <v-icon name="bi-eye-fill" class="w-6 h-6" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { ref } from 'vue';

const reports = ref([
    {
        id: 1,
        type: 'Billing Issue',
        description: 'Issue with the last bill received',
        status: 'In Progress',
        date: '2023-05-01'
    },
    {
        id: 2,
        type: 'Leakage',
        description: 'Leak detected in the main line',
        status: 'Resolved',
        date: '2023-05-03'
    },

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
