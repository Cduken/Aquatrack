<template>
    <AdminLayout title="Announcements">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-4">
                    <div class="flex items-center gap-2">
                        <v-icon name="hi-solid-speakerphone" class="w-6 h-6 text-gray-800" />
                        <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="openCreateModal"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                            <v-icon name="bi-plus-circle-fill" class="w-5 h-5" />
                            Create Announcement
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <div v-if="announcements.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                        <div class="mx-auto w-24 h-24 text-gray-400">
                            <v-icon name="hi-solid-speakerphone" class="w-full h-full" />
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No announcements yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new announcement.</p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-hash" class="w-4 h-4" /> ID
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-card-heading" class="w-4 h-4" /> Title
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-card-text" class="w-4 h-4" /> Content
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-calendar-date" class="w-4 h-4" /> Date
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-circle-fill" class="w-4 h-4" /> Status
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <v-icon name="bi-gear-fill" class="w-4 h-4" /> Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="announcement in announcements" :key="announcement.id"
                                    class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ announcement.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ announcement.title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ announcement.content }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(announcement.date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span :class="statusClasses(announcement.status)">
                                            <v-icon
                                                :name="announcement.status === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'"
                                                class="w-4 h-4 mr-1" />
                                            {{ announcement.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-3">
                                            <button @click="editAnnouncement(announcement)"
                                                class="text-blue-600 hover:text-blue-800 flex items-center gap-1"
                                                title="Edit">
                                                <v-icon name="ri-edit-box-fill" class="w-4 h-4" />
                                                <span class="sr-only">Edit</span>
                                            </button>
                                            <button @click="deleteAnnouncement(announcement.id)"
                                                class="text-red-600 hover:text-red-800 flex items-center gap-1"
                                                title="Delete">
                                                <v-icon name="fa-trash" class="w-4 h-4" />
                                                <span class="sr-only">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcement Modal Component -->
        <AnnouncementModal :show="showModal" :editing="editing"  :form="form" :errors="errors" @close="closeModal"
            @submit="editing ? updateAnnouncement() : createAnnouncement()" />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AnnouncementModal from '@/Components/Modals/AnnouncementModal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    announcements: Array,
    errors: Object
});

const showModal = ref(false);
const editing = ref(false);
const currentId = ref(null);

const form = useForm({
    title: '',
    content: '',
    status: 'active',
    start_date: null,
    end_date: null,
});

// Helper functions
const statusClasses = (status) => {
    return {
        'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-green-100 text-green-800': status.toLowerCase() === 'active',
        'bg-red-100 text-red-800': status.toLowerCase() === 'inactive'
    };
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// Modal control functions
const openCreateModal = () => {
    editing.value = false;
    form.reset();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = false;
    form.reset();
    currentId.value = null;
};

// CRUD operations
const editAnnouncement = (announcement) => {
    editing.value = true;
    currentId.value = announcement.id;
    form.title = announcement.title;
    form.content = announcement.content;
    form.status = announcement.status.toLowerCase();
    form.start_date = announcement.start_date;
    form.end_date = announcement.end_date;
    showModal.value = true;
};

const createAnnouncement = () => {
    form.post(route('announcements.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Announcement Created!',
                text: 'Your announcement has been successfully created.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '400px',
                customClass: {
                    title: 'text-lg font-medium'
                }
            });
        },
        onError: () => {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Creation Failed',
                text: 'There was an error creating the announcement.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '400px',
                customClass: {
                    title: 'text-lg font-medium'
                }
            });
        }
    });
};

const updateAnnouncement = () => {
    form.put(route('announcements.update', currentId.value), {
        onSuccess: () => {
            showModal.value = false;
            editing.value = false;
            form.reset();
            currentId.value = null;
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Update Successful!',
                text: 'The announcement has been updated.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '400px',
                customClass: {
                    title: 'text-lg font-medium'
                }
            });
        },
        onError: () => {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Update Failed',
                text: 'There was an error updating the announcement.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '400px',
                customClass: {
                    title: 'text-lg font-medium'
                }
            });
        }
    });
};

const deleteAnnouncement = (id) => {
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
            form.delete(route('announcements.destroy', id), {
                onSuccess: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The announcement has been deleted.',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                        background: '#f8f9fa',
                        backdrop: false,
                        width: '400px',
                        customClass: {
                            title: 'text-lg font-medium'
                        }
                    });
                },
                onError: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Deletion Failed',
                        text: 'There was an error deleting the announcement.',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                        background: '#f8f9fa',
                        backdrop: false,
                        width: '400px',
                        customClass: {
                            title: 'text-lg font-medium'
                        }
                    });
                }
            });
        }
    });
};

// Make sure to expose all methods that are used in the template
defineExpose({
    editAnnouncement,
    deleteAnnouncement
});
</script>
