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
            <!-- Navbar Component -->
            <MainContentNavbar :title="title" @logout="logout" />

            <div class="content-container p-6 w-full">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';
import MainContentNavbar from '@/Components/Header/MainContentNavbar.vue';
import { ref, computed } from 'vue';

const { props: pageProps } = usePage();
const user = computed(() => pageProps.auth?.user);

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
});

const sidebarOpen = ref(true);

const adminLinks = ref([
    { name: 'Dashboard', url: '/customer/dashboard', icon: 'md-dashboard' },
    { name: 'Usage', url: '/customer/usage', icon: 'bi-graph-up' },
    { name: 'Reports', url: '/customer/reports', icon: 'bi-flag-fill' },
    { name: 'Announcements', url: '/customer/announcements', icon: 'bi-megaphone-fill' },
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
    width: 100%;
    padding: 1.5rem;
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
}
</style>
