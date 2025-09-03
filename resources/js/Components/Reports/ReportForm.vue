<template>
    <div
        class="w-full p-4 rounded-lg border border-gray-500 bg-[#204878]/90 backdrop-blur-sm"
    >
        <!-- Form Errors Indicator -->
        <div
            v-if="hasErrors"
            class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm"
        >
            Please fix the following errors before submitting:
            <ul class="list-disc list-inside mt-1">
                <li v-for="(error, key) in form.errors" :key="key">
                    {{ error }}
                </li>
            </ul>
        </div>

        <form @submit.prevent="submitReport">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="municipality" class="text-white text-sm"
                        >Municipality <span class="text-red-500">*</span></label
                    >
                    <input
                        type="text"
                        id="municipality"
                        v-model="form.municipality"
                        readonly
                        value="Clarin"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white bg-opacity-50 cursor-not-allowed text-sm"
                    />
                </div>

                <div>
                    <label for="province" class="text-white text-sm"
                        >Province</label
                    >
                    <input
                        type="text"
                        id="province"
                        v-model="form.province"
                        readonly
                        value="Bohol"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white bg-opacity-50 cursor-not-allowed text-sm"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                <div>
                    <label for="reporter_name" class="text-white text-sm"
                        >Full Name <span class="text-red-500">*</span></label
                    >
                    <input
                        type="text"
                        id="reporter_name"
                        v-model="form.reporter_name"
                        required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        placeholder="Enter your full name"
                    />
                    <p
                        v-if="form.errors.reporter_name"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.reporter_name }}
                    </p>
                </div>

                <div>
                    <label for="reporter_phone" class="text-white text-sm"
                        >Phone Number</label
                    >
                    <input
                        type="tel"
                        id="reporter_phone"
                        v-model="form.reporter_phone"
                        @input="restrictPhoneInput"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        pattern="[0-9]{1,11}"
                        maxlength="11"
                        placeholder="Your contact number"
                    />
                    <p
                        v-if="form.errors.reporter_phone"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.reporter_phone }}
                    </p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="barangay" class="text-white text-sm"
                        >Barangay <span class="text-red-500">*</span></label
                    >
                    <select
                        id="barangay"
                        v-model="form.barangay"
                        required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    >
                        <option value="" disabled selected>
                            Select Barangay
                        </option>
                        <option
                            v-for="barangay in allBarangays"
                            :key="barangay"
                            :value="barangay"
                        >
                            {{ barangay }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.barangay"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.barangay }}
                    </p>
                </div>

                <div>
                    <label for="zone" class="text-white text-sm"
                        >Zone <span class="text-red-500">*</span></label
                    >
                    <input
                        type="text"
                        id="zone"
                        v-model="form.zone"
                        readonly
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white bg-opacity-50 cursor-not-allowed text-sm"
                        placeholder="Zone will be auto-filled"
                    />
                    <p
                        v-if="form.errors.zone"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.zone }}
                    </p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="purok" class="text-white text-sm"
                        >Purok/Street <span class="text-red-500">*</span></label
                    >
                    <input
                        type="text"
                        id="purok"
                        v-model="form.purok"
                        required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                        placeholder="Enter purok number or street name"
                    />
                    <p
                        v-if="form.errors.purok"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.purok }}
                    </p>
                </div>

                <div>
                    <label for="priority" class="text-white text-sm"
                        >Priority <span class="text-red-500">*</span></label
                    >
                    <select
                        id="priority"
                        v-model="form.priority"
                        required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    >
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                    <p
                        v-if="form.errors.priority"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.priority }}
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <label for="description" class="text-white text-sm"
                    >Description <span class="text-red-500">*</span></label
                >
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    required
                    class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 placeholder:text-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    placeholder="Describe the water quality issue"
                ></textarea>
                <p
                    v-if="form.errors.description"
                    class="mt-1 text-xs text-red-400"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <!-- Enhanced Camera Section with Video -->
            <div class="mt-4">
                <label class="text-white text-sm block mb-2">
                    Capture Evidence
                    <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-300 ml-2">
                        ({{ form.photos.length }}/{{ MAX_TOTAL }}) - Photos:
                        {{
                            form.photos.filter((file) =>
                                file.type.startsWith("image")
                            ).length
                        }}/{{ MAX_PHOTOS }}, Videos:
                        {{
                            form.photos.filter((file) =>
                                file.type.startsWith("video")
                            ).length
                        }}/{{ MAX_VIDEOS }}
                    </span>
                </label>

                <!-- Camera Status Banner -->
                <div
                    v-if="cameraError"
                    class="mb-3 p-3 bg-red-900/50 border border-red-600 rounded-lg"
                >
                    <div class="flex items-center text-red-300 text-sm">
                        <v-icon
                            name="bi-exclamation-triangle-fill"
                            class="w-4 h-4 mr-2"
                        />
                        {{ cameraError }}
                    </div>
                    <button
                        type="button"
                        @click="retryCamera"
                        class="mt-2 text-xs text-blue-300 hover:text-blue-200 underline"
                    >
                        Try Again
                    </button>
                </div>

                <!-- Camera Interface -->
                <div
                    class="bg-gray-800 rounded-lg overflow-hidden border border-gray-600"
                >
                    <!-- Camera Not Active State -->
                    <div v-if="!isCameraActive" class="p-6 text-center">
                        <div class="mb-4">
                            <div
                                class="w-16 h-16 mx-auto bg-blue-600 rounded-full flex items-center justify-center mb-3"
                            >
                                <v-icon
                                    name="hi-camera"
                                    class="w-8 h-8 text-white"
                                />
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">
                                Camera Required
                            </h3>
                            <p class="text-gray-300 text-sm mb-4">
                                Take photos and videos to document the water
                                quality issue
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="initializeCamera"
                            :disabled="isCameraLoading"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-800 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:transform-none"
                        >
                            <v-icon
                                v-if="isCameraLoading"
                                name="bi-arrow-repeat"
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            />
                            <v-icon
                                v-else
                                name="hi-camera"
                                class="w-5 h-5 mr-2"
                            />
                            {{
                                isCameraLoading
                                    ? "Starting Camera..."
                                    : "Open Camera"
                            }}
                        </button>

                        <div class="mt-4 text-xs text-gray-400">
                            <p>Your browser will ask for camera permission</p>
                            <p>
                                Make sure to allow access to capture photos and
                                videos
                            </p>
                        </div>
                    </div>

                    <!-- Active Camera View -->
                    <div v-else class="relative">
                        <!-- Video Element Container -->
                        <div
                            class="relative bg-black"
                            style="aspect-ratio: 4/3"
                        >
                            <video
                                ref="videoElement"
                                class="w-full h-full object-cover"
                                autoplay
                                playsinline
                                muted
                            ></video>

                            <!-- Camera Loading Overlay -->
                            <div
                                v-if="!isCameraReady"
                                class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center"
                            >
                                <div class="text-center text-white">
                                    <div
                                        class="inline-block animate-spin w-8 h-8 border-2 border-white border-t-transparent rounded-full mb-3"
                                    ></div>
                                    <p class="text-sm">{{ cameraStatus }}</p>
                                    <div
                                        class="mt-2 w-48 bg-gray-700 rounded-full h-1"
                                    >
                                        <div
                                            class="bg-blue-500 h-1 rounded-full transition-all duration-300"
                                            :style="{
                                                width: loadingProgress + '%',
                                            }"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Camera Ready Indicator -->
                            <div
                                v-if="isCameraReady"
                                class="absolute top-4 left-4"
                            >
                                <div
                                    class="flex items-center bg-green-500 text-white px-2 py-1 rounded-full text-xs"
                                >
                                    <div
                                        class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"
                                    ></div>
                                    LIVE
                                </div>
                            </div>

                            <!-- Recording Indicator -->
                            <div
                                v-if="isRecording"
                                class="absolute top-4 left-1/2 transform -translate-x-1/2"
                            >
                                <div
                                    class="flex items-center bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    <div
                                        class="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"
                                    ></div>
                                    REC {{ formatTime(recordingTime) }}
                                </div>
                            </div>

                            <!-- Camera Info -->
                            <div
                                v-if="
                                    isCameraReady && availableCameras.length > 1
                                "
                                class="absolute top-4 right-4"
                            >
                                <div
                                    class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs"
                                >
                                    {{ currentCameraName }}
                                </div>
                            </div>

                            <!-- Media Counter -->
                            <div
                                v-if="isCameraReady"
                                class="absolute bottom-4 left-4"
                            >
                                <div
                                    class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs"
                                >
                                    Photos:
                                    {{
                                        form.photos.filter((file) =>
                                            file.type.startsWith("image")
                                        ).length
                                    }}/{{ MAX_PHOTOS }}
                                    <br />
                                    Videos:
                                    {{
                                        form.photos.filter((file) =>
                                            file.type.startsWith("video")
                                        ).length
                                    }}/{{ MAX_VIDEOS }}
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Camera Controls -->
                        <div class="bg-gray-900 p-4">
                            <div class="flex items-center justify-center gap-4">
                                <!-- Switch Camera Button -->
                                <button
                                    type="button"
                                    @click="switchCamera"
                                    v-if="
                                        hasMultipleCameras &&
                                        isCameraReady &&
                                        !isRecording
                                    "
                                    class="p-3 bg-gray-700 hover:bg-gray-600 rounded-full transition-colors duration-200 text-white disabled:opacity-50"
                                    :disabled="
                                        !isCameraReady || isSwitchingCamera
                                    "
                                    :title="'Switch to ' + getNextCameraName()"
                                >
                                    <v-icon
                                        v-if="isSwitchingCamera"
                                        name="eo-loading"
                                        class="animate-spin w-6 h-6"
                                    />
                                    <v-icon
                                        v-else
                                        name="bi-arrow-repeat"
                                        class="w-6 h-6"
                                    />
                                </button>

                                <!-- Photo Capture Button (Blue) -->
                                <button
                                    type="button"
                                    @click="capturePhoto"
                                    :disabled="
                                        !isCameraReady ||
                                        form.photos.filter((file) =>
                                            file.type.startsWith('image')
                                        ).length >= MAX_PHOTOS ||
                                        isCapturing ||
                                        isRecording
                                    "
                                    class="relative p-4 bg-blue-500 hover:bg-blue-600 rounded-full transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 shadow-lg"
                                >
                                    <v-icon
                                        name="hi-camera"
                                        class="w-6 h-6 text-white"
                                    />
                                    <!-- Capture animation ring -->
                                    <div
                                        v-if="isCapturing"
                                        class="absolute inset-0 border-4 border-white rounded-full animate-ping"
                                    ></div>
                                </button>

                                <!-- Video Recording Button (Red) -->
                                <button
                                    type="button"
                                    @click="
                                        isRecording
                                            ? stopVideoRecording()
                                            : startVideoRecording()
                                    "
                                    :disabled="
                                        !isCameraReady ||
                                        (form.photos.filter((file) =>
                                            file.type.startsWith('video')
                                        ).length >= MAX_VIDEOS &&
                                            !isRecording)
                                    "
                                    class="relative p-4 rounded-full transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 shadow-lg"
                                    :class="
                                        isRecording
                                            ? 'bg-red-500 hover:bg-red-600'
                                            : 'bg-red-500 hover:bg-red-600'
                                    "
                                >
                                    <div
                                        v-if="isRecording"
                                        class="w-6 h-6 bg-white rounded-sm"
                                    ></div>
                                    <v-icon
                                        v-else
                                        name="hi-video-camera"
                                        class="w-6 h-6 text-white"
                                    />
                                </button>

                                <!-- Close Camera Button -->
                                <button
                                    type="button"
                                    @click="stopCamera"
                                    :disabled="isRecording"
                                    class="p-3 bg-red-600 hover:bg-red-700 rounded-full transition-colors duration-200 text-white disabled:opacity-50"
                                    title="Close Camera"
                                >
                                    <v-icon name="hi-solid-x" class="w-6 h-6" />
                                </button>
                            </div>

                            <!-- Camera Instructions -->
                            <div
                                v-if="isCameraReady"
                                class="mt-3 text-center text-xs text-gray-400"
                            >
                                <p v-if="form.photos.length === 0">
                                    Tap the blue button for photos or red button
                                    for videos
                                </p>
                                <p
                                    v-else-if="
                                        form.photos.filter((file) =>
                                            file.type.startsWith('image')
                                        ).length < MAX_PHOTOS ||
                                        form.photos.filter((file) =>
                                            file.type.startsWith('video')
                                        ).length < MAX_VIDEOS
                                    "
                                >
                                    {{
                                        MAX_PHOTOS -
                                        form.photos.filter((file) =>
                                            file.type.startsWith("image")
                                        ).length
                                    }}
                                    photos and
                                    {{
                                        MAX_VIDEOS -
                                        form.photos.filter((file) =>
                                            file.type.startsWith("video")
                                        ).length
                                    }}
                                    videos remaining
                                </p>
                                <p v-else class="text-yellow-400">
                                    Maximum media reached
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Gallery -->
                <div v-if="form.photo_previews.length > 0" class="mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-white text-sm font-medium">
                            Captured Media ({{ form.photo_previews.length }})
                        </h4>
                        <button
                            type="button"
                            @click="clearAllMedia"
                            class="text-xs text-red-400 hover:text-red-300 underline"
                        >
                            Clear All
                        </button>
                    </div>

                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"
                    >
                        <div
                            v-for="(preview, index) in form.photo_previews"
                            :key="'media-' + index"
                            class="relative group aspect-square"
                        >
                            <!-- Photo -->
                            <div
                                v-if="
                                    form.photos[index].type.startsWith('image')
                                "
                            >
                                <img
                                    :src="preview"
                                    class="w-full h-full object-cover rounded-lg border-2 border-gray-600 group-hover:border-blue-400 transition-colors"
                                    :alt="'Photo ' + (index + 1)"
                                />
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center"
                                >
                                    <button
                                        @click="removeMedia(index)"
                                        type="button"
                                        class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transform scale-75 hover:scale-100 transition-all duration-200"
                                    >
                                        <v-icon
                                            name="hi-trash"
                                            class="w-4 h-4"
                                        />
                                    </button>
                                </div>
                                <div
                                    class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded flex items-center"
                                >
                                    <v-icon
                                        name="hi-photograph"
                                        class="w-3 h-3 mr-1"
                                    />
                                    {{
                                        form.photos
                                            .filter((file) =>
                                                file.type.startsWith("image")
                                            )
                                            .indexOf(form.photos[index]) + 1
                                    }}
                                </div>
                            </div>
                            <!-- Video -->
                            <div v-else>
                                <video
                                    :src="preview"
                                    class="w-full h-full object-cover rounded-lg border-2 border-gray-600 group-hover:border-green-400 transition-colors"
                                    muted
                                    preload="metadata"
                                ></video>
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center rounded-lg"
                                >
                                    <div
                                        class="bg-white bg-opacity-80 rounded-full p-2"
                                    >
                                        <v-icon
                                            name="hi-play"
                                            class="w-6 h-6 text-gray-800"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center"
                                >
                                    <button
                                        @click="removeMedia(index)"
                                        type="button"
                                        class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transform scale-75 hover:scale-100 transition-all duration-200 ml-auto mt-2 mr-2 absolute top-0 right-0"
                                    >
                                        <v-icon
                                            name="hi-trash"
                                            class="w-4 h-4"
                                        />
                                    </button>
                                </div>
                                <div
                                    class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded flex items-center"
                                >
                                    <v-icon
                                        name="hi-video-camera"
                                        class="w-3 h-3 mr-1"
                                    />
                                    {{
                                        form.photos
                                            .filter((file) =>
                                                file.type.startsWith("video")
                                            )
                                            .indexOf(form.photos[index]) + 1
                                    }}
                                </div>
                                <div
                                    class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded"
                                >
                                    {{ getVideoDuration(form.photos[index]) }}s
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media requirement message -->
                <div
                    v-if="form.photos.length === 0"
                    class="mt-3 p-3 bg-blue-900/30 border border-blue-600 rounded-lg"
                >
                    <div class="flex items-center text-blue-300 text-sm">
                        <v-icon
                            name="hi-information-circle"
                            class="w-4 h-4 mr-2"
                        />
                        At least one photo or video is required to submit your
                        report
                    </div>
                </div>

                <p v-if="form.errors.photos" class="mt-2 text-xs text-red-400">
                    {{ form.errors.photos }}
                </p>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    :disabled="form.processing || isSubmitting || isRecording"
                    class="w-full p-2 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed text-sm"
                >
                    <span v-if="isSubmitting" class="flex items-center">
                        <v-icon
                            name="bi-arrow-repeat"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                        />
                        Submitting...
                    </span>
                    <span
                        v-else-if="isRecording"
                        class="flex items-center text-yellow-300"
                    >
                        <div
                            class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"
                        ></div>
                        Stop recording to submit
                    </span>
                    <span v-else>Submit Report</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    zones: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([
    "update:showSuccessModal",
    "update:successData",
    "update:showTrackModal",
    "track-report",
]);

