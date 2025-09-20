<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AquatrackLogo from '../AquatrackLogo.vue';
import AddReportModal from '../Modals/AddReportModal.vue';
import Sidebar from '../Sidebar/Sidebar.vue';

const page = usePage();

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

const isSidebarOpen = ref(false);
const showReportModal = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const handleAddReport = () => {
    showReportModal.value = true;
    isSidebarOpen.value = false;
};

// Detect if we're on specific pages
const isReportsPage = computed(() => page.url.startsWith('/reports'));
const isSelectRolesPage = computed(() => page.url.startsWith('/select-roles'));

// Single computed to decide if we should show "Back to Home"
const showBackToHome = computed(() => isReportsPage.value || isSelectRolesPage.value);
</script>

<template>

        <!-- Navbar -->
        <header class="sticky top-0 z-50 inset-0  ">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <div class="flex-shrink-0">
                        <Link href="/">
                        <AquatrackLogo class="h-10" />
                        </Link>
                    </div>

                    <!-- Right section -->
                    <div class="flex items-center gap-4">
                        <Link v-if="showBackToHome" href="/"
                            class="inline-flex items-center px-4 py-2 text-white rounded-lg transition-duration-300 hover:bg-[#d0d9e4] hover:text-black">
                        <v-icon name="bi-arrow-left" class="mr-2" />
                        Back to Home
                        </Link>
                        <Link v-else :href="route('select-roles')"
                            class="inline-flex items-center px-4 py-2 text-white rounded-lg transition-duration-300 hover:bg-[#d0d9e4] hover:text-black">
                        <v-icon name="bi-box-arrow-in-right" class="mr-2" />
                        Login
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <slot></slot>
        </main>

</template>
