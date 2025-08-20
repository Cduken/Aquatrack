<template>
    <div class="w-full p-4 rounded-lg border border-gray-500 bg-[#204878]/90 backdrop-blur-sm">
        <form @submit.prevent="submitReport">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="municipality" class="text-white text-sm">Municipality <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="municipality" v-model="form.municipality" readonly value="Clarin"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white bg-opacity-50 cursor-not-allowed text-sm">
                </div>

                <div>
                    <label for="province" class="text-white text-sm">Province</label>
                    <input type="text" id="province" v-model="form.province" readonly value="Bohol"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white bg-opacity-50 cursor-not-allowed text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                <div>
                    <label for="reporter_name" class="text-white text-sm">Full Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="reporter_name" v-model="form.reporter_name" required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        placeholder="Enter your full name">
                    <p v-if="form.errors.reporter_name" class="mt-1 text-xs text-red-400">{{ form.errors.reporter_name
                        }}</p>
                </div>

                <div>
                    <label for="reporter_phone" class="text-white text-sm">Phone Number</label>
                    <input type="tel" id="reporter_phone" v-model="form.reporter_phone"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        placeholder="Your contact number">
                    <p v-if="form.errors.reporter_phone" class="mt-1 text-xs text-red-400">{{ form.errors.reporter_phone
                        }}</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="zone" class="text-white text-sm">Zone <span class="text-red-500">*</span></label>
                    <select id="zone" v-model="form.zone" required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm">
                        <option value="" disabled selected>Select Zone</option>
                        <option v-for="(zone, index) in Object.keys(zones)" :key="index" :value="zone">
                            {{ zone }}</option>
                    </select>
                    <p v-if="form.errors.zone" class="mt-1 text-xs text-red-400">{{ form.errors.zone }}</p>
                </div>

                <div>
                    <label for="barangay" class="text-white text-sm">Barangay <span
                            class="text-red-500">*</span></label>
                    <select id="barangay" v-model="form.barangay" required :disabled="!form.zone"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm">
                        <option value="" disabled selected>Select Barangay</option>
                        <option v-for="barangay in filteredBarangays" :key="barangay" :value="barangay">
                            {{ barangay }}</option>
                    </select>
                    <p v-if="form.errors.barangay" class="mt-1 text-xs text-red-400">{{ form.errors.barangay }}</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="purok" class="text-white text-sm">Purok/Street <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="purok" v-model="form.purok" required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        placeholder="Enter purok number or street name">
                    <p v-if="form.errors.purok" class="mt-1 text-xs text-red-400">{{ form.errors.purok }}</p>
                </div>

                <div>
                    <label for="priority" class="text-white text-sm">Priority <span
                            class="text-red-500">*</span></label>
                    <select id="priority" v-model="form.priority" required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                    <p v-if="form.errors.priority" class="mt-1 text-xs text-red-400">{{ form.errors.priority }}</p>
                </div>
            </div>

            <div class="mt-4">
                <label for="description" class="text-white text-sm">Description <span
                        class="text-red-500">*</span></label>
                <textarea id="description" v-model="form.description" rows="3" required
                    class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    placeholder="Describe the water quality issue"></textarea>
                <p v-if="form.errors.description" class="mt-1 text-xs text-red-400">{{ form.errors.description }}</p>
            </div>

            <div class="mt-4">
                <label class="text-white text-sm">Upload Photos/Videos <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <label for="file-upload" class="cursor-pointer">
                        <div
                            class="flex justify-center rounded-md border-2 border-dashed border-gray-300 px-4 pt-4 pb-4 hover:border-blue-400 hover:bg-blue-50/10 transition-colors">
                            <div class="space-y-1 text-center">
                                <v-icon name="bi-images" class="mx-auto h-10 w-10 text-gray-400" />
                                <div class="flex text-xs text-gray-300 justify-center">
                                    <span class="relative font-medium text-blue-300 hover:text-blue-200">
                                        Click to upload files
                                    </span>
                                    <input id="file-upload" name="photos" type="file" class="sr-only" multiple
                                        accept="image/*,video/*" @change="handleFileUpload">
                                </div>
                                <p class="text-xs text-gray-400">
                                    PNG, JPG, GIF (max {{ MAX_PHOTOS }} photos, 5MB each)<br>
                                    MP4 (max {{ MAX_VIDEOS }} videos, 15MB each)<br>
                                    Total files: {{ MAX_TOTAL }} max
                                </p>
                                <p v-if="form.photos.length > 0" class="text-xs text-blue-300">
                                    {{ photoCount }} photos, {{ videoCount }} videos ({{ form.photos.length }} of {{
                                    MAX_TOTAL }} total)
                                </p>
                            </div>
                        </div>
                    </label>
                </div>
                <p v-if="form.errors.photos" class="mt-1 text-xs text-red-400">{{ form.errors.photos }}</p>

                <div v-if="form.photo_previews.length > 0" class="mt-3">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div v-for="(preview, index) in form.photo_previews" :key="index" class="relative group">
                            <template v-if="isVideoFile(form.photos[index])">
                                <div
                                    class="h-20 w-full bg-gray-800 flex items-center justify-center rounded border border-gray-200">
                                    <v-icon name="bi-play-circle-fill" class="text-white text-3xl" />
                                    <span class="sr-only">Video preview</span>
                                </div>
                            </template>
                            <template v-else>
                                <img :src="preview" class="h-20 w-full object-cover rounded border border-gray-200"
                                    :alt="'Preview of image ' + (index + 1)" />
                            </template>
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200">
                                <button @click="removePhoto(index)" type="button"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <v-icon name="bi-x-lg" scale="0.7" />
                                </button>
                                <div
                                    class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                    {{ (form.photos[index].size / 1024 / 1024).toFixed(2) }} MB
                                    <span v-if="isVideoFile(form.photos[index])">(Video)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button type="submit" :disabled="form.processing || isSubmitting"
                    class="w-full p-2 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed text-sm">
                    <span v-if="isSubmitting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Submitting...
                    </span>
                    <span v-else-if="form.processing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </span>
                    <span v-else class="flex items-center">
                        <v-icon name="bi-check-circle" class="mr-2" scale="0.9" />
                        Submit Report
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    zones: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['update:showSuccessModal', 'update:successData', 'update:showTrackModal', 'track-report']);