const isSubmitting = ref(false);
const locationStatus = ref("idle");
const isCameraActive = ref(false);
const isCameraReady = ref(false);
const isCameraLoading = ref(false);
const isSwitchingCamera = ref(false);
const isCapturing = ref(false);
const isRecording = ref(false);
const recordingTime = ref(0);
const availableCameras = ref([]);
const currentCameraIndex = ref(0);
const videoElement = ref(null);
const cameraError = ref("");
const cameraStatus = ref("Initializing...");
const loadingProgress = ref(0);

// Time tracking
const currentDate = ref("");
const currentTime = ref("");
const currentLocation = ref("Clarin, Bohol");

let stream = null;
let progressInterval = null;
let mediaRecorder = null;
let recordedChunks = [];
let recordingInterval = null;
let timeUpdateInterval = null;

const hasMultipleCameras = computed(() => availableCameras.value.length > 1);
const currentCameraName = computed(() => {
    if (availableCameras.value.length === 0) return "";
    const camera = availableCameras.value[currentCameraIndex.value];
    return camera?.label || `Camera ${currentCameraIndex.value + 1}`;
});

const form = useForm({
    municipality: "Clarin",
    province: "Bohol",
    zone: "",
    barangay: "",
    purok: "",
    description: "",
    photos: [],
    photo_previews: [],
    videos: [], // Kept for compatibility but will be empty
    video_previews: [], // Kept for compatibility but will be empty
    reporter_name: "",
    reporter_phone: "",
    priority: "medium",
    latitude: null,
    longitude: null,
});

