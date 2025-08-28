<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import AquatrackLogo from "../AquatrackLogo.vue";

const page = usePage();

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
});

const isSidebarOpen = ref(false);
const showReportModal = ref(false);
const isMobileMenuOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const handleAddReport = () => {
    showReportModal.value = true;
    isSidebarOpen.value = false;
};

// Detect if we're on specific pages
const isReportsPage = computed(() => page.url.startsWith("/reports"));
const isSelectRolesPage = computed(() => page.url.startsWith("/select-roles"));

// Single computed to decide if we should show "Back to Home"
const showBackToHome = computed(
    () => isReportsPage.value || isSelectRolesPage.value
);
</script>

<template>
    <!-- Navbar -->
    <header class="sticky top-0 z-50 inset-0 lg:py-6 md:py-4">
        <div class="mx-auto px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                    <Link href="/">
                        <AquatrackLogo class="h-8 w-auto" />
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <!-- Desktop Navigation -->
                <div
                    v-if="!isSelectRolesPage && !isReportsPage"
                    class="hidden md:flex items-center bg-[#1E4272]/80 rounded-[50px] px-8 py-[12px] gap-14 border border-gray-200/20 mr-12"
                >
                    <button class="font-semibold text-white text-sm">
                        Home
                    </button>
                    <button class="font-semibold text-gray-300 text-sm">
                        Features
                    </button>
                    <button class="font-semibold text-gray-300 text-sm">
                        About
                    </button>
                    <button class="font-semibold text-gray-300 text-sm">
                        CTA
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        v-if="showBackToHome"
                        href="/"
                        class="hidden sm:inline-flex items-center px-4 py-[12px] text-sm bg-white text-black rounded-[50px] hover:bg-[#E6E8EB] transition duration-300"
                    >
                        <v-icon name="bi-arrow-bar-left" class="mr-2" />
                        Back to Home
                    </Link>
                    <Link
                        v-else
                        :href="route('select-roles')"
                        class="lg:block hidden sm:inline-flex items-center px-4 py-[12px] text-sm bg-white text-black rounded-[50px] hover:bg-[#E6E8EB] transition duration-300"
                    >
                        <v-icon name="bi-person-circle" class="mr-2" />
                        Get Started
                    </Link>

                    <!-- Mobile menu button -->
                    <button
                        @click="toggleMobileMenu"
                        class="md:hidden sm:hidden lg:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#1E4272] focus:outline-none"
                    >
                        <v-icon
                            v-if="!isMobileMenuOpen"
                            name="hi-menu"
                            class="h-6 w-6"
                        />
                        <v-icon v-else name="hi-x" class="h-6 w-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                v-if="isMobileMenuOpen"
                class="md:hidden mt-4 pb-4 border-t border-white/20 pt-4"
            >
                <div class="flex flex-col space-y-3">
                    <button
                        class="font-semibold text-white text-sm py-2 px-4 bg-[#1E4272]/50 rounded-lg"
                    >
                        Home
                    </button>
                    <button
                        class="font-semibold text-gray-300 text-sm py-2 px-4"
                    >
                        Features
                    </button>
                    <button
                        class="font-semibold text-gray-300 text-sm py-2 px-4"
                    >
                        About
                    </button>
                    <button
                        class="font-semibold text-gray-300 text-sm py-2 px-4"
                    >
                        CTA
                    </button>

                    <div class="border-t border-white/20">
                        <Link
                            v-if="showBackToHome"
                            href="/"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm bg-white text-black rounded-[50px] hover:bg-[#E6E8EB] transition duration-300 w-full"
                        >
                            <v-icon name="bi-arrow-bar-left" class="mr-2" />
                            Back to Home
                        </Link>
                        <Link
                            v-else
                            :href="route('select-roles')"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm bg-white text-black rounded-[50px] hover:bg-[#E6E8EB] transition duration-300 w-full"
                        >
                            <v-icon name="bi-person-circle" class="mr-2" />
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <slot></slot>
    </main>
</template>
