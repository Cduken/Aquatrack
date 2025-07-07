<template>
    <div class="flex flex-col items-center mt-10">
        <h1 class="text-2xl font-bold mb-6">Staff Dashboard</h1>
        <button @click="logout" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
            Logout
        </button>
    </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({});

function logout() {
    form.post(route('logout'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Logged out',
                text: 'You have been successfully logged out.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                router.visit(route('login'));
            });
        },
            onError: (errors) => {
                console.error('Logout failed:', errors);
            }
        });
    }
</script>
