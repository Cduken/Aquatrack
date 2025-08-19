<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <Transition name="fade">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" @click="$emit('close')"></div>
        </Transition>

        <div class="flex items-center justify-center min-h-screen p-4">
            <Transition name="modal">
                <div class="relative w-full max-w-lg">
                    <!-- Modal container -->
                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                        <!-- Header -->
                        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                                <v-icon name="hi-solid-user-add" scale="1.1" />
                                Register New User
                            </h3>
                            <button @click="$emit('close')" class="text-white/80 hover:text-white transition-colors">
                                <v-icon name="hi-x" scale="1.2" />
                            </button>
                        </div>

                        <!-- Form -->
                        <div class="p-6">
                            <form @submit.prevent="handleSubmit">
                                <div class="space-y-6">
                                    <!-- Name Fields -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="relative">
                                            <input id="firstName" v-model="userData.name" type="text" required
                                                class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                placeholder=" ">
                                            <label for="firstName"
                                                class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                First Name
                                            </label>
                                            <div
                                                class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-user" />
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="lastName" v-model="userData.lastname" type="text" required
                                                class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                placeholder=" " @input="updateGeneratedPassword">
                                            <label for="lastName"
                                                class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                Last Name
                                            </label>
                                            <div
                                                class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-user" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Number -->
                                    <div class="relative">
                                        <input id="accountNumber" v-model="userData.accountNumber" type="text" required
                                            class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                            placeholder=" " @input="formatAccountNumber">
                                        <label for="accountNumber"
                                            class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                            Account Number
                                        </label>
                                        <div
                                            class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                            <v-icon name="hi-identification" />
                                        </div>
                                    </div>

                                    <!-- Email and Phone -->
                                    <div class="relative">
                                        <input id="email" v-model="userData.email" type="email" required
                                            class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                            placeholder=" ">
                                        <label for="email"
                                            class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                            Email
                                        </label>
                                        <div
                                            class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                            <v-icon name="hi-mail" />
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input id="phone" v-model="userData.phone" type="tel"
                                            class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                            placeholder=" " @input="validatePhoneNumber">
                                        <label for="phone"
                                            class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                            Phone
                                        </label>
                                        <div
                                            class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                            <v-icon name="hi-phone" />
                                        </div>
                                    </div>

                                    <!-- Role Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-blue-600 mb-2">Role</label>
                                        <div class="flex space-x-6">
                                            <label class="inline-flex items-center space-x-2 cursor-pointer group">
                                                <div class="relative">
                                                    <input type="radio" v-model="userData.role" value="staff"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white
                                                        peer-checked:border-blue-500 peer-focus:ring-2 peer-focus:ring-blue-200
                                                        flex items-center justify-center transition-all duration-200 group-hover:border-blue-400">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 scale-0
                                                            peer-checked:scale-100 transition-transform duration-200">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-gray-700 group-hover:text-blue-600 transition-colors flex items-center gap-1">
                                                    <v-icon name="hi-user-group"
                                                        class="text-gray-500 group-hover:text-blue-500" scale="0.9" />
                                                    Staff
                                                </span>
                                            </label>
                                            <label class="inline-flex items-center space-x-2 cursor-pointer group">
                                                <div class="relative">
                                                    <input type="radio" v-model="userData.role" value="customer"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white
                                                        peer-checked:border-blue-500 peer-focus:ring-2 peer-focus:ring-blue-200
                                                        flex items-center justify-center transition-all duration-200 group-hover:border-blue-400">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 scale-0
                                                            peer-checked:scale-100 transition-transform duration-200">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-gray-700 group-hover:text-blue-600 transition-colors flex items-center gap-1">
                                                    <v-icon name="hi-users"
                                                        class="text-gray-500 group-hover:text-blue-500" scale="0.9" />
                                                    Customer
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Zone and Barangay -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="relative">
                                            <select id="zone" v-model="userData.zone" @change="updateBarangays" required
                                                class="w-full pl-11 pr-10 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer appearance-none transition-all duration-200"
                                                placeholder=" ">
                                                <option value="" disabled selected hidden></option>
                                                <option v-for="(zone, index) in Object.keys(zones)" :key="index"
                                                    :value="zone">
                                                    {{ zone }}
                                                </option>
                                            </select>
                                            <label for="zone"
                                                class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                Zone
                                            </label>
                                            <div
                                                class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-location-marker" />
                                            </div>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-chevron-down"
                                                    class="transition-transform duration-200 peer-focus:rotate-180" />
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <select id="barangay" v-model="userData.barangay" required
                                                class="w-full pl-11 pr-10 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer appearance-none transition-all duration-200"
                                                :disabled="!userData.zone" placeholder=" ">
                                                <option value="" disabled selected hidden></option>
                                                <option v-for="(barangay, index) in filteredBarangays" :key="index"
                                                    :value="barangay">
                                                    {{ barangay }}
                                                </option>
                                            </select>
                                            <label for="barangay"
                                                class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                Barangay
                                            </label>
                                            <div
                                                class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-map" />
                                            </div>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 peer-focus:text-blue-500 transition-colors">
                                                <v-icon name="hi-chevron-down"
                                                    class="transition-transform duration-200 peer-focus:rotate-180" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Generated Password Preview -->
                                    <Transition name="fade">
                                        <div v-if="userData.lastname"
                                            class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                            <div class="flex items-center gap-3">
                                                <v-icon name="hi-key" class="text-blue-500" scale="1.1" />
                                                <div>
                                                    <p class="text-sm font-medium text-blue-600">Generated Password
                                                        Preview</p>
                                                    <p class="text-sm text-gray-700 mt-1">
                                                        {{ generatedPasswordPreview }}_XXXX
                                                        <span class="text-xs text-gray-500">(XXXX will be replaced with
                                                            user
                                                            ID after creation)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-4 border-t border-gray-200">
                            <button type="button" @click="$emit('close')"
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200 flex items-center gap-2">
                                <v-icon name="hi-x" scale="0.9" />
                                Cancel
                            </button>
                            <button type="button" @click="handleSubmit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 shadow-sm transition-all duration-200 flex items-center justify-center gap-2 min-w-[120px]"
                                :disabled="isSubmitting">
                                <template v-if="isSubmitting">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Creating...
                                </template>
                                <template v-else>
                                    <v-icon name="hi-check" scale="0.9" />
                                    Create User
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    show: Boolean,
    zones: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close', 'submit']);

