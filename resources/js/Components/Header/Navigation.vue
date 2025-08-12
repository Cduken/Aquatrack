<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
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
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <div class="flex-shrink-0">
                        <Link href="/">
                        <AquatrackLogo class="h-10" />
                        </Link>
                    </div>

                    <!-- Right section -->
                    <div class="flex items-center gap-4">
                        <!-- Login Button -->
                        <Link :href="route('select-roles')"
                            class="inline-flex items-center px-4 py-2 text-blue-900 border border-blue-600 rounded-md hover: border-blue-700">
                        <v-icon name="bi-box-arrow-in-right" class="mr-2" />
                        Login
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Component -->
        <Sidebar :isOpen="isSidebarOpen" :canLogin="canLogin" :canRegister="canRegister" @close="toggleSidebar"
            @add-report="handleAddReport" />

        <!-- Add Report Modal -->
        <AddReportModal :show="showReportModal" @close="showReportModal = false" />

        <!-- Main Content -->
        <main class="flex-1">
            <slot></slot>
        </main>
    </div>
</template>
