```vue
<template>
    <div class="antialiased min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
        <DashboardSidebar :links="staffLinks" :is-open="isSidebarOpen" @toggle-sidebar="toggleSidebar" />
        <MainContentNavbar :isSidebarOpen="isSidebarOpen" @toggle-sidebar="isSidebarOpen = !isSidebarOpen"
            @logout="handleLogout" />
        <main
            :class="['p-4 pt-20 flex-1 transition-all duration-300 ease-in-out', { 'md:ml-64': isSidebarOpen, 'md:ml-16': !isSidebarOpen }]">
            <slot />
        </main>
    </div>
</template>

<script setup>
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';
import MainContentNavbar from '@/Components/Header/MainContentNavbar.vue';
import { ref, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';
import Swal from 'sweetalert2';

const isSidebarOpen = ref(true);

const staffLinks = [
    { name: 'Dashboard', url: '/staff/dashboard', icon: 'md-dashboard' },
    { name: 'Reading', url: '/staff/reading', icon: 'bi-flag-fill' },
];

const form = useForm({});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const handleLogout = () => {
    form.post(route('logout'), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('select-roles'));
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Logout failed',
                text: 'Please try again.',
                background: '#f8f9fa',
                customClass: {
                    title: 'text-lg font-medium',
                    popup: 'dark:bg-gray-800 dark:text-white',
                },
            });
        },
    });
};

onMounted(() => {
    initFlowbite();
});
</script>
```
