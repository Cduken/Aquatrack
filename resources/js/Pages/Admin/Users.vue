<template>
    <AdminLayout title="Users">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Users</h1>
                    <div class="flex space-x-4">
                        <button
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                            <v-icon name="bi-plus" class="w-5h-5" /> Add New User
                        </button>
                        <button
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center gap-2">
                            <v-icon name="bi-download" class="w-4 h-4" /> Export
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                </th>


                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in reports" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ report.id
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.email
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ report.phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> <span
                                        :class="statusClasses(report.status)">
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
        id: 1,
        name: 'John Doe',
        email: 'johndoe69@email.com',
        phone: '123-456-7890',
        consumption: '15 m3',
        amount_due: '120.50',
        date: '2023-05-01',
        status: 'Active',
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'janesmith69@email.com',
        phone: '123-456-7890',
        consumption: '10 m3',
        amount_due: '95.75',
        date: '2023-05-03',
        status: 'Inactive',
    },

]);

const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-green-100 text-green-800': status === 'Active',
        'bg-red-100 text-red-800': status === 'Inactive'
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
