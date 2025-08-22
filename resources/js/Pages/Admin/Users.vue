<template>
    <AdminLayout title="Users">
        <div class="max-w-screen-xl mx-auto w-full">
            <!-- Header Section -->
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg mb-6">
                <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input v-model="filters.search" type="text" id="simple-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Name, email, phone..." @keyup.enter="fetchUsers" />
                            </div>
                        </form>
                    </div>
                    <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                        <button @click="showCreateModal = true" type="button" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Register New User
                        </button>
                        <div class="flex items-center w-full space-x-3 md:w-auto">
                            <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-4 h-4 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Filter
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Role</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center">
                                        <input id="role-all" type="checkbox" value="" :checked="!filters.role" @change="filters.role = ''; fetchUsers()" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="role-all" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">All Roles</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="role-customer" type="checkbox" value="customer" :checked="filters.role === 'customer'" @change="filters.role = 'customer'; fetchUsers()" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="role-customer" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Customer</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="role-staff" type="checkbox" value="staff" :checked="filters.role === 'staff'" @change="filters.role = 'staff'; fetchUsers()" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="role-staff" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Staff</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="role-admin" type="checkbox" value="admin" :checked="filters.role === 'admin'" @change="filters.role = 'admin'; fetchUsers()" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="role-admin" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Admin</label>
                                    </li>
                                </ul>
                            </div>
                            <button @click="resetFilters" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="relative overflow-x-auto mb-4 shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 cursor-pointer" @click="sortBy('id')">
                                ID
                                <v-icon v-if="filters.sort === 'id'" :name="filters.order === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down'" class="w-3 h-3 ml-1" />
                            </th>
                            <th scope="col" class="px-6 py-3">Avatar</th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" @click="sortBy('name')">
                                Name
                                <v-icon v-if="filters.sort === 'name'" :name="filters.order === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down'" class="w-3 h-3 ml-1" />
                            </th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Phone</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.id }}
                            </th>
                            <td class="px-6 py-4">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img v-if="user.avatar_url" :src="user.avatar_url" :alt="user.name" class="h-10 w-10 rounded-full object-cover">
                                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">{{ user.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ user.name }}</td>
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <td class="px-6 py-4">{{ user.phone || ' - ' }}</td>
                            <td class="px-6 py-4">
                                <span :class="roleClasses(user.role)">
                                    {{ user.role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-4">
                                    <button @click="viewUser(user)" class="text-blue-600 hover:text-blue-800" title="View">
                                        <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                    </button>
                                    <button @click="editUser(user)" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                        <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                    </button>
                                    <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-800" title="Delete">
                                        <v-icon name="fa-trash" class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="users.data.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No users found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :items="users" item-name="users" previous-text="Previous" next-text="Next" />
        </div>

        <CreateUserModal :show="showCreateModal" :zones="zones" @close="showCreateModal = false" @submit="submitCreateUser" />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { pickBy } from 'lodash';
import Swal from 'sweetalert2';
import CreateUserModal from '@/Components/Admin/Modals/CreateUserModal.vue';

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

const showCreateModal = ref(false);

const zones = {
    "Zone 1": ["Poblacion Sur"],
    "Zone 2": ["Poblacion Centro"],
    "Zone 3": ["Poblacion Centro"],
    "Zone 4": ["Poblacion Norte"],
    "Zone 5": ["Candajec", "Buangan"],
    "Zone 6": ["Bonbon"],
    "Zone 7": ["Bonbon"],
    "Zone 8": ["Nahawan"],
    "Zone 9": ["Caboy", "Villaflor", "Cantuyoc"],
    "Zone 10": ["Bacani", "Mataub", "Comaang", "Tangaran"],
    "Zone 11": ["Cantuyoc", "Nahawan"],
    "Zone 12": ["Lajog", "Buacao"],
};

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

const fetchUsers = () => {
    router.get('/admin/users', pickBy(filters.value), {
        preserveState: true,
        replace: true,
    });
};

const viewUser = (user) => {
    console.log('View user:', user);
};

const editUser = (user) => {
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

const submitCreateUser = (userData) => {
    router.post(route('admin.users.store'), userData, {
        preserveScroll: true,
        onSuccess: (response) => {
            showCreateModal.value = false;

            // Get the generated password from the response
            const generatedPassword = response.props.flash?.generated_password;

            if (generatedPassword) {
                Swal.fire({
                    icon: 'success',
                    title: 'User created successfully!',
                    html: `
                        <div class="text-left flex items-center flex-col justify-center">
                            <p class="mb-2 font-medium">Generated Password:</p>
                            <input
                                id="swal-password"
                                class="swal2-input text-center font-mono text-lg"
                                value="${generatedPassword}"
                                readonly
                                style="width: 70%; padding: 0.5rem;"
                            />
                            <p class="mt-2 text-sm text-gray-500">Please provide this password to the user</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Copy Password',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    focusConfirm: false,
                    customClass: {
                        popup: '!text-left',
                        confirmButton: '!bg-blue-600 !hover:bg-blue-700',
                    },
                    didOpen: () => {
                        const confirmBtn = Swal.getConfirmButton();
                        if (confirmBtn) {
                            confirmBtn.addEventListener('click', () => {
                                navigator.clipboard.writeText(generatedPassword);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Copied!',
                                    text: 'Password copied to clipboard',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'User created',
                    text: 'User was created successfully but password could not be displayed',
                    toast: true,
                    position: 'top-end',
                    timer: 3000
                });
            }
        },
        onError: (errors) => {
            let errorMessage = 'Failed to create user. Please check the form and try again.';

            if (errors.email) {
                errorMessage = errors.email;
            } else if (errors.name) {
                errorMessage = errors.name;
            } else if (errors.lastname) {
                errorMessage = errors.lastname;
            }

            Swal.fire({
                icon: 'error',
                title: 'Creation Failed',
                text: errorMessage,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: '!bg-red-600 !hover:bg-red-700',
                }
            });
        }
    });
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
.cursor-pointer {
    cursor: pointer;
}
</style>
