<script setup>
import { Head } from "@inertiajs/vue3";
import Navigation from "@/Components/Header/Navigation.vue";
import Hero from "@/Components/Hero.vue";
import Features from "@/Components/Features.vue";
import About from "@/Components/About.vue";
import CallToAction from "@/Components/CallToAction.vue";
import Footer from "@/Components/Footer.vue";
import { onMounted, ref } from "vue";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

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

const animationInitialized = ref(false);

// Setup scroll animations
onMounted(() => {
    if (!animationInitialized.value) {
        // Wait for components to be fully rendered
        setTimeout(() => {
            setupScrollAnimations();
            animationInitialized.value = true;
        }, 300);
    }
});

function setupScrollAnimations() {
    if (typeof window === "undefined") return;

    // Clear existing triggers
    ScrollTrigger.getAll().forEach((trigger) => trigger.kill());

    // Create a master timeline for better control
    const masterTL = gsap.timeline();

    // Features animation
    const featureItems = document.querySelectorAll(".feature-item");
    if (featureItems.length > 0) {
        featureItems.forEach((item, index) => {
            const animation = gsap.fromTo(
                item,
                { y: 50, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.8, delay: index * 0.15 }
            );

            ScrollTrigger.create({
                animation: animation,
                trigger: item,
                start: "top 85%",
                end: "bottom 15%",
                toggleActions: "play none none reset",
                markers: false,
            });
        });
    }

    // About content animation
    const aboutContent = document.querySelector(".about-content");
    if (aboutContent) {
        const animation = gsap.fromTo(
            aboutContent,
            { x: -50, opacity: 0 },
            { x: 0, opacity: 1, duration: 1 }
        );

        ScrollTrigger.create({
            animation: animation,
            trigger: aboutContent,
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reset",
            markers: false,
        });
    }

    // About image animation
    const aboutImage = document.querySelector(".about-image");
    if (aboutImage) {
        const animation = gsap.fromTo(
            aboutImage,
            { x: 50, opacity: 0 },
            { x: 0, opacity: 1, duration: 1 }
        );

        ScrollTrigger.create({
            animation: animation,
            trigger: aboutImage,
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reset",
            markers: false,
        });
    }

    // CTA animation
    const ctaSection = document.querySelector(".cta-section");
    if (ctaSection) {
        const animation = gsap.fromTo(
            ctaSection,
            { scale: 0.9, opacity: 0 },
            { scale: 1, opacity: 1, duration: 0.8 }
        );

        ScrollTrigger.create({
            animation: animation,
            trigger: ctaSection,
            start: "top 85%",
            end: "bottom 15%",
            toggleActions: "play none none reset",
            markers: false,
        });
    }

    ScrollTrigger.refresh();
}

// Optional: Add scroll event listener to refresh animations on route changes
if (typeof window !== "undefined") {
    window.addEventListener("scroll", () => {
        // This ensures animations work when scrolling back up
        ScrollTrigger.refresh();
    });
}
</script>

<template>
    <Head title="Landing" />

    <div class="overflow-hidden">

        <Hero :can-login="canLogin" :can-register="canRegister" />

        <Features />

        <About />

        <CallToAction />

        <Footer />
    </div>
</template>
