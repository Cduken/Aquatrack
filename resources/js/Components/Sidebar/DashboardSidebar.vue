<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3'; // Import Inertia's Link component

const props = defineProps({
    logo: {
        type: String,
        default: ''
    },
    title: {
        type: String,
        default: 'Dashboard'
    },
    bgColor: {
        type: String,
        default: '#1e88e5'
    },
    textColor: {
        type: String,
        default: 'white'
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
};

defineExpose({ toggleSidebar });
const emit = defineEmits(['logout']);

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
            backgroundColor: bgColor
        }">
            <v-icon :name="isOpen ? 'bi-x' : 'bi-list'" />
        </button>

        <aside class="sidebar" :class="{
            'sidebar-open': isOpen,
            'sidebar-left': position === 'left',
            'sidebar-right': position === 'right'
        }" :style="{
        width: width,
        backgroundColor: bgColor,
        color: textColor
    }">
            <div class="sidebar-content">
                <div v-if="logo || $slots.logo" class="sidebar-logo">
                    <slot name="logo">
                        <img :src="logo" alt="Logo" class="logo-image" v-if="logo">
                        <h1 v-else class="text-2xl font-bold">{{ title }}</h1>
                    </slot>
                </div>

                <nav class="sidebar-nav">
                    <slot name="links">
                        <template v-if="useRouterLinks">
                            <Link v-for="(link, index) in activeLinks" :key="index" :href="link.url"
                                class="sidebar-link" :class="{ 'active-link': link.active }">
                            <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                            {{ link.name }}
                            <span v-if="link.active" class="checkmark">✓</span>
                            </Link>
                        </template>
                        <template v-else>
                            <a v-for="(link, index) in activeLinks" :key="index" :href="link.url" class="sidebar-link"
                                :class="{ 'active-link': link.active }">
                                <v-icon v-if="link.icon" :name="link.icon" class="link-icon" />
                                {{ link.name }}
                                <span v-if="link.active" class="checkmark">✓</span>
                            </a>
                        </template>
                    </slot>
                </nav>

                <div class="sidebar-footer" v-if="showLogout">
                    <slot name="footer">
                        <button @click="emit('logout')" class="logout-button">
                            <v-icon name="BiBoxArrowRight" />
                            Logout
                        </button>
                    </slot>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" :class="{ 'overlay-visible': isOpen }" @click="toggleSidebar"></div>
    </div>
</template>

<style scoped>
/* Your existing styles remain the same */
.sidebar-wrapper {
    position: relative;
}

.sidebar-toggle {
    position: fixed;
    top: 20px;
    z-index: 1001;
    color: v-bind(textColor);
    border: none;
    padding: 10px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.sidebar-toggle:hover {
    opacity: 0.9;
    transform: scale(1.05);
}

.sidebar {
    position: fixed;
    top: 0;
    height: 100vh;
    transition: all 0.3s ease;
    z-index: 1000;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to bottom, #1e88e5, #0d47a1);
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
    padding: 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-logo {
    padding: 20px 0;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 30px;
}

.logo-image {
    max-width: 80%;
    max-height: 60px;
    object-fit: contain;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex-grow: 1;
}

.sidebar-link {
    color: v-bind(textColor);
    text-decoration: none;
    padding: 12px 15px;
    border-radius: 6px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.95rem;
    position: relative;
}

.sidebar-link:hover {
    background: rgba(255, 255, 255, 0.15);
}

.active-link {
    background: rgba(255, 255, 255, 0.25);
    font-weight: 500;
}

.active-link::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: white;
    border-radius: 0 4px 4px 0;
}

.link-icon {
    font-size: 1.1em;
}

.checkmark {
    margin-left: auto;
    font-weight: bold;
}

.sidebar-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.logout-button {
    width: 100%;
    padding: 10px 15px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 0.9rem;
}

.logout-button:hover {
    background: rgba(255, 255, 255, 0.2);
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
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
