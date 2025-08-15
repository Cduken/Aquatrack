<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { OhVueIcon } from 'oh-vue-icons';
import Swal from 'sweetalert2';
import axios from 'axios';

const props = defineProps({
    selectedRole: {
        type: String,
        default: ''
    },
    canResetPassword: {
        type: Boolean,
        default: true
    },
    status: {
        type: String,
    },
    auth: {
        type: Object,
        default: () => ({ user: null })
    }
});

const emit = defineEmits(['close', 'login-success']);

const showPassword = ref(false);
const codeVerified = ref(false);
const verificationCode = ref('');
const verificationError = ref('');
const isVerifying = ref(false);

const securedRoles = ['admin', 'staff'];
const requiresVerification = computed(() => {
    return securedRoles.includes(props.selectedRole.toLowerCase());
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    role: props.selectedRole ? props.selectedRole.toLowerCase() : 'customer'
});

// Reset code verification when selected role changes
onMounted(() => {
    codeVerified.value = false;
    verificationCode.value = '';
});

const verifyCode = async () => {
    verificationError.value = '';
    isVerifying.value = true;
    const startTime = Date.now();

    try {
        // Get fresh CSRF token before each request
        const csrfToken = getCsrfToken();

        // Add artificial delay to ensure spinner is visible
        await Promise.all([
            axios.post(route('verify.role.code'), {
                role: props.selectedRole.toLowerCase(),
                code: verificationCode.value
            }, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }),
            new Promise(resolve => setTimeout(resolve, 2000)) // Minimum 2 second delay
        ]);

        const response = await axios.post(route('verify.role.code'), {
            role: props.selectedRole.toLowerCase(),
            code: verificationCode.value
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.verified) {
            codeVerified.value = true;
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Verification Successful!',
                text: `You can now proceed to login as ${props.selectedRole}`,
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
    } catch (error) {
        console.error('Verification error:', error);
        verificationError.value = error.response?.data?.message ||
            error.response?.data?.errors?.code?.[0] ||
            'The verification code you entered is incorrect';

        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Verification Failed',
            text: verificationError.value,
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
    } finally {
        const elapsed = Date.now() - startTime;
        const minimumDelay = 2000; // 2 seconds minimum spinner display
        if (elapsed < minimumDelay) {
            await new Promise(resolve => setTimeout(resolve, minimumDelay - elapsed));
        }
        isVerifying.value = false;
    }
};

const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]').content;
};

