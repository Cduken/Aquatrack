<script setup>

import { Head } from '@inertiajs/vue3';
import Navigation from '@/Components/Header/Navigation.vue';
import Hero from '@/Components/Hero.vue';
import Features from '@/Components/Features.vue';
import About from '@/Components/About.vue';
import CallToAction from '@/Components/CallToAction.vue';
import Footer from '@/Components/Footer.vue';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>



        <Head title="Welcome" />
        <Navigation :can-login="canLogin" :can-register="canRegister" />


        <div class="bg-blue-50">
            <Hero />

            <Features />

            <About />

            <CallToAction />

            <Footer />


        </div>

</template>
