<template>
    <AdminLayout title="Staff Members">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Staff Members</h1>
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Add New Staff
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        Export
                    </button>
                </div>
            </div>
        </template>

        <div class="staff-container">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="staff in staffMembers" :key="staff.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ staff.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ staff.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ staff.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ staff.role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${staff.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
                                    {{ staff.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                <button class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const staffMembers = [
    {
        id: 1,
        name: 'Admin User',
        email: 'admin@aquatrack.com',
        role: 'Administrator',
        status: 'Active'
    },
    {
        id: 2,
        name: 'Support Staff',
        email: 'support@aquatrack.com',
        role: 'Support',
        status: 'Active'
    }
];
</script>

<style scoped>
.staff-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}
</style>
