<script setup>
import { useForm } from '@inertiajs/vue3';
import AquatrackLogo from '../AquatrackLogo.vue';
import Modal from '@/Components/Modal.vue';
const emit = defineEmits(['close']);

defineProps({
    show: Boolean,
});

const form = useForm({
    municipality: "Clarin",
    province: "Bohol",
    barangay: "",
    purok: "",
    description: "",
    photos: [],
    photo_previews: []
});

const barangays = [
    "Bacani",
    "Bool",
    "Buenavista",
    "Bunga",
    "Caboy",
    "Cagawasan",
    "Caluwasan",
    "Candajec",
    "Cantoyoc",
    "Comaang",
    "Danahao",
    "Katipunan",
    "Lajog",
    "Mataub",
    "Nahawan",
    "Poblacion Centro",
    "Poblacion Norte",
    "Poblacion Sur",
    "Tontunan",
    "Tubod",
    "Villaflor"
];

const handleFileUpload = (event) => {
    const files = event.target.files;
    form.photos = Array.from(files);
    form.photo_previews = [];

    Array.from(files).forEach(file => {
        form.photo_previews.push(URL.createObjectURL(file));
    });
};

const removePhoto = (index) => {
    form.photos.splice(index, 1);
    form.photo_previews.splice(index, 1);
    URL.revokeObjectURL(form.photo_previews[index]);
};

const submitReport = () => {
    form.post(route('reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            form.reset();
            form.photo_previews.forEach(url => URL.revokeObjectURL(url));
            form.photo_previews = [];
        }
    });
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <template #title>
            <AquatrackLogo>
                Add Report
            </AquatrackLogo>
        </template>

        <form @submit.prevent="submitReport" class="space-y-4">
            <!-- Location Section -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Fixed Municipality and Province -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Municipality</label>
                    <input type="text" value="Clarin" readonly
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 sm:text-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Province</label>
                    <input type="text" value="Bohol" readonly
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 sm:text-sm p-2 border">
                </div>

                <!-- Barangay Dropdown -->
                <div class="sm:col-span-2">
                    <label for="barangay" class="block text-sm font-medium text-gray-700">Barangay</label>
                    <select id="barangay" v-model="form.barangay" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                        <option value="" disabled selected>Select Barangay</option>
                        <option v-for="barangay in barangays" :key="barangay" :value="barangay">
                            {{ barangay }}
                        </option>
                    </select>
                </div>

                <!-- Purok Input -->
                <div class="sm:col-span-2">
                    <label for="purok" class="block text-sm font-medium text-gray-700">Purok/Street</label>
                    <input type="text" id="purok" v-model="form.purok" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
                        placeholder="Enter purok number or street name">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" v-model="form.description" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Photos</label>
                <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                            viewBox="0 0 48 48" aria-hidden="true">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file-upload"
                                class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none hover:text-blue-500">
                                <span>Upload files</span>
                                <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple
                                    accept="image/*" @change="handleFileUpload">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
                <div v-if="form.photo_previews.length > 0" class="mt-2 grid grid-cols-3 gap-2">
                    <div v-for="(preview, index) in form.photo_previews" :key="index" class="relative">
                        <img :src="preview" class="h-24 w-full object-cover rounded" />
                        <button @click="removePhoto(index)"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1">
                            <v-icon name="bi-x-lg" scale="0.8" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" @click="$emit('close')"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Submit Report
                </button>
            </div>
        </form>
    </Modal>
</template>
