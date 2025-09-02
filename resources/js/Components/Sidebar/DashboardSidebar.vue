<template>
    <aside
        :class="[
            'fixed top-0 left-0 z-50 h-screen pt-14 bg-white border-r border-gray-200 shadow-xl dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 ease-in-out',
            isOpen ? 'w-64' : 'w-16',
            isMobileMenuOpen
                ? 'translate-x-0'
                : '-translate-x-full md:translate-x-0',
        ]"
        aria-label="Sidenav"
        id="drawer-navigation"
    >
        <div class="h-full overflow-hidden py-5 px-3 bg-white dark:bg-gray-800">
            <!-- Close button for mobile -->
            <button
                v-if="isMobileMenuOpen"
                @click="$emit('toggle-mobile-menu')"
                class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg md:hidden"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    ></path>
                </svg>
            </button>

            <ul class="space-y-6 pt-[6px]">
                <li v-for="link in links" :key="link.url">
                    <Link
                        :href="link.url"
                        :title="!isOpen ? link.name : ''"
                        class="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all duration-300 relative"
                        :class="{
                            'bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 font-semibold text-primary-700 dark:text-primary-200':
                                isActive(link.url),
                            'hover:translate-x-1': !isActive(link.url),
                        }"
                        @click="handleLinkClick"
                    >
                        <span
                            v-if="isActive(link.url)"
                            class="absolute -left-0.5 w-1.5 h-8 rounded-r-full bg-primary-500"
                        ></span>
                        <component
                            :is="getIconComponent(link.icon)"
                            class="w-6 h-6 transition-all duration-300 z-10"
                            :class="{
                                'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white':
                                    !isActive(link.url),
                                'text-primary-600 dark:text-primary-400':
                                    isActive(link.url),
                            }"
                        />
                        <span
                            class="ml-3 transition-opacity duration-300"
                            :class="{
                                'opacity-0 absolute': !isOpen,
                                'opacity-100': isOpen,
                            }"
                            >{{ link.name }}</span
                        >
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
    HomeIcon,
    ChartBarIcon,
    FlagIcon,
    MegaphoneIcon,
    UsersIcon,
    DocumentTextIcon,
} from "@heroicons/vue/24/outline";


defineProps({
    links: {
        type: Array,
        required: true,
    },
    isOpen: {
        type: Boolean,
        default: true,
    },
    isMobileMenuOpen: {
        type: Boolean,
        default: false,
    },
});

const iconMap = {
    "md-dashboard": HomeIcon,
    "bi-graph-up": ChartBarIcon,
    "bi-flag-fill": FlagIcon,
    "bi-megaphone-fill": MegaphoneIcon,
    "fa-users": UsersIcon,
    "bi-file-earmark-text-fill": DocumentTextIcon,

};

const getIconComponent = (iconName) => {
    return iconMap[iconName] || HomeIcon;
};

const isActive = (url) => {
    return window.location.pathname === url;
};

// Destructure emit from defineEmits
const emit = defineEmits(["toggle-sidebar", "toggle-mobile-menu"]);

// Close mobile menu when a link is clicked
const handleLinkClick = () => {
    if (window.innerWidth < 768) {
        emit("toggle-mobile-menu");
    }
};
</script>

<style scoped>
aside {
    transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
}

a {
    transition: all 0.3s ease;
}

a:not(.bg-primary-50):hover {
    background: linear-gradient(
        90deg,
        rgba(59, 130, 246, 0.08) 0%,
        transparent 100%
    );
}

span {
    transition: opacity 0.2s ease-in-out;
}

/* Improved touch targets for mobile */
@media (max-width: 767px) {
    li {
        margin-bottom: 0.5rem;
    }

    a {
        padding: 1rem;
        min-height: 3.5rem;
    }

    .w-6.h-6 {
        width: 1.5rem;
        height: 1.5rem;
    }
}
</style>