const submit = () => {
    form._token = getCsrfToken();

    form.post(route('login'), {
        onFinish: () => form.reset('password'),

        onSuccess: (response) => {
            const userName = response.props.auth?.user?.name || 'there';
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `Welcome back, ${userName}!`,
                text: `You've successfully logged in as ${props.selectedRole || 'customer'}`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '400px',
                customClass: {
                    title: 'text-lg font-medium'
                }
            }).then(() => {
                emit('login-success');
            });
        },
        onError: (errors) => {
            // Debug the complete error object
            console.log('Complete error object:', errors);

            // Default error structure
            let errorTitle = 'Login Failed';
            let errorMessage = 'Invalid credentials. Please try again';

            // Handle different error cases
            if (errors.errors?.email?.[0]) {
                errorMessage = errors.errors.email[0];
            }
            else if (errors.role_mismatch) {
                // Handle enhanced role mismatch error
                if (typeof errors.role_mismatch === 'object') {
                    errorTitle = errors.role_mismatch.title || errorTitle;
                    errorMessage = errors.role_mismatch.message || errorMessage;
                } else {
                    errorMessage = errors.role_mismatch;
                }
            }
            else if (errors.message) {
                errorMessage = errors.message;
            }

            console.log('Displaying error:', { title: errorTitle, message: errorMessage });

            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: errorTitle,
                html: `
                    <div class="text-left">
                        <p class="font-medium">${errorMessage}</p>
                        ${errors.role_mismatch ? `
                            <div class="mt-3 text-sm">
                                <p>Try one of these options:</p>
                                <ul class="list-disc pl-5 mt-1">
                                    <li>Login through the correct portal for your account type</li>
                                    <li>Contact your administrator if you believe this is an error</li>
                                    <li>Select a different role if you have multiple roles</li>
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `,
                showConfirmButton: false,
                timer: 4000,
                toast: true,
                background: '#f8f9fa',
                backdrop: false,
                width: '450px',
                customClass: {
                    title: 'text-lg font-medium',
                    htmlContainer: 'text-left'
                }
            });
        }
    });
};
</script>

<template>
    <div class="flex flex-col md:flex-row h-full bg-white">
        <!-- Left Side - Logo Section -->
        <div
            class="hidden md:flex md:w-1/2 bg-gradient-to-br from-[#062F64]/80 to-[#1E4272]/80 items-center justify-center p-12">
            <div class="text-center space-y-6">
                <img src="/images/MainLogo.png" class="w-64 h-64 mx-auto object-contain" alt="Logo">
                <div class="space-y-2">
                    <h1
                        class="text-transparent text-4xl font-bold bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200">
                        AquaTrack
                    </h1>
                    <p class="text-blue-100 text-xl">Water Management System</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center mb-8">
                <h2 class="text-2xl text-gray-800 mb-2">Login as {{ selectedRole || 'Customer' }}</h2>
                <p class="text-gray-600">Enter your credentials to continue</p>
            </div>

            <!-- Code Verification Form -->
            <div v-if="requiresVerification && !codeVerified" class="space-y-6">
                <div class="relative">
                    <input id="verificationCode" v-model="verificationCode" type="password" required
                        class="w-full px-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent peer"
                        placeholder=" ">
                    <label for="verificationCode"
                        class="absolute left-3 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                        {{ selectedRole }} Verification Code
                    </label>
                </div>

                <div v-if="verificationError" class="text-red-500 text-sm mt-1">
                    {{ verificationError }}
                </div>

                <button @click="verifyCode" type="button" :disabled="isVerifying"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors duration-200 flex items-center justify-center gap-2"
                    :class="{ 'opacity-75 cursor-not-allowed': isVerifying }">
                    <template v-if="isVerifying">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Verifying...
                    </template>
                    <template v-else>
                        Verify Code
                    </template>
                </button>
            </div>

            <!-- Main Login Form -->
            <form v-if="!requiresVerification || codeVerified" @submit.prevent="submit" class="space-y-6">
                <div class="relative">
                    <input id="email" v-model="form.email" type="email" required
                        class="w-full px-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer"
                        placeholder=" ">
                    <label for="email"
                        class="absolute left-3 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                        Email Address
                    </label>
                </div>

                <div class="relative">
                    <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" required
                        class="w-full px-4 py-3 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 peer"
                        placeholder=" ">
                    <label for="password"
                        class="absolute left-3 top-3 text-gray-500 transition-all duration-200 transform peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 pointer-events-none bg-white px-1">
                        Password
                    </label>
                    <button type="button" class="absolute right-3 top-3.5" @click="showPassword = !showPassword">
                        <OhVueIcon :name="showPassword ? 'bi-eye-slash' : 'bi-eye'"
                            class="text-gray-500 hover:text-gray-700" />
                    </button>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors duration-200 flex items-center justify-center gap-2"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }">
                    <template v-if="form.processing">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Signing in...
                    </template>
                    <template v-else>
                        <v-icon name="md-login-outlined" class="text-lg" />
                        Sign In
                    </template>
                </button>
            </form>

            <div class="mt-6 text-sm text-[#1C606A] border border[#D7F1F5] p-4 bg-[#D7F1F5] rounded-md">
                If you do not know your account credentials, or if you have forgotten your password, please contact the
                Systems Development & Administration Office.
            </div>
        </div>
    </div>
</template>

<style scoped>
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

input:focus~label,
input:not(:placeholder-shown)~label {
    transform: translateY(-1.5rem) scale(0.75);
    color: #2563eb;
}

input::placeholder {
    color: transparent;
}
</style>
