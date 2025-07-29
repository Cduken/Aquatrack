<template>
    <AdminLayout title="Announcements">
        <div class="w-full bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
                    <div class="flex space-x-4">
                        <button @click="showModal = true"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                            <v-icon name="bi-plus" class="w-5 h-5" /> Create Announcement
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Content</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="announcement in announcements" :key="announcement.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                    announcement.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ announcement.title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ announcement.content }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ announcement.date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(announcement.status)">
                                        {{ announcement.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-4">
                                        <button @click="editAnnouncement(announcement)"
                                            class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                                        </button>
                                        <button @click="deleteAnnouncement(announcement.id)"
                                            class="text-red-600 hover:text-red-800" title="Delete">
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

        <!-- Create/Edit Announcement Modal -->
        <Modal :show="showModal" @close="showModal = false">
            <template #title>
                <h3 class="text-lg font-medium text-white">{{ editing ? 'Edit' : 'Create' }} Announcement</h3>
            </template>

            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title*</label>
                    <input type="text" id="title" v-model="form.title"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content*</label>
                    <textarea id="content" v-model="form.content" rows="4"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="start_date" v-model="form.start_date"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="end_date" v-model="form.end_date" :min="form.start_date"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
                    <select id="status" v-model="form.status"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <template #footer>
                <button type="button" @click="showModal = false"
                    class="mr-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="button" @click="editing ? updateAnnouncement() : createAnnouncement()"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ editing ? 'Update' : 'Save' }}
                </button>
            </template>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const statusClasses = (status) => {
    return {
        'px-3 py-1 rounded-full text-xs font-semibold': true,
        'bg-green-100 text-green-800': status.toLowerCase() === 'active',
        'bg-red-100 text-red-800': status.toLowerCase() === 'inactive'
    };
};

const createAnnouncement = () => {
    form.post(route('announcements.store')), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        }
    };
};

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

const updateAnnouncement = () => {
    form.put(route('announcements.update', currentId.value), {
        onSuccess: () => {
            showModal.value = false;
            editing.value = false;
            form.reset();
            currentId.value = null;
        }
    });
};

const deleteAnnouncement = (id) => {
    if (confirm('Are you sure you want to delete this announcement?')) {
        form.delete(route('announcements.destroy', id));
    }
};
</script>