const userData = ref({
    name: '',
    lastname: '',
    accountNumber: '',
    email: '',
    phone: '',
    role: 'customer',
    zone: '',
    barangay: '',
});

const isSubmitting = ref(false);
const filteredBarangays = ref([]);
const rawAccountNumber = ref(''); // Store the unformatted account number

const updateBarangays = () => {
    if (userData.value.zone && props.zones[userData.value.zone]) {
        filteredBarangays.value = props.zones[userData.value.zone];
    } else {
        filteredBarangays.value = [];
    }
    userData.value.barangay = '';
};

// Format account number as 000-00-000
const formatAccountNumber = (e) => {
    // Remove all non-digit characters
    let value = e.target.value.replace(/\D/g, '');
    rawAccountNumber.value = value; // Store the raw value

    // Apply formatting
    if (value.length > 5) {
        value = value.substring(0, 3) + '-' + value.substring(3, 5) + '-' + value.substring(5, 8);
    } else if (value.length > 3) {
        value = value.substring(0, 3) + '-' + value.substring(3, 5);
    }

    userData.value.accountNumber = value.substring(0, 11);
};


// Validate phone number (numbers only)
// Validate phone number (Philippine format)
const validatePhoneNumber = (e) => {
    let value = e.target.value;

    // Remove all non-digit characters except +
    value = value.replace(/[^0-9+]/g, '');

    // Ensure it starts with + if present
    if (value.startsWith('+')) {
        // Limit to 1 + and 11 digits (e.g., +639123456789)
        value = '+' + value.substring(1).replace(/\D/g, '').substring(0, 11);
    } else {
        // For numbers without +, limit to 11 digits (e.g., 09123456789)
        value = value.substring(0, 11);
    }

    userData.value.phone = value;
};

// Generated password preview
const generatedPasswordPreview = computed(() => {
    if (userData.value.lastname.length >= 3) {
        return userData.value.lastname.substring(0, 3).toUpperCase();
    }
    return userData.value.lastname.toUpperCase();
});

const updateGeneratedPassword = () => {
    // This is just for visual feedback, the actual password is generated in the backend
};

const resetForm = () => {
    userData.value = {
        name: '',
        lastname: '',
        accountNumber: '',
        email: '',
        phone: '',
        role: 'customer',
        zone: '',
        barangay: '',
    };
    rawAccountNumber.value = '';
    filteredBarangays.value = [];
};

const handleSubmit = async () => {
    isSubmitting.value = true;

    // Prepare data with properly formatted account number
    const submitData = {
        ...userData.value,
        account_number: userData.value.accountNumber, // Send the FORMATTED value
        phone: userData.value.phone.replace(/\D/g, '')
    };

    console.log('Submitting:', submitData); // Debug log

    await new Promise(resolve => setTimeout(resolve, 2000));
    emit('submit', submitData);
    resetForm();
    isSubmitting.value = false;
};
</script>

<style scoped>
/* Transition effects */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Input styles */
input:focus~label,
input:not(:placeholder-shown)~label,
select:focus~label,
select:not(:placeholder-shown)~label {
    transform: translateY(-1.5rem) scale(0.75);
    color: #2563eb;
}

input::placeholder,
select::placeholder {
    color: transparent;
}

/* Custom radio buttons */
input[type="radio"]:checked~div>div {
    transform: scale(1);
}

/* Select dropdown styling */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

select:disabled {
    @apply bg-gray-100 cursor-not-allowed;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23d1d5db' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}

select:disabled~label {
    @apply text-gray-400;
}

/* Spinner animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}
</style>
