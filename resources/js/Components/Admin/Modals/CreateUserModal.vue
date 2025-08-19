<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <Transition name="fade">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" @click="$emit('close')"></div>
        </Transition>

        <div class="flex items-center justify-center min-h-screen p-4">
            <Transition name="modal">
                <div class="relative w-full max-w-4xl"> <!-- Increased width -->
                    <!-- Modal container -->
                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]"> <!-- Added height constraint -->
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                                <v-icon name="hi-solid-user-add" scale="1.1" />
                                Register New User
                            </h3>
                            <button @click="$emit('close')" class="text-white/80 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10">
                                <v-icon name="hi-x" scale="1.2" />
                            </button>
                        </div>

                        <!-- Form -->
                        <div class="p-6 overflow-y-auto flex-1">
                            <form @submit.prevent="handleSubmit">
                                <div class="space-y-6">
                                    <!-- Personal Information Section -->
                                    <div class="border-b border-gray-200 pb-4">
                                        <h4 class="text-lg font-medium text-blue-700 flex items-center gap-2 mb-4">
                                            <v-icon name="hi-user-circle" class="text-blue-500" />
                                            Personal Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="relative">
                                                <input id="firstName" v-model="userData.name" type="text" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
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
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
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
                                        <div class="mt-6 relative">
                                            <input id="accountNumber" v-model="userData.accountNumber" type="text" required
                                                class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
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
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                            <div class="relative">
                                                <input id="email" v-model="userData.email" type="email" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
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
                                                <input id="phone" v-model="userData.phone" type="tel" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
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
                                        </div>
                                    </div>

                                    <!-- Equipment Information Section -->
                                    <div class="border-b border-gray-200 pb-4">
                                        <h4 class="text-lg font-medium text-blue-700 flex items-center gap-2 mb-4">
                                            <v-icon name="hi-cube" class="text-blue-500" />
                                            Equipment Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="relative">
                                                <input id="dateInstalled" v-model="userData.date_installed" type="date" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                    placeholder=" ">
                                                <label for="dateInstalled"
                                                    class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                    Date Installed
                                                </label>
                                                <div
                                                    class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                    <v-icon name="hi-calendar" />
                                                </div>
                                            </div>
                                            <div class="relative">
                                                <input id="brand" v-model="userData.brand" type="text" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                    placeholder=" ">
                                                <label for="brand"
                                                    class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                    Brand
                                                </label>
                                                <div
                                                    class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                    <v-icon name="hi-tag" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                            <div class="relative">
                                                <input id="serialNumber" v-model="userData.serial_number" type="text" required
                                                    class="w-full pl-11 pr-12 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                    placeholder=" " @input="validateSerialNumber"
                                                    maxlength="9">
                                                <label for="serialNumber"
                                                    class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                    Serial Number
                                                </label>
                                                <div
                                                    class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                    <v-icon name="hi-code" />
                                                </div>
                                                <div class="absolute right-3 top-3.5 text-xs text-gray-400">
                                                    {{ userData.serial_number ? userData.serial_number.length : 0 }}/9
                                                </div>
                                            </div>
                                            <div class="relative">
                                                <input id="size" v-model="userData.size" type="text" required
                                                    class="w-full pl-11 pr-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer transition-all duration-200"
                                                    placeholder=" ">
                                                <label for="size"
                                                    class="absolute left-11 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                                                    Size
                                                </label>
                                                <div
                                                    class="absolute left-3 top-3.5 text-gray-400 peer-focus:text-blue-500 transition-colors">
                                                    <v-icon name="hi-arrows-expand" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location and Role Section -->
                                    <div>
                                        <h4 class="text-lg font-medium text-blue-700 flex items-center gap-2 mb-4">
                                            <v-icon name="hi-location-marker" class="text-blue-500" />
                                            Location & Role
                                        </h4>
                                        <!-- Zone and Barangay -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="relative">
                                                <select id="zone" v-model="userData.zone" @change="updateBarangays" required
                                                    class="w-full pl-11 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer appearance-none transition-all duration-200"
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
                                                    class="w-full pl-11 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 peer appearance-none transition-all duration-200"
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

                                        <!-- Role Selection -->
                                        <div class="space-y-2 mt-6">
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
                                    </div>

                                    <!-- Generated Password Preview -->
                                    <Transition name="fade">
                                        <div v-if="userData.lastname"
                                            class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200 shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <v-icon name="hi-key" class="text-blue-500" scale="1.1" />
                                                <div>
                                                    <p class="text-sm font-medium text-blue-600">Generated Password
                                                        Preview</p>
                                                    <p class="text-sm text-gray-700 mt-1 font-mono">
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
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 shadow-sm transition-all duration-200 flex items-center justify-center gap-2 min-w-[120px]"
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
    date_installed: '',
    brand: '',
    serial_number: '',
    size: ''
});

const isSubmitting = ref(false);
const filteredBarangays = ref([]);
const rawAccountNumber = ref('');

const updateBarangays = () => {
    if (userData.value.zone && props.zones[userData.value.zone]) {
        filteredBarangays.value = props.zones[userData.value.zone];
    } else {
        filteredBarangays.value = [];
    }
    userData.value.barangay = '';
};

const formatAccountNumber = (e) => {
    let value = e.target.value.replace(/\D/g, '');
    rawAccountNumber.value = value;

    if (value.length > 5) {
        value = value.substring(0, 3) + '-' + value.substring(3, 5) + '-' + value.substring(5, 8);
    } else if (value.length > 3) {
        value = value.substring(0, 3) + '-' + value.substring(3, 5);
    }

    userData.value.accountNumber = value.substring(0, 11);
};

const validatePhoneNumber = (e) => {
    let value = e.target.value;
    value = value.replace(/[^0-9+]/g, '');

    if (value.startsWith('+')) {
        value = '+' + value.substring(1).replace(/\D/g, '').substring(0, 11);
    } else {
        value = value.substring(0, 11);
    }

    userData.value.phone = value;
};

// Validate serial number (only digits, exactly 9 characters)
const validateSerialNumber = (e) => {
    let value = e.target.value;
    // Remove all non-digit characters
    value = value.replace(/\D/g, '');
    // Limit to 9 digits
    value = value.substring(0, 9);
    userData.value.serial_number = value;
};

const generatedPasswordPreview = computed(() => {
    if (userData.value.lastname.length >= 3) {
        return userData.value.lastname.substring(0, 3).toUpperCase();
    }
    return userData.value.lastname.toUpperCase();
});

const updateGeneratedPassword = () => {
    // For visual feedback only
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
        date_installed: '',
        brand: '',
        serial_number: '',
        size: ''
    };
    rawAccountNumber.value = '';
    filteredBarangays.value = [];
};

const handleSubmit = async () => {
    isSubmitting.value = true;

    // Validate serial number length
    if (userData.value.serial_number.length !== 9) {
        alert('Serial number must be exactly 9 digits');
        isSubmitting.value = false;
        return;
    }

    const submitData = {
        ...userData.value,
        account_number: userData.value.accountNumber,
        phone: userData.value.phone.replace(/\D/g, '')
    };

    console.log('Submitting:', submitData);
    emit('submit', submitData);
    resetForm();
    isSubmitting.value = false;
};
</script>

<style scoped>
/* Your existing styles remain the same */
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

input[type="radio"]:checked~div>div {
    transform: scale(1);
}

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
