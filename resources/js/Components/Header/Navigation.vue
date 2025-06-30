<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AquatrackLogo from '../AquatrackLogo.vue';
import AddReportModal from '../Modals/AddReportModal.vue';

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


                        <!-- Sidebar Toggle Button -->
                        <button @click="toggleSidebar"
                            class="p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none transition-colors">
                            <v-icon name="bi-list" scale="1.25" />
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Overlay -->
        <div v-show="isSidebarOpen" @click="toggleSidebar"
            class="fixed inset-0 bg-black/50 z-50 transition-opacity duration-300"
            :class="{ 'opacity-0 pointer-events-none': !isSidebarOpen, 'opacity-100': isSidebarOpen }">
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg z-50 transform transition-transform duration-300 ease-in-out"
            :class="{ 'translate-x-full': !isSidebarOpen, 'translate-x-0': isSidebarOpen }">
            <div class="h-full flex flex-col">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <AquatrackLogo></AquatrackLogo>
                    <button @click="toggleSidebar" class="p-1 rounded-full hover:bg-gray-100">
                        <v-icon name="bi-x-lg" />
                    </button>
                </div>

                <!-- Sidebar Content -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <Link href="/" @click="toggleSidebar"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <v-icon name="bi-house" scale="0.9" />
                    <span>Home</span>
                    </Link>

                    <Link :href="route('reports.index')" @click="toggleSidebar"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <v-icon name="bi-flag" scale="0.9" />
                    <span>Reports</span>
                    </Link>

                    <button @click="showReportModal = true; toggleSidebar()"
                        class="flex items-center gap-3 w-full text-left px-3 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <v-icon name="bi-plus-circle" scale="0.9" />
                        <span>Add Report</span>
                    </button>

                    <div class="border-t border-gray-200 my-2"></div>

                    <Link v-if="canLogin" :href="route('login')" @click="toggleSidebar"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <v-icon name="bi-box-arrow-in-right" scale="0.9" />
                    <span>Log In</span>
                    </Link>
                </div>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t text-sm text-gray-500">
                    <p>Aquatrack v1.0</p>
                </div>
            </div>
        </aside>

        <!-- Add Report Modal -->
        <AddReportModal :show="showReportModal" @close="showReportModal = false" />

        <!-- Main Content -->
        <main class="flex-1">
            <slot></slot>
        </main>
    </div>
</template>

<style>
/* Smooth transitions for the sidebar */
.transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
