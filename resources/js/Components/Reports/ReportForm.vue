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
                    <label for="zone" class="text-white text-sm"
                        >Zone <span class="text-red-500">*</span></label
                    >
                    <select
                        id="zone"
                        v-model="form.zone"
                        required
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    >
                        <option value="" disabled selected>Select Zone</option>
                        <option
                            v-for="(zone, index) in Object.keys(zones)"
                            :key="index"
                            :value="zone"
                        >
                            {{ zone }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.zone"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.zone }}
                    </p>
                </div>

                <div>
                    <label for="barangay" class="text-white text-sm"
                        >Barangay <span class="text-red-500">*</span></label
                    >
                    <select
                        id="barangay"
                        v-model="form.barangay"
                        required
                        :disabled="!form.zone"
                        class="w-full p-1.5 mt-1 rounded-md text-white bg-[#4E6F96] border-gray-400 focus:border-white focus:ring-1 focus:ring-white text-sm"
                    >
                        <option value="" disabled selected>
                            Select Barangay
                        </option>
                        <option
                            v-for="barangay in filteredBarangays"
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

            <div class="mt-4">
                <label class="text-white text-sm">
                    Capture Photos/Videos
                    <span class="text-red-500">*</span>
                </label>
                <div class="mt-1">
                    <div v-if="!isCameraActive" class="flex flex-col gap-3">
                        <button
                            type="button"
                            @click="startCamera"
                            :disabled="isCameraLoading"
                            class="flex items-center justify-center p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <v-icon name="bi-camera" class="mr-2" />
                            {{
                                isCameraLoading
                                    ? "Opening Camera..."
                                    : "Open Camera"
                            }}
                        </button>
                        <p class="text-xs text-gray-400 text-center">
                            You need to capture at least one photo for your
                            report
                        </p>
                    </div>

                    <div
                        v-else
                        class="camera-container bg-black rounded-md overflow-hidden"
                    >
                        <div class="relative w-full" style="height: 320px">
                            <video
                                id="webcam"
                                class="absolute inset-0 w-full h-full object-cover"
                                autoplay
                                playsinline
                                ref="webcamRef"
                            ></video>
                            <!-- Loading overlay -->
                            <div
                                v-if="!isCameraReady"
                                class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center"
                            >
                                <div class="text-white text-center">
                                    <div
                                        class="animate-spin w-8 h-8 border-2 border-white border-t-transparent rounded-full mx-auto mb-2"
                                    ></div>
                                    <p class="text-sm">
                                        Initializing camera...
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="camera-controls p-3 bg-gray-900 flex justify-center gap-4"
                        >
                            <button
                                type="button"
                                @click="capturePhoto"
                                class="p-3 bg-white rounded-full hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="
                                    !isCameraReady ||
                                    form.photos.length >= MAX_PHOTOS
                                "
                            >
                                <v-icon
                                    name="bi-camera"
                                    class="text-2xl text-gray-800"
                                />
                            </button>
                            <button
                                type="button"
                                @click="switchCamera"
                                class="p-3 bg-gray-700 rounded-full hover:bg-gray-600 transition-colors text-white disabled:opacity-50"
                                v-if="hasMultipleCameras"
                                :disabled="!isCameraReady"
                            >
                                <v-icon name="bi-arrow-repeat" />
                            </button>
                            <button
                                type="button"
                                @click="stopCamera"
                                class="p-3 bg-red-600 rounded-full hover:bg-red-700 transition-colors text-white"
                            >
                                <v-icon name="bi-x-lg" />
                            </button>
                        </div>
                    </div>

                    <div v-if="form.photo_previews.length > 0" class="mt-3">
                        <p class="text-xs text-gray-300 mb-2">
                            Captured photos ({{ form.photo_previews.length }} of
                            {{ MAX_PHOTOS }})
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <div
                                v-for="(preview, index) in form.photo_previews"
                                :key="index"
                                class="relative group"
                            >
                                <img
                                    :src="preview"
                                    class="h-20 w-full object-cover rounded border border-gray-200"
                                    :alt="'Captured photo ' + (index + 1)"
                                />
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200"
                                >
                                    <button
                                        @click="removePhoto(index)"
                                        type="button"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                                    >
                                        <v-icon name="bi-x-lg" scale="0.7" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-if="form.errors.photos" class="mt-1 text-xs text-red-400">
                    {{ form.errors.photos }}
                </p>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    :disabled="form.processing || isSubmitting"
                    class="w-full p-2 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed text-sm"
                >
                    <span v-if="isSubmitting" class="flex items-center">
                        <svg
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        Submitting...
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
const availableCameras = ref([]);
const currentCameraIndex = ref(0);
const webcamRef = ref(null);
const hasMultipleCameras = computed(() => availableCameras.value.length > 1);
let stream = null;

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
    latitude: null,
    longitude: null,
});

