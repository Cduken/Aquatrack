<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { OhVueIcon } from 'oh-vue-icons';

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

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Welcome to AquaTrack</h1>
            <p class="text-gray-600 text-sm">Sign in to manage your water usage</p>
        </div>

        <form @submit.prevent="submit" class="w-full">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-1" for="email">
                    Email Address
                </label>
                <div class="relative">
                    <input
                        id="email"
                        type="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        v-model="form.email"
                        required
                        placeholder="Enter your email"
                        autofocus
                    />
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-1" for="password">
                    Password
                </label>
                <div class="relative">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        v-model="form.password"
                        required
                        placeholder="Enter your password"
                    />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        @click="togglePasswordVisibility"
                    >
                        <OhVueIcon
                            :name="showPassword ? 'bi-eye-slash' : 'bi-eye'"
                            class="text-gray-500 hover:text-gray-700"
                        />
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <button
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
                    type="submit"
                    :disabled="form.processing"
                >
                    Sign In
                </button>
            </div>

            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                    <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 font-medium">
                        Register here
                    </Link>
                </p>
            </div>
        </form>

        <div class="mt-6 text-center text-gray-500 text-xs">
            Powered by Local Water Utilities Administration (LWUA)
        </div>
    </GuestLayout>
</template>
