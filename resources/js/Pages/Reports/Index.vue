<template>
    <Navigation>
        <div class="relative w-full min-h-screen overflow-hidden">
            <!-- Background elements - image with gradient overlay -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('/images/AquatrackIMG.jpg')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#062F64]/80 to-[#1E4272]/80">
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 to-transparent"></div>
            </div>

            <!-- Floating water drop decoration -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-blue-500 opacity-10 blur-3xl"></div>
            <div class="absolute bottom-1/3 right-1/4 w-96 h-96 rounded-full bg-teal-500 opacity-10 blur-3xl"></div>

            <!-- Main content -->
            <div class="relative z-10 flex items-center justify-center min-h-screen w-full">
                <div class="flex items-center justify-center flex-col p-4 md:p-10 w-full max-w-4xl">
                    <div class="flex items-center justify-center flex-col mb-8">
                        <v-icon name="bi-exclamation-triangle-fill" class="mb-4 text-[#F87171]" scale="4" />
                        <h1 class="text-3xl md:text-4xl text-white font-bold">Report Water Issue</h1>
                        <p class="text-gray-300 mt-2 text-center">Report water emergencies, leaks, or other issues in
                            your
                            area</p>
                    </div>

                    <div class="w-full p-6 rounded-lg border border-gray-500 bg-[#204878]/90 backdrop-blur-sm">
                        <form @submit.prevent="submitReport">
                            <!-- Reporter Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Reporter Name -->
                                <div>
                                    <label for="reporter_name" class="text-white">Your Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="reporter_name" v-model="form.reporter_name" required
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white"
                                        placeholder="Enter your full name">
                                    <p v-if="form.errors.reporter_name" class="mt-1 text-sm text-red-400">{{
                                        form.errors.reporter_name }}</p>
                                </div>

                                <!-- Reporter Phone -->
                                <div>
                                    <label for="reporter_phone" class="text-white">Phone Number</label>
                                    <input type="tel" id="reporter_phone" v-model="form.reporter_phone"
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white"
                                        placeholder="Your contact number">
                                    <p v-if="form.errors.reporter_phone" class="mt-1 text-sm text-red-400">{{
                                        form.errors.reporter_phone }}</p>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Zone -->
                                <div>
                                    <label for="zone" class="text-white">Zone <span
                                            class="text-red-500">*</span></label>
                                    <select id="zone" v-model="form.zone" required
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white">
                                        <option value="" disabled selected>Select Zone</option>
                                        <option v-for="(zone, index) in Object.keys(zones)" :key="index" :value="zone">
                                            {{
                                                zone }}</option>
                                    </select>
                                    <p v-if="form.errors.zone" class="mt-1 text-sm text-red-400">{{ form.errors.zone }}
                                    </p>
                                </div>

                                <!-- Barangay -->
                                <div>
                                    <label for="barangay" class="text-white">Barangay <span
                                            class="text-red-500">*</span></label>
                                    <select id="barangay" v-model="form.barangay" required :disabled="!form.zone"
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white">
                                        <option value="" disabled selected>Select Barangay</option>
                                        <option v-for="barangay in filteredBarangays" :key="barangay" :value="barangay">
                                            {{
                                                barangay }}</option>
                                    </select>
                                    <p v-if="form.errors.barangay" class="mt-1 text-sm text-red-400">{{
                                        form.errors.barangay
                                        }}</p>
                                </div>
                            </div>

                            <!-- Purok and Priority -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Purok -->
                                <div>
                                    <label for="purok" class="text-white">Purok/Street <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="purok" v-model="form.purok" required
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white"
                                        placeholder="Enter purok number or street name">
                                    <p v-if="form.errors.purok" class="mt-1 text-sm text-red-400">{{ form.errors.purok
                                        }}
                                    </p>
                                </div>

                                <!-- Priority -->
                                <div>
                                    <label for="priority" class="text-white">Priority <span
                                            class="text-red-500">*</span></label>
                                    <select id="priority" v-model="form.priority" required
                                        class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    <p v-if="form.errors.priority" class="mt-1 text-sm text-red-400">{{
                                        form.errors.priority
                                        }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="text-white">Description <span
                                        class="text-red-500">*</span></label>
                                <textarea id="description" v-model="form.description" rows="4" required
                                    class="w-full p-2 mt-2 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white"
                                    placeholder="Describe the water quality issue"></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-400">{{
                                    form.errors.description }}</p>
                            </div>

                            <!-- Photo Upload -->
                            <div class="mt-6">
                                <label class="text-white">Upload Photos <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <label for="file-upload" class="cursor-pointer">
                                        <div
                                            class="flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6 hover:border-blue-400 hover:bg-blue-50/10 transition-colors">
                                            <div class="space-y-1 text-center">
                                                <v-icon name="bi-images" class="mx-auto h-12 w-12 text-gray-400" />
                                                <div class="flex text-sm text-gray-300 justify-center">
                                                    <span
                                                        class="relative font-medium text-blue-300 hover:text-blue-200">
                                                        Click to upload files
                                                    </span>
                                                    <input id="file-upload" name="photos" type="file" class="sr-only"
                                                        multiple accept="image/*" @change="handleFileUpload">
                                                </div>
                                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB each (max {{
                                                    MAX_FILES }} files)</p>
                                                <p v-if="form.photos.length > 0" class="text-xs text-blue-300">
                                                    {{ form.photos.length }} of {{ MAX_FILES }} files selected
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <p v-if="form.errors.photos" class="mt-1 text-sm text-red-400">{{ form.errors.photos }}
                                </p>

                                <!-- Photo Previews -->
                                <div v-if="form.photo_previews.length > 0" class="mt-4">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        <div v-for="(preview, index) in form.photo_previews" :key="index"
                                            class="relative group">
                                            <img :src="preview"
                                                class="h-24 w-full object-cover rounded border border-gray-200" />
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200">
                                                <button @click="removePhoto(index)" type="button"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                                    <v-icon name="bi-x-lg" scale="0.8" />
                                                </button>
                                                <div
                                                    class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                                    {{ (form.photos[index].size / 1024 / 1024).toFixed(2) }} MB
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                <button type="submit" :disabled="form.processing"
                                    class="w-full p-3 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
                                    <span v-if="form.processing" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else class="flex items-center">
                                        <v-icon name="bi-check-circle" class="mr-2" />
                                        Submit Report
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <p class="mt-4 text-sm text-gray-300">For immediate emergencies, please call 911 or your local
                        emergency
                        services</p>
                </div>
            </div>
        </div>
    </Navigation>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Navigation from '@/Components/Header/Navigation.vue';
