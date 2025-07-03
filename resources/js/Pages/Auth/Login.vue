<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { OhVueIcon } from 'oh-vue-icons';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showPassword = ref(false);
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign in" />

        <div class="max-w-4xl w-full bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col md:flex-row h-full md:h-auto">
            <!-- Left Side - Logo -->
            <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center p-12 bg-gradient-to-r from-blue-500 via-blue-400 to-blue-300">
                <img src="/images/MainLogo.png" class="max-w-full max-h-full h-[300px] w-[300px] object-contain" alt="">
                <h1 class="text-white text-3xl font-bold mt-6">AquaTrack</h1>
                <p class="text-blue-100 mt-2 text-center text-lg">
                    Smart Water Management System
                </p>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full md:w-1/2 py-10 px-6 md:px-12">
                <div class="md:hidden mb-8 flex flex-col items-center">
                    <img src="/images/MainLogo.png" class="h-[150px] w-[150px] object-contain" alt="">
                    <h1 class="text-2xl sm:text-3xl font-md text-gray-800 mt-4">Welcome Back!</h1>
                    <!-- <p class="text-blue-500 mt-2 text-center text-lg">
                        Smart Water Management System
                    </p> -->
                </div>

                <div class="text-center mb-6 lg:block md:block sm:hidden hidden">
                    <h2 class="text-2xl font-md text-gray-800">Welcome Back!</h2>
                </div>

                <form @submit.prevent="submit" class="w-full">
                    <!-- Email Field with Floating Label -->
                    <div class="relative mb-6">
                        <input id="email" name="email" type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent peer"
                            v-model="form.email" required placeholder=" " autofocus />
                        <label for="email" class="absolute left-4 top-3 text-gray-500 pointer-events-none transition-all duration-200 ease-out
                                   peer-focus:-top-2 peer-focus:text-sm peer-focus:text-blue-600
                                   peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-sm peer-[:not(:placeholder-shown)]:text-blue-600
                                   bg-white px-1">
                            Email Address
                        </label>
                    </div>

                    <!-- Password Field with Floating Label -->
                    <div class="relative mb-6">
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent peer"
                            v-model="form.password" required placeholder=" " />
                        <label for="password" class="absolute left-4 top-3 text-gray-500 pointer-events-none transition-all duration-200 ease-out
                                   peer-focus:-top-2 peer-focus:text-sm peer-focus:text-blue-600
                                   peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-sm peer-[:not(:placeholder-shown)]:text-blue-600
                                   bg-white px-1">
                            Password
                        </label>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            @click="togglePasswordVisibility">
                            <OhVueIcon :name="showPassword ? 'bi-eye-slash' : 'bi-eye'"
                                class="text-gray-500 hover:text-gray-700" />
                        </button>
                    </div>

                    <div class="mb-4">
                        <button
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
                            type="submit" :disabled="form.processing">
                            <v-icon name="md-login-outlined" />
                            Sign In
                        </button>
                    </div>

                    <span class="border border-gray-200 flex mb-4"></span>

                    <div class="flex items-center">
                        <div class="p-4 border border-blue-100 rounded-md bg-[#D7F1F5]">
                            <p class="text-sm text-[#1C606A]">
                                If you do not know your account credentials, or if you have forgotten your password,
                                please contact the Systems Development & Administration Office.
                            </p>
                        </div>
                    </div>
                </form>

                <div class="mt-6 text-center text-gray-500 text-xs">
                    Powered by Local Water Utilities Administration (LWUA)
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Add smooth transitions for the floating labels */
label {
    transition: all 0.2s ease-out;
}

/* Adjust the position of the eye icon to account for the larger input */
.relative .absolute.right-0 {
    top: 50%;
    transform: translateY(-50%);
}

/* Ensure the input has enough padding to not overlap with the label when floated */
input {
    padding-top: 1rem;
    padding-bottom: 0.5rem;
}
</style>
