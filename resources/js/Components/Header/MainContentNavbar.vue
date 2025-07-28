<template>
    <nav class="bg-white shadow-md border-b px-4 py-[30.5px] flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800 ml-14">{{ title }}</h1>
        <div class="flex items-center space-x-4">
            <button class="relative text-gray-600 hover:text-gray-900">
                <v-icon name="bi-bell-fill" class="text-xl" />
                <span
                    class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">3</span>
            </button>
            <div class="relative">
                <button @click.stop="toggleDropdown"
                    class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <span class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        {{ userInitials }}
                    </span>
                    <span class="hidden md:inline">{{ userDisplayName }}</span>
                    <v-icon name="bi-chevron-down" class="text-sm" />
                </button>
                <transition name="dropdown">
                    <div v-show="isDropdownOpen" v-click-outside="closeDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <a @click.prevent="handleLogout"
                            class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cursor-pointer">Logout</a>
                    </div>
                </transition>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
});

const emit = defineEmits(['logout']);

const { props: pageProps } = usePage();
const user = computed(() => pageProps.auth?.user);

const userDisplayName = computed(() => {
    if (user.value?.name) return user.value.name;
    if (user.value?.email) return user.value.email;
    return 'Account';
});

const userInitials = computed(() => {
    if (user.value?.name) {
        return user.value.name
            .split(' ')
            .map(n => n[0])
            .join('')
            .toUpperCase();
    }
    if (user.value?.email) {
        return user.value.email[0].toUpperCase();
    }
    return 'A';
});

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const handleLogout = () => {
    closeDropdown();
    emit('logout');
};

// Handle escape key
const handleKeydown = (e) => {
    if (e.key === 'Escape' && isDropdownOpen.value) {
        closeDropdown();
    }
};

// Custom directive for click outside
const vClickOutside = {
    beforeMount(el, binding) {
        el.clickOutsideEvent = function (event) {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    }
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
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
