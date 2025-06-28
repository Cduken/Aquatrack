<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    }
});

const isMobileMenuOpen = ref(false);
</script>

<template>
    <header class="sticky top-0 z-50 bg-white/50 backdrop-blur-lg shadow-lg">
        <div class="mx-auto p-2 px-6 sm:px-8 md:px-12">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <Link href="/" class="flex items-center group">
                    <img src="/images/MainLogo.png" alt="AquaTrack Logo"
                        class="h-14 w-14 sm:h-20 sm:w-20 md:h-24 md:w-24 object-contain rounded-full" />
                    <span
                        class="ml-2 text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">
                        AquaTrack
                    </span>
                    </Link>
                </div>


                <div class="flex items-center gap-6">



                    <Link v-if="canLogin" :href="route('login')" class="hidden sm:flex items-center justify-center gap-2 rounded-md bg-blue-600
                        px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700 transition-all
                        sm:px-4 sm:py-2 sm:text-base
                        md:px-6 md:py-3 md:text-lg
                        hover:shadow-lg hover:-translate-y-0.5 transform">
                    Log In
                    <v-icon name="bi-box-arrow-in-right" scale="1"
                        class="sm:scale-110 md:scale-125 transition-transform group-hover:translate-x-1" />
                    </Link>


                    <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="md:hidden text-blue-700 focus:outline-none transition-transform duration-300"
                        :class="{ 'rotate-90': isMobileMenuOpen }">
                        <v-icon :name="isMobileMenuOpen ? 'bi-x-lg' : 'bi-list'" scale="1.5"
                            class="w-8 h-8 transition-all duration-300" />
                    </button>
                </div>
            </div>


            <div v-show="isMobileMenuOpen" class="md:hidden origin-top transition-all duration-300 ease-out"
                :class="isMobileMenuOpen ? 'scale-y-100 opacity-100' : 'scale-y-0 opacity-0'">
                <div class="space-y-4 py-4 text-center">
                    <Link href="#features" class="block text-lg text-blue-900 hover:text-blue-600 py-2 transition-all duration-300
                          hover:bg-blue-50 rounded-lg hover:scale-105 transform">
                    Features
                    </Link>
                    <Link href="#solutions" class="block text-lg text-blue-900 hover:text-blue-600 py-2 transition-all duration-300
                          hover:bg-blue-50 rounded-lg hover:scale-105 transform">
                    Solutions
                    </Link>
                    <Link href="#pricing" class="block text-lg text-blue-900 hover:text-blue-600 py-2 transition-all duration-300
                          hover:bg-blue-50 rounded-lg hover:scale-105 transform">
                    Pricing
                    </Link>
                    <Link href="#contact" class="block text-lg text-blue-900 hover:text-blue-600 py-2 transition-all duration-300
                          hover:bg-blue-50 rounded-lg hover:scale-105 transform">
                    Contact
                    </Link>

                    <Link v-if="canLogin" :href="route('login')" class="mt-4 inline-flex items-center justify-center gap-2 rounded-md bg-blue-600
                        px-6 py-3 text-lg font-medium text-white hover:bg-blue-700 transition-all
                        hover:shadow-lg hover:-translate-y-0.5 transform">
                    Log In
                    <v-icon name="bi-box-arrow-in-right" scale="1.2"
                        class="transition-transform group-hover:translate-x-1" />
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
