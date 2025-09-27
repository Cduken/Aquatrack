<template>
    <AdminLayout title="Announcements">
        <div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <v-icon name="hi-solid-speakerphone" class="w-6 h-6 text-blue-600" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
                            <p class="text-sm text-gray-500">Manage and create announcements for your platform</p>
                        </div>
                    </div>
                    <button @click="openCreateModal"
                        class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                        <v-icon name="bi-plus-circle" class="w-5 h-5" />
                        Create Announcement
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div v-if="announcements.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <div class="mx-auto w-16 h-16 text-gray-300 mb-4">
                        <v-icon name="hi-solid-speakerphone" class="w-full h-full" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">No announcements yet</h3>
                    <p class="mt-1 text-sm text-gray-500 mb-6">Get started by creating your first announcement.</p>
                    <button @click="openCreateModal"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm">
                        <v-icon name="bi-plus-circle" class="w-4 h-4" />
                        Create Announcement
                    </button>
                </div>

                <div v-else>
                    <!-- Filters and search -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <v-icon name="hi-solid-search" class="w-5 h-5 text-gray-400" />
                            </div>
                            <input type="text" v-model="searchQuery" placeholder="Search announcements..."
                                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <select v-model="statusFilter" class="pl-3 pr-10 py-2.5 appearance-none border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <v-icon name="hi-chevron-down" class="w-5 h-5 text-gray-400" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div v-for="announcement in filteredAnnouncements" :key="announcement.id"
                            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-4">
                                    <span :class="statusClasses(announcement.status)" class="text-xs">
                                        <v-icon :name="announcement.status === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'" class="w-3 h-3 mr-1" />
                                        {{ announcement.status }}
                                    </span>
                                    <div class="text-xs text-gray-500">{{ formatDate(announcement.date) }}</div>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-1">{{ announcement.title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ announcement.content }}</p>

                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">ID: {{ announcement.id }}</span>
                                    <div class="flex space-x-2">
                                        <button @click="editAnnouncement(announcement)"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Edit">
                                            <v-icon name="ri-edit-box-line" class="w-4 h-4" />
                                        </button>
                                        <button @click="deleteAnnouncement(announcement.id)"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete">
                                            <v-icon name="fa-regular-trash-alt" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    announcements: Array,
    errors: Object
});

const showModal = ref(false);
const editing = ref(false);
const currentId = ref(null);
const searchQuery = ref('');
const statusFilter = ref('all');

const form = useForm({
    title: '',
    content: '',
    status: 'active',
    start_date: null,
    end_date: null,
});

// Computed property for filtered announcements
const filteredAnnouncements = computed(() => {
    return props.announcements.filter(announcement => {
        const matchesSearch = announcement.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            announcement.content.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = statusFilter.value === 'all' || announcement.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

// Helper functions
const statusClasses = (status) => {
    return {
        'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium': true,
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
        text: "This announcement will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
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

<style scoped>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

/* Smooth transitions for all interactive elements */
button, select, input {
    transition: all 0.2s ease;
}
</style>