const MAX_PHOTOS = 5;
const MAX_VIDEOS = 0;
const MAX_TOTAL = MAX_PHOTOS + MAX_VIDEOS;
const MAX_PHOTO_SIZE = 5 * 1024 * 1024;
const MAX_VIDEO_SIZE = 15 * 1024 * 1024;

const filteredBarangays = computed(() => {
    return props.zones[form.zone] || [];
});

const hasErrors = computed(() => {
    return Object.keys(form.errors).length > 0;
});

const getCameras = async () => {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        availableCameras.value = devices.filter(
            (device) => device.kind === "videoinput"
        );
        console.log("Available cameras:", availableCameras.value);
        return availableCameras.value;
    } catch (error) {
        console.error("Error getting cameras:", error);
        Swal.fire({
            icon: "error",
            title: "Camera Error",
            text: "Could not access camera devices.",
            confirmButtonColor: "#3085d6",
        });
        return [];
    }
};

const startCamera = async () => {
    try {
        isCameraLoading.value = true;
        isCameraReady.value = false;

        // Stop existing stream first
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }

        const cameras = await getCameras();
        if (cameras.length === 0) {
            Swal.fire({
                icon: "error",
                title: "No Camera Found",
                text: "No camera devices were found on your device.",
                confirmButtonColor: "#3085d6",
            });
            isCameraLoading.value = false;
            return;
        }

        // Set camera as active to render the video element
        isCameraActive.value = true;

        // Wait for Vue to update the DOM
        await nextTick();

        // Now try to get the webcam element
        const webcamElement = document.getElementById("webcam");
        if (!webcamElement) {
            console.error("Webcam element still not found after DOM update");
            isCameraActive.value = false;
            isCameraLoading.value = false;
            throw new Error("Webcam element not found");
        }

        // Simplified constraints for better compatibility
        const constraints = {
            video: {
                width: { ideal: 640, max: 1280 },
                height: { ideal: 480, max: 720 },
                facingMode: "user",
            },
            audio: false,
        };

        // If we have multiple cameras and not on first camera, try specific device
        if (cameras.length > 1 && currentCameraIndex.value > 0) {
            constraints.video.deviceId = {
                exact: cameras[currentCameraIndex.value].deviceId,
            };
            delete constraints.video.facingMode;
        }

        console.log("Starting camera with constraints:", constraints);
        stream = await navigator.mediaDevices.getUserMedia(constraints);

        // Clear any existing src
        webcamElement.srcObject = null;

        // Set the new stream
        webcamElement.srcObject = stream;

        // Force attributes
        webcamElement.autoplay = true;
        webcamElement.playsInline = true;
        webcamElement.muted = true;

        // Wait for video to be ready with better error handling
        await new Promise((resolve, reject) => {
            let resolved = false;

            const onLoadedData = () => {
                if (!resolved) {
                    resolved = true;
                    webcamElement.removeEventListener(
                        "loadeddata",
                        onLoadedData
                    );
                    webcamElement.removeEventListener("error", onError);
                    console.log("Video metadata loaded successfully");
                    resolve();
                }
            };

            const onError = (e) => {
                if (!resolved) {
                    resolved = true;
                    webcamElement.removeEventListener(
                        "loadeddata",
                        onLoadedData
                    );
                    webcamElement.removeEventListener("error", onError);
                    console.error("Video error:", e);
                    reject(new Error("Video failed to load"));
                }
            };

            webcamElement.addEventListener("loadeddata", onLoadedData);
            webcamElement.addEventListener("error", onError);

            // Try to play immediately
            const playPromise = webcamElement.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log("Video play started successfully");
                        if (!resolved) {
                            resolved = true;
                            webcamElement.removeEventListener(
                                "loadeddata",
                                onLoadedData
                            );
                            webcamElement.removeEventListener("error", onError);
                            resolve();
                        }
                    })
                    .catch((playError) => {
                        console.error("Play error:", playError);
                        // Don't reject on play error, wait for loadeddata instead
                    });
            }

            // Fallback timeout
            setTimeout(() => {
                if (!resolved) {
                    resolved = true;
                    webcamElement.removeEventListener(
                        "loadeddata",
                        onLoadedData
                    );
                    webcamElement.removeEventListener("error", onError);

                    if (
                        webcamElement.videoWidth > 0 &&
                        webcamElement.videoHeight > 0
                    ) {
                        console.log("Video ready via timeout check");
                        resolve();
                    } else {
                        reject(new Error("Video initialization timeout"));
                    }
                }
            }, 5000);
        });

        isCameraReady.value = true;
        isCameraLoading.value = false;
        console.log(
            "Camera started successfully, dimensions:",
            webcamElement.videoWidth,
            "x",
            webcamElement.videoHeight
        );
    } catch (error) {
        console.error("Error starting camera:", error);
        isCameraLoading.value = false;
        isCameraReady.value = false;
        isCameraActive.value = false;

        // Clean up stream on error
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }

        let errorMessage = "Could not initialize the camera.";
        if (error.name === "NotAllowedError") {
            errorMessage =
                "Camera access was denied. Please allow camera permissions and refresh the page.";
        } else if (error.name === "NotFoundError") {
            errorMessage = "No camera device found on your device.";
        } else if (error.name === "NotReadableError") {
            errorMessage =
                "Camera is being used by another application. Please close other camera apps.";
        } else if (error.name === "OverconstrainedError") {
            errorMessage =
                "Camera constraints not supported. Trying with basic settings...";
            // Retry with minimal constraints
            setTimeout(() => startCameraWithMinimalConstraints(), 1000);
            return;
        }

        Swal.fire({
            icon: "error",
            title: "Camera Error",
            text: errorMessage,
            confirmButtonColor: "#3085d6",
        });
    }
};

