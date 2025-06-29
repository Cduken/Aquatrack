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

const isMobileMenuOpen = ref(false);
const showReportModal = ref(false);
</script>

<template>
    <div>
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-24 items-center justify-between">
                    <!-- Logo with fixed width -->
                    <div class="flex-shrink-0">
                        <Link href="/">
                        <AquatrackLogo class="h-10" />
                        </Link>
                    </div>

                    <!-- Desktop navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <Link href="/"
                            class="flex items-center gap-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-lg font-medium transition-colors">
                        <v-icon name="bi-house" scale="0.9" />
                        Home
                        </Link>
                        <Link :href="route('reports.index')"
                            class="flex items-center gap-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-lg font-medium transition-colors">
                        <v-icon name="bi-flag" scale="0.9" />
                        Reports
                        </Link>
                        <button @click="showReportModal = true"
                            class="flex items-center gap-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-lg font-medium transition-colors">
                            <v-icon name="bi-plus-circle" scale="0.9" />
                            Add Report
                        </button>
                    </div>

                    <!-- Right section -->
                    <div class="flex items-center gap-4">
                        <Link v-if="canLogin" :href="route('login')"
                            class="hidden md:flex items-center gap-2 rounded-md bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-2 text-lg font-medium text-white shadow-sm hover:from-blue-700 hover:to-blue-600 transition-all">

                        Log In
                        <v-icon name="bi-box-arrow-in-right" scale="1.2" />
                        </Link>

                        <!-- Mobile menu button -->
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="md:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none">
                            <v-icon :name="isMobileMenuOpen ? 'bi-x-lg' : 'bi-list'" scale="1.25" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-show="isMobileMenuOpen" class="md:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link href="/" @click="isMobileMenuOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                    <v-icon name="bi-house" scale="0.9" />
                    Home
                    </Link>
                    <Link :href="route('reports.index')" @click="isMobileMenuOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                    <v-icon name="bi-flag" scale="0.9" />
                    Reports
                    </Link>
                    <button @click="showReportModal = true; isMobileMenuOpen = false"
                        class="flex items-center gap-3 w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                        <v-icon name="bi-plus-circle" scale="0.9" />
                        Add Report
                    </button>
                    <Link v-if="canLogin" :href="route('login')" @click="isMobileMenuOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                    <v-icon name="bi-box-arrow-in-right" scale="0.9" />
                    Log In
                    </Link>
                </div>
            </div>
        </header>

        <AddReportModal :show="showReportModal" @close="showReportModal = false" />

        <slot></slot>
    </div>
</template>
