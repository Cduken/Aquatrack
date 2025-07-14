<template>
    <MainLayout>
        <DashboardSidebar @logout="logout" :links="adminLinks" :useRouterLinks="true" :defaultOpen="true">
            <template #default>
                <div class="flex flex-col items-center pt-10">
                    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

                </div>
            </template>

            <!-- Optional: Custom footer content -->
            <template #footer>
                <button @click="logout"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center justify-center gap-2"
                    :disabled="form.processing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <span v-if="form.processing">Logging out...</span>
                    <span v-else>Logout</span>
                </button>
            </template>
        </DashboardSidebar>
    </MainLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import MainLayout from '@/Layouts/MainLayout.vue';
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';

import { ref } from 'vue';

const form = useForm({});

const adminLinks = ref([
    { name: 'Dashboard', url: '/admin/dashboard', icon: 'bi-speedometer2' },
    { name: 'Users', url: '/admin/users', icon: 'hi-solid-user' },
    { name: 'Settings', url: '/admin/settings', icon: 'bi-gear' },
    { name: 'Reports', url: '/admin/reports', icon: 'bi-file-earmark-text' }
]);


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
