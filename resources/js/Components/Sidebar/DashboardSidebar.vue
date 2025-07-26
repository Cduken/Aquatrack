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
    <div class="sidebar-wrapper">
        <button v-if="!isMobile" class="sidebar-toggle" @click="toggleSidebar" :style="{
            left: position === 'left' ? (isOpen ? `calc(${width} + 20px)` : '20px') : 'auto',
            right: position === 'right' ? (isOpen ? `calc(${width} + 20px)` : '20px') : 'auto',
        }">
            <v-icon :name="isOpen ? 'bi-x' : 'bi-list'" />
        </button>

        <aside class="sidebar" :class="{
            'sidebar-open': isOpen,
            'sidebar-left': position === 'left',
            'sidebar-right': position === 'right',
            'sidebar-mobile': isMobile
        }" :style="{
            width: isMobile ? '100%' : width,
            color: textColor
        }">
            <div class="sidebar-content">
                <div v-if="!isMobile && (logo || $slots.logo)" class="sidebar-logo">
                    <slot name="logo">
                        <div class="logo-container">
                            <span class="logo-text">AquaTrack</span>
                        </div>
                    </slot>
                </div>

                <div class="sidebar-section">
                    <nav class="sidebar-nav" :class="{ 'mobile-nav': isMobile }">
                        <slot name="links">
                            <template v-if="useRouterLinks">
                                <Link v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="sidebar-link" :class="{ 'active-link': link.active }">
                                <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                                <span v-if="!isMobile || (isMobile && isOpen)">{{ link.name }}</span>
                                </Link>
                            </template>
                            <template v-else>
                                <a v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="sidebar-link" :class="{ 'active-link': link.active }">
                                    <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                                    <span v-if="!isMobile || (isMobile && isOpen)">{{ link.name }}</span>
                                </a>
                            </template>
                        </slot>
                    </nav>
                </div>
                <!-- <div v-if="!isMobile && showLogout" class="mt-auto p-4 border-t border-gray-700">
                    <button @click="emit('logout')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        <v-icon name="bi-box-arrow-right" />
                        <span>Logout</span>
                    </button>
                </div> -->
            </div>
        </aside>

        <div class="sidebar-overlay" :class="{ 'overlay-visible': isOpen && isMobile }" @click="toggleSidebar"></div>
    </div>
</template>

<style scoped>
.sidebar {
    background: #2D3E50;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-logo {
    padding: 5px;
    text-align: center;
    margin-bottom: 20px;
    background: #223241;
}

.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-text {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: bold;
}

.sidebar-section {
    padding: 0 15px;
}

.sidebar-nav {
    gap: 5px;
}

.sidebar-link {
    padding: 10px 10px;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    margin-top: 15px;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar-link:hover {
    background: #425262;
}

.active-link {
    background: #425262;
    font-weight: 500;
}

.link-icon {
    color: #ffffff;
    font-size: 1.2rem;
}

.sidebar-wrapper {
    position: relative;
}

.sidebar-toggle {
    position: fixed;
    top: 20px;
    z-index: 1001;
    color: #1e3a8a;
    background: #ffffff;
    border: none;
    padding: 10px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.sidebar-toggle:hover {
    background: #f1f5f9;
}

.sidebar {
    position: fixed;
    top: 0;
    height: 100vh;
    transition: all 0.3s ease;
    z-index: 1000;
    overflow-y: auto;
}

.sidebar-left {
    left: calc(-1 * v-bind(width));
}

.sidebar-right {
    right: calc(-1 * v-bind(width));
}

.sidebar-left.sidebar-open {
    left: 0;
}

.sidebar-right.sidebar-open {
    right: 0;
}

.sidebar-content {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* background: rgba(0, 0, 0, 0.5); */
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.overlay-visible {
    opacity: 1;
    visibility: visible;
}

/* Mobile styles */
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

    .sidebar-content {
        padding: 10px;
        min-height: 80px;
    }

    .sidebar-nav.mobile-nav {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        padding: 8px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #1e3a8a;
        border-top: 1px solid #3b82f6;
    }

    .sidebar-link {
        flex-direction: column;
        gap: 5px;
        padding: 8px 12px;
        margin-top: 0;
        font-size: 0.75rem;
        text-align: center;
    }

    .sidebar-link span {
        display: none;
    }

    .sidebar-toggle {
        display: none;
    }
}
</style>