// Loading state for submit button
const isSubmitting = ref(false);

// Form data and validation
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

// File upload limits
const MAX_PHOTOS = 5;
const MAX_VIDEOS = 3;
const MAX_TOTAL = MAX_PHOTOS + MAX_VIDEOS;
const MAX_PHOTO_SIZE = 5 * 1024 * 1024; // 5MB
const MAX_VIDEO_SIZE = 15 * 1024 * 1024; // 15MB

// Computed properties
const filteredBarangays = computed(() => {
    return props.zones[form.zone] || [];
});

// Watchers
watch(() => form.zone, () => {
    form.barangay = ""; // Reset barangay when zone changes
});

// Helper functions
const isVideoFile = (file) => {
    return file?.type?.match('video.*');
};

const handleFileUpload = (event) => {
    const files = event.target.files;
    if (!files || files.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'No Files Selected',
            text: 'Please select at least one file to upload.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    const currentPhotos = photoCount.value;
    const currentVideos = videoCount.value;

    // Check total files limit
    if (form.photos.length + files.length > MAX_TOTAL) {
        Swal.fire({
            icon: 'error',
            title: 'Too Many Files',
            html: `You can upload a maximum of ${MAX_TOTAL} files total (${MAX_PHOTOS} photos + ${MAX_VIDEOS} videos).<br>You currently have ${form.photos.length} files selected.`,
            confirmButtonColor: '#3085d6',
        });
        event.target.value = '';
        return;
    }

    const validFiles = [];
    const invalidPhotoSizeFiles = [];
    const invalidVideoSizeFiles = [];
    const invalidTypeFiles = [];
    const overPhotoLimit = [];
    const overVideoLimit = [];

    Array.from(files).forEach(file => {
        const isImage = file.type.match('image.*');
        const isVideo = isVideoFile(file);

        if (!isImage && !isVideo) {
            invalidTypeFiles.push(file.name);
        } else if (isImage && (currentPhotos + validFiles.filter(f => f.type.match('image.*')).length >= MAX_PHOTOS)) {
            overPhotoLimit.push(file.name);
        } else if (isVideo && (currentVideos + validFiles.filter(f => isVideoFile(f)).length >= MAX_VIDEOS)) {
            overVideoLimit.push(file.name);
        } else if (isImage && file.size > MAX_PHOTO_SIZE) {
            invalidPhotoSizeFiles.push(file.name);
        } else if (isVideo && file.size > MAX_VIDEO_SIZE) {
            invalidVideoSizeFiles.push(file.name);
        } else {
            validFiles.push(file);
        }
    });

    // Show error messages
    if (invalidTypeFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File Type',
            html: `The following files are not supported:<br><strong>${invalidTypeFiles.join('<br>')}</strong><br><br>Please select only image or video files.`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (invalidPhotoSizeFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Photo Size Exceeded',
            html: `The following photos exceed 5MB limit:<br><strong>${invalidPhotoSizeFiles.join('<br>')}</strong><br><br>Please select smaller photos.`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (invalidVideoSizeFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Video Size Exceeded',
            html: `The following videos exceed 15MB limit:<br><strong>${invalidVideoSizeFiles.join('<br>')}</strong><br><br>Please select smaller videos.`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (overPhotoLimit.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Photo Limit Reached',
            html: `You can only upload ${MAX_PHOTOS} photos maximum.<br>The following files won't be added:<br><strong>${overPhotoLimit.join('<br>')}</strong>`,
            confirmButtonColor: '#3085d6',
        });
    }

    if (overVideoLimit.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Video Limit Reached',
            html: `You can only upload ${MAX_VIDEOS} videos maximum.<br>The following files won't be added:<br><strong>${overVideoLimit.join('<br>')}</strong>`,
            confirmButtonColor: '#3085d6',
        });
    }

    // Process valid files
    if (validFiles.length > 0) {
        form.photos = [...form.photos, ...validFiles];
        validFiles.forEach(file => {
            if (isVideoFile(file)) {
                form.photo_previews.push('/images/video-icon.png');
            } else {
                try {
                    form.photo_previews.push(URL.createObjectURL(file));
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Processing Error',
                        text: `Failed to process file ${file.name}. Please try again.`,
                        confirmButtonColor: '#3085d6',
                    });
                }
            }
        });
    }

    event.target.value = '';
};