const startCameraWithMinimalConstraints = async () => {
    try {
        console.log("Retrying with minimal constraints");
        const constraints = { video: true, audio: false };

        stream = await navigator.mediaDevices.getUserMedia(constraints);
        isCameraActive.value = true;

        const webcamElement = document.getElementById("webcam");
        if (webcamElement) {
            webcamElement.srcObject = stream;
            webcamElement.autoplay = true;
            webcamElement.playsInline = true;
            webcamElement.muted = true;

            await webcamElement.play();
            isCameraReady.value = true;
            console.log("Camera started with minimal constraints");
        }
    } catch (retryError) {
        console.error("Retry failed:", retryError);
        Swal.fire({
            icon: "error",
            title: "Camera Initialization Failed",
            text: "Unable to access camera with any settings.",
            confirmButtonColor: "#3085d6",
        });
    }
};

const stopCamera = () => {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        const webcamElement = document.getElementById("webcam");
        if (webcamElement) {
            webcamElement.srcObject = null;
        }
        console.log("Camera stopped.");
    }
    isCameraActive.value = false;
    isCameraReady.value = false;
    isCameraLoading.value = false;
    stream = null;
};

const switchCamera = async () => {
    if (availableCameras.value.length <= 1) {
        Swal.fire({
            icon: "info",
            title: "Single Camera",
            text: "Only one camera detected on your device.",
            confirmButtonColor: "#3085d6",
            timer: 1500,
        });
        return;
    }

    console.log("Switching camera from index:", currentCameraIndex.value);

    // Show loading state
    isCameraReady.value = false;

    // Stop current stream
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
    }

    // Switch to next camera
    currentCameraIndex.value =
        (currentCameraIndex.value + 1) % availableCameras.value.length;
    console.log("Switching to camera index:", currentCameraIndex.value);

    // Restart camera with new index
    await startCamera();
};