const MAX_PHOTOS = 3;
const MAX_VIDEOS = 2;
const MAX_TOTAL = MAX_PHOTOS + MAX_VIDEOS;
const MAX_PHOTO_SIZE = 5 * 1024 * 1024;
const MAX_VIDEO_SIZE = 25 * 1024 * 1024;
const MAX_VIDEO_DURATION = 30; // 30 seconds

// Update time display
const updateTimeDisplay = () => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
    currentTime.value = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
    });
};

// Compute all barangays from zones object
const allBarangays = computed(() => {
    return Object.values(props.zones).flat();
});

// Map barangay to zone
const barangayToZone = computed(() => {
    const mapping = {};
    Object.entries(props.zones).forEach(([zone, barangays]) => {
        barangays.forEach((barangay) => {
            mapping[barangay] = zone;
        });
    });
    return mapping;
});

const hasErrors = computed(() => {
    return Object.keys(form.errors).length > 0;
});

const getNextCameraName = () => {
    if (availableCameras.value.length <= 1) return "";
    const nextIndex =
        (currentCameraIndex.value + 1) % availableCameras.value.length;
    const camera = availableCameras.value[nextIndex];
    return camera?.label || `Camera ${nextIndex + 1}`;
};

const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, "0")}`;
};

const getVideoDuration = (videoFile) => {
    return (
        videoFile.duration || Math.floor((videoFile.size / (1024 * 1024)) * 3)
    ); // Use stored duration or fallback
};

const startLoadingProgress = () => {
    loadingProgress.value = 0;
    progressInterval = setInterval(() => {
        if (loadingProgress.value < 90) {
            loadingProgress.value += Math.random() * 15;
        }
    }, 200);
};

const stopLoadingProgress = () => {
    if (progressInterval) {
        clearInterval(progressInterval);
        progressInterval = null;
    }
    loadingProgress.value = 100;
    setTimeout(() => {
        loadingProgress.value = 0;
    }, 500);
};

const getCameras = async () => {
    try {
        cameraStatus.value = "Detecting cameras...";
        const devices = await navigator.mediaDevices.enumerateDevices();
        availableCameras.value = devices.filter(
            (device) => device.kind === "videoinput"
        );

        if (
            availableCameras.value.length > 0 &&
            !availableCameras.value[0].label
        ) {
            try {
                const tempStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                });
                tempStream.getTracks().forEach((track) => track.stop());
                const devicesWithLabels =
                    await navigator.mediaDevices.enumerateDevices();
                availableCameras.value = devicesWithLabels.filter(
                    (device) => device.kind === "videoinput"
                );
            } catch (e) {
                console.warn("Could not get camera labels:", e);
            }
        }

        console.log("Available cameras:", availableCameras.value);
        return availableCameras.value;
    } catch (error) {
        console.error("Error getting cameras:", error);
        throw new Error("Could not detect camera devices");
    }
};

const initializeCamera = async () => {
    cameraError.value = "";
    isCameraLoading.value = true;
    startLoadingProgress();

    try {
        cameraStatus.value = "Requesting camera permission...";

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error("Camera not supported by this browser");
        }

        await getCameras();

        if (availableCameras.value.length === 0) {
            throw new Error("No camera devices found");
        }

        await startCameraStream();

        // Start time updates
        updateTimeDisplay();
        timeUpdateInterval = setInterval(updateTimeDisplay, 1000);
    } catch (error) {
        console.error("Camera initialization failed:", error);
        handleCameraError(error);
    } finally {
        isCameraLoading.value = false;
        stopLoadingProgress();
    }
};

const startCameraStream = async () => {
    try {
        cameraStatus.value = "Starting camera...";

        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }

        isCameraActive.value = true;
        await nextTick();

        if (!videoElement.value) {
            throw new Error("Video element not available");
        }

        const constraints = {
            video: {
                width: { ideal: 1280, max: 1920 },
                height: { ideal: 720, max: 1080 },
                frameRate: { ideal: 30, max: 60 },
            },
            audio: true, // Enable audio for video recording
        };

        if (availableCameras.value.length > 0) {
            if (currentCameraIndex.value < availableCameras.value.length) {
                constraints.video.deviceId = {
                    exact: availableCameras.value[currentCameraIndex.value]
                        .deviceId,
                };
            }
        }

        console.log("Starting camera with constraints:", constraints);
        cameraStatus.value = "Connecting to camera...";

        stream = await navigator.mediaDevices.getUserMedia(constraints);

        cameraStatus.value = "Loading video stream...";
        videoElement.value.srcObject = stream;

        await new Promise((resolve, reject) => {
            const timeout = setTimeout(
                () => reject(new Error("Video load timeout")),
                10000
            );

            const onLoadedMetadata = () => {
                clearTimeout(timeout);
                videoElement.value.removeEventListener(
                    "loadedmetadata",
                    onLoadedMetadata
                );
                videoElement.value.removeEventListener("error", onError);
                resolve();
            };

            const onError = (e) => {
                clearTimeout(timeout);
                videoElement.value.removeEventListener(
                    "loadedmetadata",
                    onLoadedMetadata
                );
                videoElement.value.removeEventListener("error", onError);
                reject(new Error("Video failed to load"));
            };

            videoElement.value.addEventListener(
                "loadedmetadata",
                onLoadedMetadata
            );
            videoElement.value.addEventListener("error", onError);

            const playPromise = videoElement.value.play();
            if (playPromise) {
                playPromise.catch(console.warn);
            }
        });

        isCameraReady.value = true;
        cameraStatus.value = "Camera ready";
        console.log("Camera started successfully");
    } catch (error) {
        console.error("Camera stream error:", error);
        throw error;
    }
};

const handleCameraError = (error) => {
    isCameraActive.value = false;
    isCameraReady.value = false;

    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
    }

    let errorMessage = "Camera initialization failed";

    if (
        error.name === "NotAllowedError" ||
        error.message.includes("permission")
    ) {
        errorMessage =
            "Camera and microphone access denied. Please allow permissions and try again.";
    } else if (
        error.name === "NotFoundError" ||
        error.message.includes("No camera")
    ) {
        errorMessage = "No camera found on your device.";
    } else if (error.name === "NotReadableError") {
        errorMessage = "Camera is busy or being used by another application.";
    } else if (error.name === "OverconstrainedError") {
        errorMessage = "Camera settings not supported by your device.";
    } else if (error.message.includes("not supported")) {
        errorMessage = "Camera not supported by this browser.";
    }

    cameraError.value = errorMessage;
};

const retryCamera = () => {
    cameraError.value = "";
    initializeCamera();
};

const stopCamera = () => {
    if (isRecording.value) {
        stopVideoRecording();
    }

    if (timeUpdateInterval) {
        clearInterval(timeUpdateInterval);
        timeUpdateInterval = null;
    }

    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        if (videoElement.value) {
            videoElement.value.srcObject = null;
        }
        console.log("Camera stopped");
    }

    isCameraActive.value = false;
    isCameraReady.value = false;
    isCameraLoading.value = false;
    stream = null;
    cameraError.value = "";

    stopLoadingProgress();
};

const switchCamera = async () => {
    if (
        availableCameras.value.length <= 1 ||
        !isCameraReady.value ||
        isRecording.value
    )
        return;

    try {
        isSwitchingCamera.value = true;
        isCameraReady.value = false;
        cameraStatus.value = "Switching camera...";

        currentCameraIndex.value =
            (currentCameraIndex.value + 1) % availableCameras.value.length;
        console.log("Switching to camera:", currentCameraName.value);

        await startCameraStream();
    } catch (error) {
        console.error("Camera switch failed:", error);
        handleCameraError(error);
    } finally {
        isSwitchingCamera.value = false;
    }
};

const startVideoRecording = async () => {
    if (
        !isCameraReady.value ||
        form.photos.filter((file) => file.type.startsWith("video")).length >=
            MAX_VIDEOS ||
        !stream
    ) {
        return;
    }

    try {
        recordedChunks = [];
        recordingTime.value = 0;

        // Create MediaRecorder
        const options = {
            mimeType: "video/webm;codecs=vp9,opus",
        };

        // Fallback mimeTypes
        if (!MediaRecorder.isTypeSupported(options.mimeType)) {
            options.mimeType = "video/webm;codecs=vp8,opus";
            if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                options.mimeType = "video/webm";
                if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                    options.mimeType = "";
                }
            }
        }

        mediaRecorder = new MediaRecorder(stream, options);

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = async () => {
            const blob = new Blob(recordedChunks, { type: "video/webm" });

            if (blob.size > MAX_VIDEO_SIZE) {
                Swal.fire({
                    icon: "error",
                    title: "Video Too Large",
                    text: `Video size exceeds ${
                        MAX_VIDEO_SIZE / 1024 / 1024
                    }MB limit.`,
                    timer: 3000,
                });
                return;
            }

            const filename = `water-report-video-${Date.now()}.webm`;
            const file = new File([blob], filename, {
                type: "video/webm",
                lastModified: Date.now(),
            });

            file.duration = recordingTime.value; // Store duration in seconds

            // Add to form.photos instead of form.videos
            form.photos.push(file);
            form.photo_previews.push(URL.createObjectURL(blob));

            console.log(
                `Video ${
                    form.photos.filter((file) => file.type.startsWith("video"))
                        .length
                } recorded successfully`
            );

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: "success",
                title: `Video ${
                    form.photos.filter((file) => file.type.startsWith("video"))
                        .length
                } recorded!`,
            });
        };

        mediaRecorder.start();
        isRecording.value = true;

        // Start recording timer
        recordingInterval = setInterval(() => {
            recordingTime.value++;

            // Auto-stop at max duration
            if (recordingTime.value >= MAX_VIDEO_DURATION) {
                stopVideoRecording();
            }
        }, 1000);

        console.log("Video recording started");
    } catch (error) {
        console.error("Failed to start recording:", error);
        Swal.fire({
            icon: "error",
            title: "Recording Failed",
            text: "Failed to start video recording. Please try again.",
            timer: 3000,
        });
    }
};

const stopVideoRecording = () => {
    if (!isRecording.value || !mediaRecorder) return;

    try {
        mediaRecorder.stop();
        isRecording.value = false;

        if (recordingInterval) {
            clearInterval(recordingInterval);
            recordingInterval = null;
        }

        console.log("Video recording stopped");
    } catch (error) {
        console.error("Failed to stop recording:", error);
    }
};

const capturePhoto = async () => {
    if (
        !isCameraReady.value ||
        form.photos.filter((file) => file.type.startsWith("image")).length >=
            MAX_PHOTOS ||
        isCapturing.value ||
        isRecording.value
    ) {
        return;
    }

    try {
        isCapturing.value = true;

        const video = videoElement.value;
        if (!video || !video.videoWidth || !video.videoHeight) {
            throw new Error("Video not ready for capture");
        }

        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Draw video frame
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Enhanced timestamp with better visibility
        const now = new Date();
        const timestamp = now.toLocaleString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: true,
        });

        // Create timestamp background
        const timestampPadding = 20;
        const fontSize = Math.max(16, Math.floor(canvas.width / 60));
        ctx.font = `bold ${fontSize}px Arial`;

        // Measure text
        const textMetrics = ctx.measureText(timestamp);
        const textWidth = textMetrics.width;
        const textHeight = fontSize;

        const bgX = timestampPadding;
        const bgY = canvas.height - timestampPadding - textHeight - 10;
        const bgWidth = textWidth + 20;
        const bgHeight = textHeight + 20;

        // Draw semi-transparent background
        ctx.fillStyle = "rgba(0, 0, 0, 0.8)";
        ctx.fillRect(bgX, bgY, bgWidth, bgHeight);

        // Draw white border
        ctx.strokeStyle = "white";
        ctx.lineWidth = 2;
        ctx.strokeRect(bgX, bgY, bgWidth, bgHeight);

        // Draw text with heavy outline for better visibility
        const textX = bgX + 10;
        const textY = bgY + textHeight + 5;

        // Multiple stroke layers for better visibility
        ctx.lineWidth = 6;
        ctx.strokeStyle = "black";
        ctx.strokeText(timestamp, textX, textY);

        ctx.lineWidth = 4;
        ctx.strokeStyle = "white";
        ctx.strokeText(timestamp, textX, textY);

        ctx.lineWidth = 2;
        ctx.strokeStyle = "black";
        ctx.strokeText(timestamp, textX, textY);

        // Final white text
        ctx.fillStyle = "white";
        ctx.fillText(timestamp, textX, textY);

        // Add location stamp
        const locationText = currentLocation.value;
        ctx.font = `${Math.floor(fontSize * 0.8)}px Arial`;
        const locationY = textY + fontSize + 5;

        // Location background
        const locTextMetrics = ctx.measureText(locationText);
        const locBgWidth = locTextMetrics.width + 16;
        const locBgHeight = Math.floor(fontSize * 0.8) + 16;

        ctx.fillStyle = "rgba(0, 100, 200, 0.8)";
        ctx.fillRect(
            textX,
            locationY - Math.floor(fontSize * 0.8) - 8,
            locBgWidth,
            locBgHeight
        );

        ctx.strokeStyle = "white";
        ctx.lineWidth = 1;
        ctx.strokeRect(
            textX,
            locationY - Math.floor(fontSize * 0.8) - 8,
            locBgWidth,
            locBgHeight
        );

        // Location text
        ctx.fillStyle = "white";
        ctx.fillText(locationText, textX + 8, locationY);

        const blob = await new Promise((resolve) => {
            canvas.toBlob(resolve, "image/jpeg", 0.95);
        });

        if (!blob) {
            throw new Error("Failed to create image blob");
        }

        if (blob.size > MAX_PHOTO_SIZE) {
            throw new Error("Photo size too large");
        }

        const filename = `water-report-${Date.now()}.jpg`;
        const file = new File([blob], filename, {
            type: "image/jpeg",
            lastModified: Date.now(),
        });

        form.photos.push(file);
        form.photo_previews.push(URL.createObjectURL(blob));

        console.log(
            `Photo ${
                form.photos.filter((file) => file.type.startsWith("image"))
                    .length
            } captured successfully`
        );

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: "success",
            title: `Photo ${
                form.photos.filter((file) => file.type.startsWith("image"))
                    .length
            } captured!`,
        });
    } catch (error) {
        console.error("Photo capture failed:", error);

        Swal.fire({
            icon: "error",
            title: "Capture Failed",
            text: error.message || "Failed to capture photo. Please try again.",
            timer: 3000,
        });
    } finally {
        isCapturing.value = false;
    }
};

const removeMedia = (index) => {
    if (index >= 0 && index < form.photo_previews.length) {
        URL.revokeObjectURL(form.photo_previews[index]);
        form.photos.splice(index, 1);
        form.photo_previews.splice(index, 1);
        console.log(`Media ${index + 1} removed`);
    }
};

const clearAllMedia = () => {
    const totalMedia = form.photos.length;
    if (totalMedia === 0) return;

    Swal.fire({
        title: "Clear All Media?",
        text: `This will remove all ${form.photos.length} media files.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Clear All",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Cleanup all preview URLs
            form.photo_previews.forEach((url) => URL.revokeObjectURL(url));

            // Clear arrays
            form.photos = [];
            form.photo_previews = [];
            form.videos = [];
            form.video_previews = [];

            Swal.fire({
                icon: "success",
                title: "Media Cleared",
                toast: true,
                position: "top-end",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    });
};

