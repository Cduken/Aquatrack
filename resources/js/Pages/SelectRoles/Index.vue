<template>
    <div class="min-h-screen relative overflow-hidden">
        <Navigation />

        <!-- Background elements -->
        <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/images/AquatrackIMG.jpg')"
        ></div>
        <div
            class="absolute inset-0 bg-gradient-to-br from-[#062F64]/80 to-[#1E4272]/80"
        >
            <div
                class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 to-transparent"
            ></div>
        </div>

        <!-- Floating decorations -->
        <div
            class="select-roles-drop-top absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-blue-500 opacity-10 blur-3xl"
        ></div>
        <div
            class="select-roles-drop-bottom absolute bottom-1/3 right-1/4 w-96 h-96 rounded-full bg-teal-500 opacity-10 blur-3xl"
        ></div>

        <!-- Main content -->
        <div
            class="relative z-10 min-h-screen flex flex-col items-center justify-center px-4"
        >
            <div class="w-full min-h-[85vh] max-w-6xl text-center">
                <!-- Header -->
                <div class="mb-12 select-roles-header">
                    <h1
                        class="text-4xl md:text-6xl text-white mb-4 font-md tracking-[-0.06em] select-roles-headline"
                    >
                        Welcome to
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200"
                            >AquaTrack</span
                        >
                    </h1>
                    <p
                        class="text-lg text-[#A2A8AF] max-w-2xl mx-auto select-roles-subheadline"
                    >
                        Choose how you want to access the platform
                    </p>
                </div>

                <!-- Role cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-8 mx-auto max-w-5xl role-cards-container"
                >
                    <!-- Customer Card -->
                    <div
                        class="glass-card group role-card"
                        @click="showLoginModal('customer')"
                    >
                        <div
                            class="glass-card-inner bg-gradient-to-br from-blue-500/20 to-blue-600/20"
                        >
                            <div class="icon-container bg-blue-500/20">
                                <v-icon
                                    name="hi-user"
                                    class="text-blue-300 text-4xl"
                                />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">
                                Concessioner
                            </h3>
                            <p class="text-blue-100 mb-6">
                                Report and track water issues
                            </p>
                            <div class="select-badge">Select Role</div>
                        </div>
                    </div>

                    <!-- Admin Card -->
                    <div
                        class="glass-card group role-card"
                        @click="showLoginModal('admin')"
                    >
                        <div
                            class="glass-card-inner bg-gradient-to-br from-purple-500/20 to-purple-600/20"
                        >
                            <div class="icon-container bg-purple-500/20">
                                <v-icon
                                    name="hi-cog"
                                    class="text-purple-300 text-4xl"
                                />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">
                                Administrator
                            </h3>
                            <p class="text-purple-100 mb-6">
                                Manage system and users
                            </p>
                            <div class="select-badge">Select Role</div>
                        </div>
                    </div>

                    <!-- Staff Card -->
                    <div
                        class="glass-card group role-card"
                        @click="showLoginModal('staff')"
                    >
                        <div
                            class="glass-card-inner bg-gradient-to-br from-teal-500/20 to-teal-600/20"
                        >
                            <div class="icon-container bg-teal-500/20">
                                <v-icon
                                    name="hi-users"
                                    class="text-teal-300 text-4xl"
                                />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">
                                Staff
                            </h3>
                            <p class="text-teal-100 mb-6">
                                Handle meter readings
                            </p>
                            <div class="select-badge">Select Role</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300"
        >
            <div
                class="relative bg-white rounded-xl shadow-2xl w-full max-w-[900px] mx-4 overflow-hidden transform transition-all duration-300"
                :class="modalClasses"
            >
                <button
                    @click="hideLoginModal"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10"
                >
                    <v-icon name="hi-x" class="text-2xl" />
                </button>
                <Login
                    :selected-role="selectedRole"
                    @close="hideLoginModal"
                    @login-success="handleLoginSuccess"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { OhVueIcon, addIcons } from "oh-vue-icons";
import {
    HiUser,
    HiCog,
    HiUsers,
    FaWater,
    HiArrowLeft,
    HiX,
} from "oh-vue-icons/icons";
import Login from "@/Pages/Auth/Login.vue";
import Navigation from "@/Components/Header/Navigation.vue";
import gsap from "gsap"; // Import GSAP

addIcons(HiUser, HiCog, HiUsers, FaWater, HiArrowLeft, HiX);

const showModal = ref(false);
const selectedRole = ref("");

const modalClasses = computed(() => {
    return showModal.value
        ? "opacity-100 scale-100 translate-y-0"
        : "opacity-0 scale-95 translate-y-4";
});