const removePhoto = (index) => {
    if (!isVideoFile(form.photos[index])) {
        URL.revokeObjectURL(form.photo_previews[index]);
    }
    form.photos.splice(index, 1);
    form.photo_previews.splice(index, 1);
};

const submitReport = () => {
    if (form.photos.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Media Required',
            text: 'Please upload at least one photo or video for your report.',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    isSubmitting.value = true;

    setTimeout(() => {
        form.post(route('reports.store'), {
            preserveScroll: true,
            onSuccess: (response) => {
                isSubmitting.value = false;

                // Emit events to update parent state
                emit('update:successData', {
                    trackingCode: response.props.trackingCode,
                    dateSubmitted: response.props.dateSubmitted
                });
                emit('update:showSuccessModal', true);

                // Show SweetAlert success modal
                Swal.fire({
                    position: 'top-end',
                    title: 'Report Submitted Successfully!',
                    text: `Tracking Code: ${response.props.trackingCode}`,
                    icon: 'success',
                    toast: true,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });

                // Reset form
                form.reset();
                form.photo_previews.forEach(url => {
                    if (url !== '/images/video-icon.png') {
                        URL.revokeObjectURL(url);
                    }
                });
                form.photo_previews = [];
            },
            onError: (errors) => {
                isSubmitting.value = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: 'There was an error submitting your report. Please try again.',
                    confirmButtonColor: '#3085d6',
                });
            }
        });
    }, 2000);
};

// Computed properties for file counts
const videoCount = computed(() => {
    return form.photos.filter(file => isVideoFile(file)).length;
});

const photoCount = computed(() => {
    return form.photos.filter(file => !isVideoFile(file)).length;
});
</script>
