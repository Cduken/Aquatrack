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
        <main :class="['main-content', { 'sidebar-open': sidebarOpen, 'sidebar-closed': !sidebarOpen }]">
            <!-- Water background effect -->
            <div class="water-bg"></div>

            <!-- Sticky Navbar Container -->
            <div class="sticky-nav-container">
                <MainContentNavbar :title="title" @logout="logout" />
            </div>

            <!-- Scrollable Content Area -->
            <div class="content-container">
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
import { router } from '@inertiajs/vue3';

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
    { name: 'Dashboard', url: '/staff/dashboard', icon: 'md-dashboard' },
    { name: 'Reading', url: '/staff/reading', icon: 'bi-flag-fill' },
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
                        router.visit(route('home'));
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
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    height: 100vh;
    overflow: hidden;
    transition: all 0.3s ease;
    width: 100%;
    position: relative;
}

.sticky-nav-container {
    position: sticky;
    top: 0;
    z-index: 10;
    width: 100%;
}

.content-container {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    position: relative;
}



@media (min-width: 641px) {
    .main-content.sidebar-open {
        margin-left: 250px;
        width: calc(100% - 250px);
    }

    .main-content.sidebar-closed {
        margin-left: 80px;
        width: calc(100% - 80px);
    }
}

@media (max-width: 640px) {
    .main-content {
        margin-bottom: 60px;
        margin-left: 0 !important;
        width: 100% !important;
    }

    .water-bg {
        height: 40vh;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
    }
}
</style>
