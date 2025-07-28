<template>
    <AdminLayout title="Records">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">Customer Records</h1>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account No.
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Reading
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consumption
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Due
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in reports" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ report.id
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.customer }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.account_number
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.last_reading }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.consumption }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ report.amount_due }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(report.status)">
                                        {{ report.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-4">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                        </button>
                                        <button class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" title="Delete">
                                            <v-icon name="fa-trash" class="w-5 h-5" />
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
        account_number: 'ACCT-1001',
        last_reading: '120 m3',
        consumption: '15 m3',
        amount_due: '120.50',
        date: '2023-05-01',
        status: 'Paid',
    },
    {
        id: 1235,
        customer: 'Jane Smith',
        account_number: 'ACCT-1002',
        last_reading: '85 m3',
        consumption: '10 m3',
        amount_due: '95.75',
        date: '2023-05-03',
        status: 'Pending',
    },
    {
        id: 1236,
        customer: 'Robert Johnson',
        account_number: 'ACCT-1003',
        last_reading: '210 m3',
        consumption: '25 m3',
        amount_due: '150.20',
        date: '2023-05-05',
        status: 'Overdue',
    }
]);

const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-green-100 text-green-800': status === 'Paid',
        'bg-yellow-100 text-yellow-800': status === 'Pending',
        'bg-red-100 text-red-800': status === 'Overdue'
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
