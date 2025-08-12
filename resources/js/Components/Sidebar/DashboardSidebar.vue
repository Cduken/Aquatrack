<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    logo: {
        type: String,
        default: ''
    },
    title: {
        type: String,
        default: 'Dashboard'
    },
    textColor: {
        type: String,
        default: '#ffffff'
    },
    width: {
        type: String,
        default: '250px'
    },
    position: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'right'].includes(value)
    },
    links: {
        type: Array,
        default: () => [],
        validator: (links) => {
            return links.every(link =>
                typeof link.name === 'string' &&
                typeof link.url === 'string' &&
                (link.icon ? typeof link.icon === 'string' : true) &&
                (link.active ? typeof link.active === 'boolean' : true)
            )
        }
    },
    useRouterLinks: {
        type: Boolean,
        default: false
    },
    showLogout: {
        type: Boolean,
        default: true
    },
    defaultOpen: {
        type: Boolean,
        default: true
    }
});

const isOpen = ref(props.defaultOpen);
const isMobile = ref(window.innerWidth <= 640);

const handleResize = () => {
    isMobile.value = window.innerWidth <= 640;
    if (isMobile.value) {
        isOpen.value = true;
    }
};

window.addEventListener('resize', handleResize);

const toggleSidebar = () => {
    if (!isMobile.value) {
        isOpen.value = !isOpen.value;
    }
    emit('toggle', isOpen.value);
};

defineExpose({ toggleSidebar });
const emit = defineEmits(['logout', 'toggle']);

const activeLinks = computed(() => {
    if (!props.useRouterLinks) return props.links;

    return props.links.map(link => ({
        ...link,
        active: window.location.pathname === link.url
    }));
});
</script>

<template>
    <div class="relative">
        <!-- External Toggle Button (visible when sidebar is open) -->
        <button v-if="!isMobile && isOpen"
            class="fixed top-5 z-[1001] text-white bg-gradient-to-br from-blue-500 to-teal-500 border-none rounded-lg flex items-center cursor-pointer text-xl transition-all shadow-md hover:bg-gradient-to-br hover:from-blue-600 hover:to-teal-600 p-2"
            @click="toggleSidebar" :style="{
                left: position === 'left' ? `calc(${width} - 30px)` : 'auto',
                right: position === 'right' ? `calc(${width} - 30px)` : 'auto',
            }">
            <v-icon class="w-6 h-6 text-white" name="bi-chevron-double-left" />
        </button>

        <aside
            class="fixed top-0 h-screen transition-all z-[1000] overflow-y-auto bg-gradient-to-br from-blue-500 to-teal-500 shadow-lg"
            :class="{
                'sidebar-open': isOpen,
                'sidebar-left': position === 'left',
                'sidebar-right': position === 'right',
                'sidebar-mobile': isMobile,
                'w-20': !isOpen && !isMobile,
                'w-[250px]': isOpen && !isMobile,
            }">
            <div class="h-full flex flex-col">
                <!-- Internal Toggle Button (visible when sidebar is closed) -->
                <button v-if="!isMobile && !isOpen"
                    class="p-[30.5px] shadow-lg text-white flex items-center justify-center cursor-pointer transition-all hover:bg-white/20"
                    @click="toggleSidebar">
                    <v-icon class="w-8 h-8 text-white" name="bi-chevron-double-right" />
                </button>

                <!-- Logo section (only visible when open) -->
                <div v-if="!isMobile && (logo || $slots.logo) && isOpen"
                    class="p-[5.5px] text-center mb-5 bg-blue-600/30 shadow-lg">
                    <slot name="logo">
                        <div class="flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">AquaTrack</span>
                        </div>
                    </slot>
                </div>

                <div class="px-4">
                    <nav class="flex flex-col gap-4 mt-2" :class="{ 'mobile-nav': isMobile }">
                        <slot name="links">
                            <template v-if="useRouterLinks">
                                <Link v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="flex items-center gap-3 px-3 py-3 text-white no-underline rounded-lg transition-colors hover:bg-white/20 group"
                                    :class="{ 'bg-white/30 font-medium': link.active }">
                                <div class="relative">
                                    <v-icon v-if="link.icon" :name="link.icon"
                                        class="text-white text-xl min-w-[24px]" />
                                    <!-- Tooltip for closed state -->
                                    <span v-if="!isOpen && !isMobile"
                                        class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1 text-sm whitespace-nowrap bg-gray-800 text-white rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg z-[1001] pointer-events-none">
                                        {{ link.name }}
                                        <div
                                            class="absolute right-full top-1/2 w-2 h-2 -mt-1 bg-gray-800 transform rotate-45">
                                        </div>
                                    </span>
                                </div>
                                <span v-if="isOpen" class="text-sm whitespace-nowrap">{{ link.name }}</span>
                                </Link>
                            </template>
                            <template v-else>
                                <a v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="flex items-center gap-3 px-3 py-3 text-white no-underline rounded-lg transition-colors hover:bg-white/20 group"
                                    :class="{ 'bg-white/30 font-medium': link.active }">
                                    <div class="relative">
                                        <v-icon v-if="link.icon" :name="link.icon"
                                            class="text-white text-xl min-w-[24px]" />
                                        <!-- Tooltip for closed state -->
                                        <span v-if="!isOpen && !isMobile"
                                            class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1 text-sm whitespace-nowrap bg-gray-800 text-white rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg z-[1001] pointer-events-none">
                                            {{ link.name }}
                                            <div
                                                class="absolute right-full top-1/2 w-2 h-2 -mt-1 bg-gray-800 transform rotate-45">
                                            </div>
                                        </span>
                                    </div>
                                    <span v-if="isOpen" class="text-sm whitespace-nowrap">{{ link.name }}</span>
                                </a>
                            </template>
                        </slot>
                    </nav>
                </div>
            </div>
        </aside>

        <div class="fixed inset-0 z-[999] transition-all opacity-0 invisible"
            :class="{ 'opacity-100 visible': isOpen && isMobile }" @click="toggleSidebar"></div>
    </div>
</template>

<style scoped>
/* Mobile styles - keep exactly the same as before */
@media (max-width: 640px) {
    .sidebar.sidebar-mobile {
        width: 100% !important;
        height: auto;
        bottom: 0;
        top: auto;
        left: 0;
        right: 0;
        transform: translateY(0);
        border-radius: 12px 12px 0 0;
        z-index: 1000;
    }

    .sidebar-mobile .flex-col {
        padding: 10px;
        min-height: 80px;
    }

    .mobile-nav {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        padding: 8px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to right, #3b82f6, #06b6d4);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .mobile-nav a {
        flex-direction: column;
        gap: 5px;
        padding: 8px 12px;
        margin-top: 0;
        font-size: 0.75rem;
        text-align: center;
    }

    .mobile-nav span {
        display: none;
    }
}
</style>