const capturePhoto = () => {
    console.log("Capturing photo, isCameraReady:", isCameraReady.value);
    if (!isCameraReady.value || form.photos.length >= MAX_PHOTOS) {
        console.warn(
            "Cannot capture photo - camera not ready or max photos reached"
        );
        return;
    }

    const webcamElement = document.getElementById("webcam");
    if (!webcamElement || !webcamElement.srcObject) {
        console.warn("Webcam element or stream not available");
        Swal.fire({
            icon: "warning",
            title: "Camera Not Ready",
            text: "Please wait for the camera to initialize.",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    try {
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");

        // Set canvas dimensions to match video dimensions
        canvas.width = webcamElement.videoWidth || 640;
        canvas.height = webcamElement.videoHeight || 480;

        // Draw the video frame
        context.drawImage(webcamElement, 0, 0, canvas.width, canvas.height);

        // Add timestamp
        const timestamp = new Date().toLocaleString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
            timeZoneName: "short",
            weekday: "long",
            month: "long",
            day: "numeric",
            year: "numeric",
        });

        context.fillStyle = "white";
        context.strokeStyle = "black";
        context.lineWidth = 2;
        context.font = "16px Arial";

        const textMetrics = context.measureText(timestamp);
        const textWidth = textMetrics.width;
        const textHeight = 20;
        const padding = 10;

        // Position timestamp at bottom-left
        const x = padding;
        const y = canvas.height - padding;

        // Draw text with outline for better visibility
        context.strokeText(timestamp, x, y);
        context.fillText(timestamp, x, y);

        console.log("Timestamp added:", timestamp);

        canvas.toBlob(
            (blob) => {
                if (!blob) {
                    console.error("Failed to convert canvas to blob.");
                    Swal.fire({
                        icon: "error",
                        title: "Capture Failed",
                        text: "Failed to capture photo. Please try again.",
                        confirmButtonColor: "#3085d6",
                    });
                    return;
                }

                console.log("Photo blob created, size:", blob.size);

                const file = new File(
                    [blob],
                    `report-photo-${Date.now()}.jpg`,
                    {
                        type: "image/jpeg",
                        lastModified: Date.now(),
                    }
                );

                form.photos.push(file);
                form.photo_previews.push(URL.createObjectURL(blob));
                console.log(
                    "Photo captured successfully, total photos:",
                    form.photos.length
                );

                // Show success feedback
                Swal.fire({
                    icon: "success",
                    title: "Photo Captured!",
                    toast: true,
                    text: `Photo ${form.photos.length} of ${MAX_PHOTOS} captured successfully.`,
                    confirmButtonColor: "#3085d6",
                    timer: 1500,
                    showConfirmButton: false,
                    position: "top-right",
                });

                if (form.photos.length >= MAX_PHOTOS) {
                    Swal.fire({
                        icon: "info",
                        title: "Photo Limit Reached",
                        text: `You've reached the maximum of ${MAX_PHOTOS} photos.`,
                        confirmButtonColor: "#3085d6",
                        timer: 2000,
                    });
                }
            },
            "image/jpeg",
            0.9
        );
    } catch (error) {
        console.error("Error capturing photo:", error);
        Swal.fire({
            icon: "error",
            title: "Capture Failed",
            text: "An error occurred while capturing the photo.",
            confirmButtonColor: "#3085d6",
        });
    }
};

onUnmounted(() => {
    stopCamera();
    // Clean up photo previews
    form.photo_previews.forEach((url) => {
        if (url && !url.includes("video-icon.png")) {
            URL.revokeObjectURL(url);
        }
    });
    console.log("Component unmounted, camera and resources cleaned up.");
});

watch(
    () => form.zone,
    () => {
        form.barangay = "";
        console.log("Zone changed, resetting barangay:", form.zone);
    }
);

const isVideoFile = (file) => {
    return file?.type?.match("video.*");
};

const restrictPhoneInput = (event) => {
    let value = event.target.value.replace(/[^0-9]/g, "");
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    form.reporter_phone = value;
    console.log("Phone input restricted:", value);
};

const removePhoto = (index) => {
    if (!isVideoFile(form.photos[index])) {
        URL.revokeObjectURL(form.photo_previews[index]);
    }
    form.photos.splice(index, 1);
    form.photo_previews.splice(index, 1);
    console.log(
        "Photo removed at index:",
        index,
        "Remaining photos:",
        form.photos.length
    );
};

const submitReport = () => {
    console.log(
        "Submitting report, photos count:",
        form.photos.length,
        "Location status:",
        locationStatus.value
    );
    if (form.photos.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Media Required",
            text: "Please upload at least one photo or video for your report.",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    if (locationStatus.value !== "success") {
        Swal.fire({
            icon: "error",
            title: "Location Required",
            text: "Please enable GPS/location services to submit.",
            confirmButtonColor: "#3085d6",
        });
        getLocation();
        return;
    }

    isSubmitting.value = true;
    console.log("Form submission started.");

    const loadingTimeout = setTimeout(() => {
        form.post(route("reports.store"), {
            preserveScroll: true,
            onSuccess: (response) => {
                isSubmitting.value = false;
                console.log("Submission success, response:", response.props);

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
                form.reset();
                form.photo_previews.forEach((url) => {
                    if (url !== "/images/video-icon.png") {
                        URL.revokeObjectURL(url);
                    }
                });
                form.photo_previews = [];
                locationStatus.value = "idle";
                getLocation();
            },
            onError: (errors) => {
                isSubmitting.value = false;
                console.error("Submission error:", errors);
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

    watch(isSubmitting, (newValue) => {
        if (!newValue) {
            clearTimeout(loadingTimeout);
            console.log("Submission cancelled or completed.");
        }
    });
};

const getLocation = () => {
    console.log("Getting location, current status:", locationStatus.value);
    if (!navigator.geolocation) {
        locationStatus.value = "error";
        Swal.fire({
            icon: "error",
            title: "Geolocation Not Supported",
            text: "Your browser does not support geolocation.",
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
            console.log("GPS acquired:", {
                latitude: form.latitude,
                longitude: form.longitude,
            });
        },
        (error) => {
            locationStatus.value = "error";
            console.log("GPS error:", error);
            Swal.fire({
                icon: "error",
                title: "Location Access Denied",
                text: "Please enable GPS/location services.",
                confirmButtonColor: "#3085d6",
            });
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
};

onMounted(() => {
    getLocation();
    console.log("Component mounted, initiating location fetch.");
});
</script>
