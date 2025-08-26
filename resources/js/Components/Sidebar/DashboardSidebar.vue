<template>
    <aside
        :class="['fixed top-0 left-0 z-40 h-screen pt-14 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 ease-in-out', isOpen ? 'w-64' : 'w-16']"
        aria-label="Sidenav" id="drawer-navigation">
        <div class="h-full overflow-hidden py-5 px-3 bg-white dark:bg-gray-800">
            <form action="#" method="GET" class="md:hidden mb-2" :class="{ 'hidden': !isOpen }">
                <label for="sidebar-search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="sidebar-search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Search" />
                </div>
            </form>
            <ul class="space-y-6 pt-[6px]">
                <li v-for="link in links" :key="link.url">
                    <Link :href="link.url"
                        class="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all duration-300 relative"
                        :class="{
                            'bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 font-semibold text-primary-700 dark:text-primary-200': isActive(link.url),
                            'hover:translate-x-1': !isActive(link.url)
                        }">
                    <!-- Thicker active indicator bar -->
                    <span v-if="isActive(link.url)"
                        class="absolute -left-0.5 w-1.5 h-8 rounded-r-full bg-primary-500"></span>

                    <component :is="getIconComponent(link.icon)" class="w-6 h-6 transition-all duration-300 z-10"
                        :class="{
                            'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white': !isActive(link.url),
                            'text-primary-600 dark:text-primary-400': isActive(link.url)
                        }" />
                    <span class="ml-3 transition-opacity duration-300"
                        :class="{ 'opacity-0 absolute': !isOpen, 'opacity-100': isOpen }">{{ link.name }}</span>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import {
    HomeIcon,
    ChartBarIcon,
    FlagIcon,
    MegaphoneIcon,
    UsersIcon,
    DocumentTextIcon,
} from '@heroicons/vue/24/outline';

defineProps({
    links: {
        type: Array,
        required: true,
    },
    isOpen: {
        type: Boolean,
        default: true,
    },
});

const iconMap = {
    'md-dashboard': HomeIcon,
    'bi-graph-up': ChartBarIcon,
    'bi-flag-fill': FlagIcon,
    'bi-megaphone-fill': MegaphoneIcon,
    'fa-users': UsersIcon,
    'bi-file-earmark-text-fill': DocumentTextIcon,
};

const getIconComponent = (iconName) => {
    return iconMap[iconName] || HomeIcon; // Fallback to HomeIcon
};

const isActive = (url) => {
    return window.location.pathname === url;
};

defineEmits(['toggle-sidebar']);
</script>

<style scoped>
aside {
    transition: width 0.3s ease-in-out;
}

a {
    transition: all 0.3s ease;
}

/* Hover effect for non-active items */
a:not(.bg-primary-50):hover {
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.08) 0%, transparent 100%);
}

/* Smooth transitions for text when sidebar collapses */
span {
    transition: opacity 0.2s ease-in-out;
}
</style>