const showLoginModal = (role) => {
    selectedRole.value = role.charAt(0).toUpperCase() + role.slice(1);
    showModal.value = true;
};

const hideLoginModal = () => {
    showModal.value = false;
};

const handleLoginSuccess = () => {
    hideLoginModal();
};

// GSAP animations on page load
onMounted(() => {
    // Set initial state for all animated elements
    gsap.set(".select-roles-drop-top, .select-roles-drop-bottom", {
        scale: 0,
        opacity: 0,
    });

    gsap.set(".select-roles-header", {
        y: 30,
        opacity: 0,
    });

    gsap.set(".select-roles-headline span", {
        backgroundPosition: "100% 50%",
    });

    gsap.set(".select-roles-subheadline", {
        y: 15,
        opacity: 0,
    });

    gsap.set(".role-card", {
        y: 30,
        opacity: 0,
        scale: 1, // Changed from 0.9 to 1 to prevent scaling jump
    });

    const tl = gsap.timeline();

    // Animate background elements
    tl.to(".select-roles-drop-top, .select-roles-drop-bottom", {
        scale: 1,
        opacity: 0.1,
        duration: 1.2,
        ease: "power2.out",
    });

    // Animate header content
    tl.to(
        ".select-roles-header",
        {
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power2.out",
        },
        "-=0.8"
    );

    // Animate headline with gradient text reveal
    tl.to(
        ".select-roles-headline span",
        {
            backgroundPosition: "0% 50%",
            duration: 1.5,
            ease: "power2.inOut",
        },
        "-=0.5"
    );

    // Animate subheadline
    tl.to(
        ".select-roles-subheadline",
        {
            y: 0,
            opacity: 1,
            duration: 0.7,
            ease: "power2.out",
        },
        "-=0.5"
    );

    // Animate role cards with stagger effect - smoother animation
    tl.to(
        ".role-card",
        {
            y: 0,
            opacity: 1,
            duration: 0.8,
            stagger: 0.2,
            ease: "back.out(1.4)", // Smoother easing
        },
        "-=0.4"
    );

    // Add continuous subtle animations to water drops
    gsap.to(".select-roles-drop-top", {
        y: 20,
        duration: 4,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
        delay: 1, // Start after initial animation
    });

    gsap.to(".select-roles-drop-bottom", {
        y: -20,
        duration: 5,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
        delay: 1.2, // Slight delay for more natural movement
    });
});
</script>

<style scoped>
.glass-card {
    perspective: 1000px;
    height: 320px;
    position: relative;
    cursor: pointer;
    /* Prevent layout shift during animation */
    transform: translateZ(0);
    backface-visibility: hidden;
}

.glass-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 1rem;
    padding: 2px;
    background: linear-gradient(45deg, transparent, transparent);
    -webkit-mask: linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    transition: all 0.5s ease;
    opacity: 0;
}

.glass-card:hover::before {
    opacity: 1;
}

.glass-card:nth-child(1):hover::before {
    background: linear-gradient(
        45deg,
        rgba(59, 130, 246, 0.5),
        rgba(6, 182, 212, 0.5)
    );
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
}

.glass-card:nth-child(2):hover::before {
    background: linear-gradient(
        45deg,
        rgba(168, 85, 247, 0.5),
        rgba(217, 70, 239, 0.5)
    );
    box-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
}

.glass-card:nth-child(3):hover::before {
    background: linear-gradient(
        45deg,
        rgba(20, 184, 166, 0.5),
        rgba(16, 185, 129, 0.5)
    );
    box-shadow: 0 0 20px rgba(20, 184, 166, 0.5);
}

.glass-card-inner {
    @apply h-full w-full rounded-2xl p-8 border border-white/10 backdrop-blur-lg;
    transform-style: preserve-3d;
    transition: all 0.5s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.05);
    /* Prevent layout issues during animation */
    transform: translateZ(0);
    backface-visibility: hidden;
}

.glass-card:hover .glass-card-inner {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    background: rgba(255, 255, 255, 0.1);
}

.icon-container {
    @apply w-20 h-20 rounded-full flex items-center justify-center mb-6 mx-auto;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.select-badge {
    @apply inline-block px-6 py-2 rounded-full text-sm font-medium text-white bg-white/20;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.group:hover .select-badge {
    @apply bg-white/30 transform scale-105;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* Performance optimizations */
.role-card {
    transform: translateZ(0);
    backface-visibility: hidden;
}

.select-roles-drop-top,
.select-roles-drop-bottom {
    transform: translateZ(0);
}
</style>
