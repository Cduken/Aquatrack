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
        default: '#1e293b'
    },
    width: {
        type: String,
        default: '280px'
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

const toggleSidebar = () => {
    isOpen.value = !isOpen.value;
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
        <button class="sidebar-toggle" @click="toggleSidebar" :style="{
            left: position === 'left' ? (isOpen ? `calc(${width} + 20px)` : '20px') : 'auto',
            right: position === 'right' ? (isOpen ? `calc(${width} + 20px)` : '20px') : 'auto',
        }">
            <v-icon :name="isOpen ? 'bi-x' : 'bi-list'" />
        </button>

        <aside class="sidebar" :class="{
            'sidebar-open': isOpen,
            'sidebar-left': position === 'left',
            'sidebar-right': position === 'right'
        }" :style="{
            width: width,

            color: textColor
        }">
            <div class="sidebar-content">
                <div v-if="logo || $slots.logo" class="sidebar-logo">
                    <slot name="logo">
                        <h1 class="text-2xl font-bold text-gray-800">AquaTrack</h1>
                    </slot>
                </div>

                <div class="sidebar-section">

                    <nav class="sidebar-nav">
                        <slot name="links">
                            <template v-if="useRouterLinks">
                                <Link v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="sidebar-link" :class="{ 'active-link': link.active }">
                                <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                                {{ link.name }}
                                </Link>
                            </template>
                            <template v-else>
                                <a v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                    class="sidebar-link" :class="{ 'active-link': link.active }">
                                    <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                                    {{ link.name }}
                                </a>
                            </template>
                        </slot>
                    </nav>
                </div>
                <div class="mt-auto p-4 border-t border-gray-700">
                    <button @click="emit('logout')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        <v-icon name="bi-box-arrow-right" />
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" :class="{ 'overlay-visible': isOpen }" @click="toggleSidebar"></div>
    </div>
</template>

<style scoped>
.sidebar {
    background: #282829;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);

}

.sidebar-logo {
    padding: 15px 0;
    text-align: center;
    margin-bottom: 20px;
    background: #1c1c1c;
}

.sidebar-section {
    margin-bottom: 20px;
    padding: 0 15px;
}

.sidebar-section-title {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0 0 10px 0;
    margin-bottom: 10px;
    border-bottom: 1px solid #e2e8f0;
}

.sidebar-nav {
    gap: 5px;
}

.sidebar-link {
    padding: 10px 15px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 20px;
    text-decoration: none;
    margin-top: 10px;
    border-radius: 10px;
    color: white;
}

.sidebar-link:hover {
    background: rgb(91, 91, 93);
}

.active-link {
    background: rgb(91, 91, 93);
}

.link-icon {
    color: white;

}

.sidebar-wrapper {
    position: relative;
}

.sidebar-toggle {
    position: fixed;
    top: 20px;
    z-index: 1001;
    color: #2b2b2c;
    background: white;
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
    /* background: rgba(0, 0, 0, 0.1); */
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.overlay-visible {
    opacity: 1;
    visibility: visible;
}
</style>
