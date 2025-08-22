<template>
    <div v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto overflow-x-hidden flex justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="fixed inset-0 bg-gray-900/75" aria-hidden="true" @click="$emit('close')"></div>
        <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
            <!-- Modal content -->
            <div
                class="relative bg-white rounded-lg shadow-lg dark:bg-gray-800 sm:p-5 transform transition-all duration-300 ease-out scale-100 overflow-y-auto max-h-[80vh] scrollbar-hide">
                <!-- Modal header -->
                <div class="flex justify-between items-center rounded-t border-b dark:border-gray-600 mb-4  bg-white dark:bg-gray-800"
                    style="margin-top:0;padding-top:0;padding-bottom:1rem;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <v-icon name="hi-user" class="w-5 h-5" />
                        Register New User
                    </h3>
                    <button @click="$emit('close')" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <v-icon name="hi-x" class="w-5 h-5" />
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="handleSubmit">
                    <div class="grid gap-3 mb-4 sm:grid-cols-2">
                        <!-- Personal Information Section -->
                        <div class="sm:col-span-2">
                            <h4
                                class="text-base font-medium text-gray-900 dark:text-white flex items-center gap-2 mb-2">
                                <v-icon name="hi-user" class="w-5 h-5" />
                                Personal Information
                            </h4>
                        </div>
                        <div>
                            <label for="firstName"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-user" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="firstName" v-model="userData.name" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="First name">
                            </div>
                        </div>
                        <div>
                            <label for="lastName"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-user" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="lastName" v-model="userData.lastname" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Last name" @input="updateGeneratedPassword">
                            </div>
                        </div>
                        <div>
                            <label for="accountNumber"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Account
                                Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-credit-card" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="accountNumber" v-model="userData.accountNumber" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="123-45-678" @input="formatAccountNumber">
                            </div>
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-mail" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="email" v-model="userData.email" type="email" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="name@company.com">
                            </div>
                        </div>
                        <div>
                            <label for="phone"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-phone" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="phone" v-model="userData.phone" type="tel" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="+12345678901" @input="validatePhoneNumber">
                            </div>
                        </div>

                        <!-- Equipment Information Section -->
                        <div class="sm:col-span-2 mt-3">
                            <h4
                                class="text-base font-medium text-gray-900 dark:text-white flex items-center gap-2 mb-2">
                                <v-icon name="hi-cube" class="w-5 h-5" />
                                Equipment Information
                            </h4>
                        </div>
                        <div>
                            <label for="dateInstalled"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Date
                                Installed</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-calendar" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="dateInstalled" v-model="userData.date_installed" type="date" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>
                        </div>
                        <div>
                            <label for="brand"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-tag" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="brand" v-model="userData.brand" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Brand name">
                            </div>
                        </div>
                        <div>
                            <label for="serialNumber"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Serial
                                Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-document-text" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="serialNumber" v-model="userData.serial_number" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 pr-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="123456789" @input="validateSerialNumber" maxlength="9">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-500">
                                    {{ userData.serial_number ? userData.serial_number.length : 0 }}/9
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="size"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Size</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-arrows-expand" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input id="size" v-model="userData.size" type="text" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Size">
                            </div>
                        </div>

                        <!-- Location and Role Section -->
                        <div class="sm:col-span-2 mt-3">
                            <h4
                                class="text-base font-medium text-gray-900 dark:text-white flex items-center gap-2 mb-2">
                                <v-icon name="hi-location-marker" class="w-5 h-5" />
                                Location & Role
                            </h4>
                        </div>
                        <div>
                            <label for="zone"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Zone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-location-marker"
                                        class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <select id="zone" v-model="userData.zone" @change="updateBarangays" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="" disabled selected>Select zone</option>
                                    <option v-for="(zone, index) in Object.keys(zones)" :key="index" :value="zone">
                                        {{ zone }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="barangay"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Barangay</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <v-icon name="hi-location-marker"
                                        class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <select id="barangay" v-model="userData.barangay" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    :disabled="!userData.zone">
                                    <option value="" disabled selected>Select barangay</option>
                                    <option v-for="(barangay, index) in filteredBarangays" :key="index"
                                        :value="barangay">
                                        {{ barangay }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" v-model="userData.role" value="staff"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="text-gray-900 dark:text-white flex items-center gap-1">
                                        <v-icon name="hi-user-group" class="w-4 h-4" />
                                        Staff
                                    </span>
                                </label>
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" v-model="userData.role" value="customer"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="text-gray-900 dark:text-white flex items-center gap-1">
                                        <v-icon name="hi-user" class="w-4 h-4" />
                                        Customer
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Generated Password Preview -->
                        <Transition name="fade">
                            <div v-if="userData.lastname"
                                class="sm:col-span-2 p-3 rounded-lg border border-blue-200 bg-blue-100 dark:bg-gray-700 dark:border-gray-600">
                                <div class="flex items-center gap-2">
                                    <v-icon name="hi-key" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                                    <div>
                                        <p class="text-sm font-medium text-blue-700 dark:text-white">Generated Password
                                            Preview</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-mono">
                                            {{ generatedPasswordPreview }}_XXXX
                                            <span class="text-xs text-gray-500 dark:text-gray-400">(XXXX will be
                                                replaced with user ID after creation)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center space-x-2 p-4 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                        <div class="ml-[-15px] flex items-center gap-4">
                        <button type="button" @click="$emit('close')"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-4 py-2 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center gap-2"
                            :disabled="isSubmitting">
                            <template v-if="isSubmitting">
                                <v-icon name="hi-refresh" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" />
                                Creating...
                            </template>
                            <template v-else>
                                <v-icon name="hi-plus" class="mr-1 -ml-1 w-5 h-5" />
                                Create User
                            </template>
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { OhVueIcon, addIcons } from "oh-vue-icons";
import {
    HiUser, HiX, HiCreditCard, HiMail, HiPhone, HiCube,
    HiCalendar, HiTag, HiDocumentText, HiArrowsExpand,
    HiLocationMarker, HiUserGroup, HiKey, HiRefresh, HiPlus
} from "oh-vue-icons/icons";

addIcons(
    HiUser, HiX, HiCreditCard, HiMail, HiPhone, HiCube,
    HiCalendar, HiTag, HiDocumentText, HiArrowsExpand,
    HiLocationMarker, HiUserGroup, HiKey, HiRefresh, HiPlus
);

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

const validateSerialNumber = (e) => {
    let value = e.target.value;
    value = value.replace(/\D/g, '');
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

    emit('submit', submitData);
    resetForm();
    isSubmitting.value = false;
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Modal animation */
.modal-enter-active {
    animation: slideDown 0.3s ease-out;
}

.modal-leave-active {
    animation: slideUp 0.2s ease-in;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px) scale(0.9);
        opacity: 0;
    }

    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    to {
        transform: translateY(-50px) scale(0.9);
        opacity: 0;
    }
}

/* Hide scrollbar but allow scrolling */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

/* Fix sticky header flush to top */
:deep(.sticky.top-0) {
    top: 0 !important;
    margin-top: 0 !important;
    padding-top:
        0 !important;
}
</style>
