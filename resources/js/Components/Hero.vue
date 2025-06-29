<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="pt-16 w-full">
        <div class="w-full flex flex-col items-center text-center px-4 sm:px-6">
            <div class="w-full">
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900">
                    Track Your Water Usage with AquaTrack
                </h1>

                <p class="mt-6 max-w-5xl mx-auto text-base sm:text-lg md:text-xl lg:text-2xl text-gray-600">
                    Modern water management system for residential customers. Monitor usage, pay bills, and report
                    issues all in one convenient platform.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('login')"
                        class="inline-flex items-center text-center gap-2 rounded-md bg-blue-600 px-8 py-4 text-lg font-medium text-white hover:bg-blue-700 transition-colors">
                    Log in to Your Account
                    <v-icon name="hi-solid-user" scale="1.3"></v-icon>
                    </Link>

                </div>

                <!-- Full width water wave illustration with rounded corners -->
                <div class="w-full mt-12 overflow-hidden">
                    <div class="rounded-tl-[40px] rounded-tr-[40px] overflow-hidden">
                        <svg class="w-full" viewBox="0 0 1200 120" preserveAspectRatio="none">
                            <path
                                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                                opacity=".25" class="fill-blue-400 dark:fill-blue-600"></path>
                            <path
                                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                                opacity=".5" class="fill-blue-400 dark:fill-blue-600"></path>
                            <path
                                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                                class="fill-blue-500 dark:fill-blue-700"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
