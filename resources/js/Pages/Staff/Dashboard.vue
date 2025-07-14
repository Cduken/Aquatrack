<template>
    <div class="flex flex-col items-center mt-10">
        <h1 class="text-2xl font-bold mb-6">Staff Dashboard</h1>
        <button @click="confirmLogout" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
            Logout
        </button>
    </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({});

function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You'll be logged out of the system!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancel',
        backdrop: true,
        allowOutsideClick: false,
        allowEscapeKey: true
    }).then((result) => {
        if (result.isConfirmed) {
            logout();
        }
    });
}

function logout() {
    form.post(route('logout'), {
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
                router.visit(route('login'));
            });
        },
        onError: (errors) => {
            console.error('Logout failed:', errors);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Logout failed',
                toast: true,
                text: 'There was an error logging out. Please try again.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>
