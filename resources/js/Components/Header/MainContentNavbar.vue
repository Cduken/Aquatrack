<template>
    <!-- Wrapper with relative positioning -->
    <div class="relative">
        <nav class="sticky top-0 z-[100] bg-white/95 backdrop-blur-md shadow-md border-b border-gray-200 px-4 py-[26px] flex justify-between items-center"
            role="navigation" aria-label="Dashboard navigation">
            <!-- Title -->
            <h1
                class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 ml-4">
                {{ title }}
            </h1>

            <!-- Right section -->
            <div class="flex items-center space-x-4">
                <!-- Notification Button -->
                <button
                    class="relative text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 transition-colors duration-200"
                    aria-label="View notifications (3 unread)">
                    <v-icon name="bi-bell-fill" class="text-xl" />
                    <span
                        class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                        3
                    </span>
                </button>

                <!-- User Dropdown -->
                <div class="relative">
                    <button @click.stop="toggleDropdown"
                        class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-1 transition-colors duration-200"
                        aria-label="Toggle user menu" :aria-expanded="isDropdownOpen" ref="dropdownButton">
                        <!-- Updated Avatar/Initials Display -->
                        <div class="relative w-8 h-8 rounded-full overflow-hidden">
                            <img v-if="user.avatar_url" :src="user.avatar_url" :alt="userDisplayName"
                                class="w-full h-full object-cover">
                            <div v-else
                                class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-500 text-white flex items-center justify-center font-medium">
                                {{ userInitials }}
                            </div>
                        </div>
                        <span class="hidden md:inline text-sm font-medium">{{
                            userDisplayName
                            }}</span>
                        <v-icon :name="isDropdownOpen ? 'bi-chevron-up' : 'bi-chevron-down'" class="text-sm" />
                    </button>

                    <!-- Dropdown Menu -->
                    <transition name="dropdown">
                        <div v-show="isDropdownOpen" v-click-outside="closeDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl z-50"
                            role="menu" aria-label="User menu" ref="dropdownMenu">
                            <Link href="/profile"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 focus:bg-blue-50 hover:text-blue-700 focus:text-blue-700 transition-colors duration-150"
                                role="menuitem">Settings</Link>
                            <hr class="border-gray-200" />
                            <button @click.prevent="handleLogout"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 focus:bg-red-50 hover:text-red-700 focus:text-red-700 transition-colors duration-150"
                                role="menuitem">
                                Logout
                            </button>
                        </div>
                    </transition>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const emit = defineEmits(['logout']);

const { props: pageProps } = usePage();

const user = computed(() => pageProps.auth?.user ?? {});

// Compute user display name with fallback
const userDisplayName = computed(() => {
    if (user.value?.name) return user.value.name;
    if (user.value?.email) return user.value.email.split('@')[0];
    return 'Guest';
});

// Compute user initials with fallback
const userInitials = computed(() => {
    if (user.value?.name) {
        return user.value.name
            .split(' ')
            .map((n) => n[0])
            .join('')
            .toUpperCase()
            .slice(0, 2);
    }
    if (user.value?.email) {
        return user.value.email[0].toUpperCase();
    }
    return 'G';
});

const isDropdownOpen = ref(false);
const dropdownButton = ref(null);
const dropdownMenu = ref(null);

// Toggle dropdown
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
    if (isDropdownOpen.value) {
        // Focus first menu item when dropdown opens
        setTimeout(() => {
            dropdownMenu.value?.querySelector('a, button')?.focus();
        }, 0);
    }
};

// Close dropdown
const closeDropdown = () => {
    isDropdownOpen.value = false;
};

// Handle logout
const handleLogout = () => {
    closeDropdown();
    emit('logout');
};

// Handle escape key and focus trapping
const handleKeydown = (e) => {
    if (e.key === 'Escape' && isDropdownOpen.value) {
        closeDropdown();
        dropdownButton.value?.focus();
    }
    // Trap focus within dropdown
    if (isDropdownOpen.value && e.key === 'Tab') {
        const focusableElements = dropdownMenu.value?.querySelectorAll(
            'a[href], button:not([disabled])'
        );
        if (focusableElements?.length) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }
};

// Custom directive for click outside
const vClickOutside = {
    beforeMount(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener('click', el.clickOutsideEvent, { passive: true });
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    },
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
/* Dropdown transition */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.95);
}

/* Pulse animation for notification badge */
@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(1.15);
        opacity: 0.8;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

/* Focus styles for accessibility */
button:focus,
a:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}
</style>
