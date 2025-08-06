<template>
    <AdminLayout title="Users">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Users</h1>
                    <div class="flex space-x-4">
                        <button @click="showCreateModal = true"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                            <v-icon name="bi-plus" class="w-5 h-5" /> Add New User
                        </button>
                        <button
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center gap-2">
                            <v-icon name="bi-download" class="w-4 h-4" /> Export
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input v-model="filters.search" type="text" placeholder="Name, email, phone..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                @keyup.enter="fetchUsers">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select v-model="filters.role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                @change="fetchUsers">
                                <option value="">All Roles</option>
                                <option value="customer">Customer</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select v-model="filters.sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                @change="fetchUsers">
                                <option value="id">ID</option>
                                <option value="name">Name</option>
                                <option value="created_at">Registration Date</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                            <select v-model="filters.order"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                @change="fetchUsers">
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="resetFilters"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition mr-2">
                            Reset
                        </button>
                        <button @click="fetchUsers"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                            <v-icon name="bi-search" class="w-4 h-4" /> Search
                        </button>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="mt-6">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                                        @click="sortBy('id')">
                                        ID <v-icon v-if="filters.sort === 'id'"
                                            :name="filters.order === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down'"
                                            class="w-3 h-3 ml-1" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                                        @click="sortBy('name')">
                                        Name <v-icon v-if="filters.sort === 'name'"
                                            :name="filters.order === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down'"
                                            class="w-3 h-3 ml-1" />
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.id
                                        }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.phone || ' - ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span :class="roleClasses(user.role)">
                                            {{ user.role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-4">
                                            <button @click="viewUser(user)" class="text-blue-600 hover:text-blue-800"
                                                title="View">
                                                <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                            </button>
                                            <button @click="editUser(user)"
                                                class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                                <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                            </button>
                                            <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-800"
                                                title="Delete">
                                                <v-icon name="fa-trash" class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No users found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ users.from }}</span> to <span class="font-medium">{{
                                users.to }}</span> of <span class="font-medium">{{ users.total }}</span> users
                        </div>
                        <div class="flex space-x-2">
                            <button @click="prevPage" :disabled="!users.prev_page_url"
                                :class="{ 'opacity-50 cursor-not-allowed': !users.prev_page_url }"
                                class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </button>
                            <button @click="nextPage" :disabled="!users.next_page_url"
                                :class="{ 'opacity-50 cursor-not-allowed': !users.next_page_url }"
                                class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create User Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Create New User</h2>
                <form @submit.prevent="createUser">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input v-model="newUser.name" type="text" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="newUser.email" type="email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input v-model="newUser.phone" type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { pickBy } from 'lodash';
import Swal from 'sweetalert2';

const props = defineProps({
    users: Object,
    filters: Object,
});

const filters = ref({
    search: props.filters.search || '',
    role: props.filters.role || '',
    sort: props.filters.sort || 'id',
    order: props.filters.order || 'desc',
    per_page: props.filters.per_page || 10,
});

const newUser = ref({
    name: '',
    email: '',
    phone: '',
    status: 'active',
});

const showCreateModal = ref(false);

// Watch filters and reload when they change
watch(filters, (value) => {
    router.get('/admin/users', pickBy(value), {
        preserveState: true,
        replace: true,
    });
}, { deep: true });

const sortBy = (column) => {
    if (filters.value.sort === column) {
        filters.value.order = filters.value.order === 'asc' ? 'desc' : 'asc';
    } else {
        filters.value.sort = column;
        filters.value.order = 'asc';
    }
};

const resetFilters = () => {
    filters.value = {
        search: '',
        role: '',
        sort: 'id',
        order: 'desc',
        per_page: 10,
    };
};

const prevPage = () => {
    if (props.users.prev_page_url) {
        router.visit(props.users.prev_page_url, {
            preserveState: true,
        });
    }
};

const nextPage = () => {
    if (props.users.next_page_url) {
        router.visit(props.users.next_page_url, {
            preserveState: true,
        });
    }
};

const viewUser = (user) => {
    // Implement view user functionality
    console.log('View user:', user);
};

const editUser = (user) => {
    // Implement edit user functionality
    console.log('Edit user:', user);
};

const confirmDelete = (user) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/users/${user.id}`, {
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    );
                },
                onError: () => {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
};

const createUser = () => {
    // We'll leave this empty for now as per your request
    showCreateModal.value = false;
};

const roleClasses = (role) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-blue-100 text-blue-800': role === 'admin',
        'bg-green-100 text-green-800': role === 'customer',
        'bg-purple-100 text-purple-800': role === 'staff',
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

.cursor-pointer {
    cursor: pointer;
}
</style>
