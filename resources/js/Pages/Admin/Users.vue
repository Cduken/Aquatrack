<template>
    <AdminLayout>
        <div class="w-full max-w-4xl bg-white shadow-md rounded p-6">
            <h2 class="text-lg font-semibold mb-4">Welcome to AquaTrack Users!</h2>
            <p class="mb-2">Here’s a quick overview of the system:</p>

            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Total Registered Users: <strong>120</strong></li>
                <li>Pending Reports: <strong>8</strong></li>
                <li>System Status: <span class="text-green-600 font-semibold">All systems operational</span>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

</script>