// Cleanup on unmount
onUnmounted(() => {
    stopCamera();

    // Cleanup all preview URLs
    form.photo_previews.forEach((url) => {
        URL.revokeObjectURL(url);
    });

    if (progressInterval) {
        clearInterval(progressInterval);
    }

    if (recordingInterval) {
        clearInterval(recordingInterval);
    }

    if (timeUpdateInterval) {
        clearInterval(timeUpdateInterval);
    }

    console.log("Component unmounted, resources cleaned up");
});

// Watch for barangay changes
watch(
    () => form.barangay,
    (newBarangay) => {
        form.zone = barangayToZone.value[newBarangay] || "";
        console.log("Barangay changed, zone set to:", form.zone);
    }
);

const restrictPhoneInput = (event) => {
    let value = event.target.value.replace(/[^0-9]/g, "");
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    form.reporter_phone = value;
};

const submitReport = () => {
    console.log(
        "Submitting report with",
        form.photos.length,
        "media files (",
        form.photos.filter((file) => file.type.startsWith("image")).length,
        "photos,",
        form.photos.filter((file) => file.type.startsWith("video")).length,
        "videos)"
    );

    if (form.photos.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Media Required",
            text: "Please capture at least one photo or video for your report.",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    if (locationStatus.value !== "success") {
        Swal.fire({
            icon: "error",
            title: "Location Required",
            text: "Please enable GPS/location services to submit your report.",
            confirmButtonColor: "#3085d6",
        });
        getLocation();
        return;
    }

    if (isRecording.value) {
        Swal.fire({
            icon: "warning",
            title: "Recording in Progress",
            text: "Please stop the video recording before submitting your report.",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    isSubmitting.value = true;

    // Add a 2-second loading delay
    setTimeout(() => {
        form.post(route("reports.store"), {
            preserveScroll: true,
            onSuccess: (response) => {
                isSubmitting.value = false;
                console.log("Report submitted successfully");

                if (
                    response.props.error &&
                    response.props.error.includes("daily report limit")
                ) {
                    Swal.fire({
                        icon: "error",
                        title: "Submission Failed",
                        text: response.props.error,
                        confirmButtonColor: "#3085d6",
                    });
                    return;
                }

                emit("update:successData", {
                    trackingCode: response.props.trackingCode,
                    dateSubmitted: response.props.dateSubmitted,
                });
                emit("update:showSuccessModal", true);

                Swal.fire({
                    position: "top-end",
                    title: "Report Submitted Successfully!",
                    text: `Tracking Code: ${response.props.trackingCode}`,
                    icon: "success",
                    toast: true,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });

                // Reset form
                form.reset();
                form.photo_previews.forEach((url) => URL.revokeObjectURL(url));
                form.photos = [];
                form.photo_previews = [];
                form.videos = [];
                form.video_previews = [];
                locationStatus.value = "idle";
                getLocation();
            },
            onError: (errors) => {
                isSubmitting.value = false;
                console.error("Submission failed:", errors);

                let errorMessage =
                    "There was an error submitting your report. Please try again.";
                if (
                    errors.message &&
                    errors.message.includes("daily report limit")
                ) {
                    errorMessage = errors.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "Submission Failed",
                    text: errorMessage,
                    confirmButtonColor: "#3085d6",
                });
            },
        });
    }, 2000);
};

const getLocation = () => {
    if (!navigator.geolocation) {
        locationStatus.value = "error";
        Swal.fire({
            icon: "error",
            title: "Geolocation Not Supported",
            text: "Your browser does not support location services.",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    locationStatus.value = "loading";

    navigator.geolocation.getCurrentPosition(
        (position) => {
            form.latitude = position.coords.latitude;
            form.longitude = position.coords.longitude;
            locationStatus.value = "success";
            console.log("Location acquired:", {
                latitude: form.latitude,
                longitude: form.longitude,
            });
        },
        (error) => {
            locationStatus.value = "error";
            console.warn("Location error:", error);
            Swal.fire({
                icon: "error",
                title: "Location Access Denied",
                text: "Please enable GPS/location services for your browser.",
                confirmButtonColor: "#3085d6",
            });
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 300000,
        }
    );
};

onMounted(() => {
    getLocation();
    console.log("Enhanced report form component mounted with video support");
});
</script>
