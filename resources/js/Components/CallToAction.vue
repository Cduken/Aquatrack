<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="mt-10 sm:mt-20 bg-gradient-to-r from-blue-600 to-blue-900 pt-16 pb-16 sm:pt-32 sm:pb-32 text-white">
        <div class="text-center px-4">
            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6">
                Ready to optimize your water management?
            </h2>
            <p class="text-base sm:text-md md:text-lg max-w-3xl mx-auto text-blue-100">
                Join hundreds of organizations using AquaTrack to save water and reduce costs
            </p>
            <div class="mt-6 sm:mt-8">
                <Link :href="route('login')"
                    class="inline-flex items-center gap-2 rounded-md bg-white px-4 py-3 sm:px-8 sm:py-4 text-base sm:text-lg font-medium text-blue-600 shadow transition hover:bg-blue-50">
                Start Now
                <v-icon name="md-start-twotone" scale="1.3" />
                </Link>
            </div>
        </div>
    </section>
</template>