import { ref, computed, watch } from 'vue';
import Swal from 'sweetalert2';

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

const form = useForm({
    municipality: "Clarin",
    province: "Bohol",
    zone: "",
    barangay: "",
    purok: "",
    description: "",
    photos: [],
    photo_previews: [],
    reporter_name: "",
    reporter_phone: "",
    priority: "medium",
});

const filteredBarangays = computed(() => {
    return form.zone ? zones[form.zone] : [];
});

watch(() => form.zone, (newZone) => {
    form.barangay = "";
});

const MAX_FILES = 5;
const MAX_FILE_SIZE = 10 * 1024 * 1024;

const handleFileUpload = (event) => {
    const files = event.target.files;

    if (form.photos.length + files.length > MAX_FILES) {
        Swal.fire({
            icon: 'error',
            title: 'Too Many Files',
            html: `You can upload a maximum of ${MAX_FILES} files.<br>You currently have ${form.photos.length} files selected.`,
            confirmButtonColor: '#3085d6',
        });
        event.target.value = '';
        return;
    }

    const validFiles = [];
    const invalidFiles = [];
    const invalidTypeFiles = [];

    Array.from(files).forEach(file => {
        if (!file.type.match('image.*')) {
            invalidTypeFiles.push(file.name);
        } else if (file.size > MAX_FILE_SIZE) {
            invalidFiles.push(file.name);
        } else {
            validFiles.push(file);
        }
    });

    if (invalidTypeFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File Type',
            html: `The following files are not images:<br><strong>${invalidTypeFiles.join('<br>')}</strong><br><br>Please select only image files.`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (invalidFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'File Size Exceeded',
            html: `The following files exceed 10MB limit:<br><strong>${invalidFiles.join('<br>')}</strong><br><br>Please select smaller files.`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (validFiles.length > 0) {
        form.photos = [...form.photos, ...validFiles];
        validFiles.forEach(file => {
            form.photo_previews.push(URL.createObjectURL(file));
        });
    }

    event.target.value = '';
};

const removePhoto = (index) => {
    URL.revokeObjectURL(form.photo_previews[index]);
    form.photos.splice(index, 1);
    form.photo_previews.splice(index, 1);
};

const submitReport = () => {
    if (form.photos.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Photos Required',
            text: 'Please upload at least one photo for your report.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    form.post(route('reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.photo_previews.forEach(url => URL.revokeObjectURL(url));
            form.photo_previews = [];
        },
        onError: (errors) => {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'There was an error submitting your report. Please try again.',
                confirmButtonColor: '#3085d6',
            });
        }
    });
};
</script>
