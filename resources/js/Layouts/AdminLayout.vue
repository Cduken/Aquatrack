<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <DashboardSidebar @logout="logout" @toggle="sidebarOpen = $event" :links="adminLinks" :useRouterLinks="true"
            :defaultOpen="true">
            <template #logo>
                <AquatrackLogo class="h-16 w-auto" />
            </template>
        </DashboardSidebar>

        <!-- Main Content -->
        <main :class="['main-content', { 'sidebar-open': sidebarOpen }]">
            <!-- Navbar -->
            <nav class="bg-white shadow-lg border-b px-6 py-[32.5px]">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-800 ml-14">{{ title }}</h1>
                    <div class="flex items-center space-x-4">
                        <!-- User dropdown or other navbar items can go here -->

                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-6">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';
import AquatrackLogo from '@/Components/AquatrackLogo.vue';
import { ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
});

const sidebarOpen = ref(true);

const adminLinks = ref([
    { name: 'Dashboard', url: '/admin/dashboard', icon: 'bi-speedometer2' },
    { name: 'Users', url: '/admin/users', icon: 'hi-solid-user' },
    { name: 'Reports', url: '/admin/reports', icon: 'bi-file-earmark-text' },
    { name: 'Records', url: '/admin/records', icon: 'bi-file-earmark-text' },
    { name: 'Announcements', url: '/admin/announcements', icon: 'bi-file-earmark-text' },
    { name: 'Staff', url: '/admin/staff', icon: 'hi-solid-user' },
]);

const form = useForm({});

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
</script>

<style scoped>
.admin-layout {
    display: flex;
    min-height: 100vh;
    position: relative;
}

.main-content {
    flex-grow: 1;
    transition: margin-left 0.3s ease;
    margin-left: 0;
    width: 100%;
}

.main-content.sidebar-open {
    margin-left: 280px;
    width: calc(100% - 280px);
}
</style>
