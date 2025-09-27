<template>
    <div
        class="antialiased min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col"
    >
        <!-- Mobile Menu Overlay -->
        <div
            v-if="isMobileMenuOpen"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
            @click="closeMobileMenu"
        ></div>

        <DashboardSidebar
            :links="staffLinks"
            :is-open="isSidebarOpen"
            :is-mobile-menu-open="isMobileMenuOpen"
            @toggle-sidebar="toggleSidebar"
            @toggle-mobile-menu="toggleMobileMenu"
        />

        <MainContentNavbar
            :isSidebarOpen="isSidebarOpen"
            :isMobileMenuOpen="isMobileMenuOpen"
            @toggle-sidebar="toggleSidebar"
            @toggle-mobile-menu="toggleMobileMenu"
            @logout="handleLogout"
        />

        <main
            :class="[
                'p-4 pt-16 flex-1 transition-all duration-300 ease-in-out',
                {
                    'md:ml-64': isSidebarOpen && !isMobile,
                    'md:ml-16': !isSidebarOpen && !isMobile,
                    'ml-0': isMobile,
                },
            ]"
        >
            <slot />
        </main>
    </div>
</template>

<script setup>
import DashboardSidebar from "@/Components/Sidebar/DashboardSidebar.vue";
import MainContentNavbar from "@/Components/Header/MainContentNavbar.vue";
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { initFlowbite } from "flowbite";
import Swal from "sweetalert2";

const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);

// Detect mobile screen size
const isMobile = ref(window.innerWidth < 768);
const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    if (!isMobile.value) {
        isMobileMenuOpen.value = false;
    }
};

const staffLinks = [
    { name: "Dashboard", url: "/staff/dashboard", icon: "md-dashboard" },
    { name: "Reading", url: "/staff/reading", icon: "bi-flag-fill" },
];

const form = useForm({});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

const handleLogout = () => {
    form.post(route("logout"), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route("select-roles"));
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Logout failed",
                text: "Please try again.",
                background: "#f8f9fa",
                customClass: {
                    title: "text-lg font-medium",
                    popup: "dark:bg-gray-800 dark:text-white",
                },
            });
        },
    });
};

onMounted(() => {
    initFlowbite();
    window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});
</script>

<style scoped>
@media (max-width: 767px) {
    main {
        padding-top: 4rem !important;
    }
}
</style>
