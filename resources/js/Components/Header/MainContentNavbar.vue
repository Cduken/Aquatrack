<template>
    <div class="relative">
        <nav class="sticky top-0 z-[100] bg-white/95 backdrop-blur-md border-b border-gray-100 px-4 sm:px-6 py-[26.5px] shadow-lg flex justify-between items-center"
            role="navigation" aria-label="Dashboard navigation">
            <!-- Left side - Logo and title -->
            <div class="flex items-center space-x-3 pl-10">

                <div class="flex items-center">


                    <span class="text-md font-medium text-gray-600 capitalize">
                        {{ currentPage }}
                    </span>
                </div>
            </div>

            <!-- Right side - Navigation controls -->
            <div class="flex items-center space-x-4">
                <!-- Notifications button -->
                <button v-if="showNotificationsButton" @click="openNotifications"
                    class="relative flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 focus:outline-none transition-all duration-200"
                    aria-label="View notifications">
                    <v-icon name="bi-bell" class="text-[1rem]" />
                    <span v-if="unreadCount > 0"
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full border border-white">
                        {{ unreadCount > 9 ? '9+' : unreadCount }}
                    </span>
                </button>

                <!-- User dropdown -->
                <div class="relative">
                    <button @click.stop="toggleDropdown"
                        class="flex items-center space-x-2 group focus:outline-none transition-all duration-200 px-2 py-1 rounded-lg hover:bg-gray-50"
                        aria-label="Toggle user menu" :aria-expanded="isDropdownOpen" ref="dropdownButton">
                        <div class="flex items-center space-x-2">
                            <div
                                class="relative w-8 h-8 rounded-lg overflow-hidden border border-gray-200 group-hover:border-blue-300 transition-colors duration-200">
                                <img v-if="user.avatar_url" :src="user.avatar_url" :alt="userDisplayName"
                                    class="w-full h-full object-cover">
                                <div v-else
                                    class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-400 text-white flex items-center justify-center font-medium text-sm">
                                    {{ userInitials }}
                                </div>
                            </div>
                            <span class="hidden md:inline text-sm font-medium text-gray-700">
                                {{ userDisplayName }}
                            </span>
                        </div>
                        <v-icon :name="isDropdownOpen ? 'bi-chevron-up' : 'bi-chevron-down'"
                            class="text-gray-400 text-xs transition-transform duration-200" />
                    </button>

                    <!-- Enhanced dropdown menu -->
                    <transition name="dropdown">
                        <div v-show="isDropdownOpen" v-click-outside="closeDropdown"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg z-50 border border-gray-100 overflow-hidden"
                            role="menu" aria-label="User menu" ref="dropdownMenu">
                            <div class="p-1">
                                <div
                                    class="px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ userDisplayName }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
                                </div>

                                <Link href="/profile"
                                    class="flex items-center w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150"
                                    role="menuitem">
                                <v-icon name="bi-gear" class="mr-3 text-blue-400" />
                                Account Settings
                                </Link>

                                <button @click.prevent="handleLogout"
                                    class="flex items-center w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150"
                                    role="menuitem">
                                    <v-icon name="bi-box-arrow-right" class="mr-3 text-red-400" />
                                    Sign Out
                                </button>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </nav>

        <NotificationModal v-if="showNotificationsButton" :isOpen="showNotifications" @close="closeNotifications"
            :initial-reports="initialReports" />
    </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import NotificationModal from '../Modals/NotificationModal.vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
    initialReports: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['logout']);

const { props: pageProps } = usePage();

// Get current page name from URL
const currentPage = computed(() => {
    const path = window.location.pathname;
    if (path === '/') return 'dashboard';
    if (path.startsWith('/reports')) return 'reports';
    if (path.startsWith('/users')) return 'users';
    if (path.startsWith('/staff')) return 'staff';
    if (path.startsWith('/analytics')) return 'analytics';
    if (path.startsWith('/profile')) return 'profile';
    return path.split('/').pop() || 'dashboard';
});

const isAdmin = computed(() => {
    return pageProps.auth?.user?.role === 'admin';
});

const isAdminRoute = computed(() => {
    return window.location.pathname.startsWith('/admin');
});

const showNotificationsButton = computed(() => {
    return isAdmin.value || isAdminRoute.value;
});

const showNotifications = ref(false);
const unreadCount = computed(() => {
    return props.initialReports.length;
});

const openNotifications = () => {
    showNotifications.value = true;
};

const closeNotifications = () => {
    showNotifications.value = false;
};

const user = computed(() => pageProps.auth?.user ?? {});

const userDisplayName = computed(() => {
    if (user.value?.name) return user.value.name;
    if (user.value?.email) return user.value.email.split('@')[0];
    return 'Guest';
});

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

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
    if (isDropdownOpen.value) {
        setTimeout(() => {
            dropdownMenu.value?.querySelector('a, button')?.focus();
        }, 0);
    }
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const handleLogout = () => {
    closeDropdown();
    emit('logout');
};

const handleKeydown = (e) => {
    if (e.key === 'Escape' && isDropdownOpen.value) {
        closeDropdown();
        dropdownButton.value?.focus();
    }
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
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.15s ease-out;
    transform-origin: top right;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-2px);
}

/* Water ripple effect on buttons */
button:active {
    transform: scale(0.98);
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

/* Subtle wave animation for the logo */
@keyframes wave {
    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-2px);
    }

    100% {
        transform: translateY(0);
    }
}

.wave-animate {
    animation: wave 2s ease-in-out infinite;
}
</style>
