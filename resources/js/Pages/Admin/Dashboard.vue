<template>
    <AdminLayout title="Dashboard">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Users -->
            <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <v-icon name="hi-user-group" scale="1.5" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Total Users</h2>
                    <div class="text-2xl font-bold">{{ totalUsers }}</div>
                </div>
            </div>

            <!-- Active Staff -->
            <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <v-icon name="bi-person-badge" scale="1.5" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Active Staff</h2>
                    <div class="text-2xl font-bold">{{ totalStaffs }}</div>
                </div>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <v-icon name="bi-flag-fill" scale="1.5" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Reports</h2>
                    <div class="text-2xl font-bold">{{ totalReports }}</div>
                </div>
            </div>

            <!-- Last 7 Days -->
            <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <v-icon name="bi-calendar" scale="1.5" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Customers</h2>
                    <div class="text-2xl font-bold">{{ totalCustomers }}</div>
                </div>
            </div>
        </div>

        <!-- Water Consumption Analytics -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Water Consumption Analytics</h2>
            <div class="h-64">
                <canvas ref="waterChart"></canvas>
            </div>
            <div class="mt-4 text-center text-sm text-gray-500">
                Average Consumption: <span class="font-semibold">45 m³</span>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';

const waterChart = ref(null);

const page = usePage();
const totalUsers = page.props.total_users ?? 0;
const totalStaffs = page.props.total_staffs ?? 0;
const totalReports = page.props.total_reports ?? 0;
const totalCustomers = page.props.total_customers ?? 0;


onMounted(() => {
    // Water Consumption Chart
    new Chart(waterChart.value, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Water Consumption (m³)',
                data: [30, 40, 35, 50, 45, 55, 50],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'm³'
                    }
                }
            }
        }
    });
});
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
