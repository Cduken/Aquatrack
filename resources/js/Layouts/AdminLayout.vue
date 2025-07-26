<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <DashboardSidebar @logout="logout" @toggle="sidebarOpen = $event" :links="adminLinks" :useRouterLinks="true"
            :defaultOpen="true" :textColor="'#ffffff'">
            <template #logo>
                <div class="flex items-center p-[6.6px]">
                    <img src="/images/MainLogo.png"
                        class="h-[70px] w-[70px] min-w-[50px] min-h-[50px] object-contain object-center" alt="">
                    <h1 class="text-white text-[22px] font-bold">AquaTrack</h1>
                </div>
            </template>
        </DashboardSidebar>

        <!-- Main Content -->
        <main :class="['main-content', { 'sidebar-open': sidebarOpen }]">
            <!-- Navbar -->
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
                                <a @click.prevent="logout"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cursor-pointer">Logout</a>
                            </div>
                        </transition>
                    </div>
                </div>
            </nav>

            <div class="content-container p-6">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';

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

const { props: pageProps } = usePage();
const user = computed(() => pageProps.auth?.user);

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
});

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

const sidebarOpen = ref(true);
const isDropdownOpen = ref(false);

const adminLinks = ref([
    { name: 'Dashboard', url: '/admin/dashboard', icon: 'md-dashboard' },
    { name: 'Reports', url: '/admin/reports', icon: 'bi-flag-fill' },
    { name: 'Records', url: '/admin/records', icon: 'bi-file-earmark-text-fill' },
    { name: 'Announcements', url: '/admin/announcements', icon: 'bi-megaphone-fill' },
    { name: 'Users', url: '/admin/users', icon: 'fa-users' },
    { name: 'Staff', url: '/admin/staff', icon: 'hi-solid-user' },
]);

const form = useForm({});

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const logout = () => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will be logged out of the system.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('logout'), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Logged out',
                        toast: true,
                        text: 'You have been successfully logged out.',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = route('login');
                    });
                },
                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout failed',
                        text: 'Please try again.',
                    });
                }
            });
        }
    });
};

// Handle escape key
const handleKeydown = (e) => {
    if (e.key === 'Escape' && isDropdownOpen.value) {
        closeDropdown();
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
.admin-layout {
    display: flex;
    min-height: 100vh;
    position: relative;
    background: #f9f9f9;
}

.main-content {
    flex-grow: 1;
    transition: all 0.3s ease;
    width: 100%;
}

.main-content.sidebar-open {
    margin-left: 0;
}

.content-container {
    max-width: 1200px;
    margin: 0 auto;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    text-align: left;
    font-weight: 600;
    color: #4b5563;
}

td {
    text-align: left;
    vertical-align: middle;
}

/* Dropdown transitions */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

@media (min-width: 641px) {
    .main-content.sidebar-open {
        margin-left: 250px;
        width: calc(100% - 250px);
    }
}

@media (max-width: 640px) {
    .main-content {
        margin-bottom: 60px;
    }

    nav {
        padding: 8px;
    }

    h1 {
        font-size: 1.2rem;
    }

    button {
        padding: 4px 8px;
        font-size: 0.9rem;
    }
}
</style>
