<template>
    <AdminLayout title="Dashboard">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <v-icon name="bi-people" class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-500 text-sm font-medium">Total Users</p>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-bold text-gray-800">{{ totalUsers }}</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-600 flex items-center gap-1">
                                <v-icon name="bi-arrow-up" class="w-3 h-3" /> 4.5%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Staff -->
            <div class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-green-50 text-green-600">
                        <v-icon name="bi-person-badge" class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-500 text-sm font-medium">Active Staff</p>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-bold text-gray-800">{{ totalStaffs }}</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-600 flex items-center gap-1">
                                <v-icon name="bi-arrow-up" class="w-3 h-3" /> 2.3%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports -->
            <div class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                        <v-icon name="bi-flag" class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-500 text-sm font-medium">Reports</p>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-bold text-gray-800">{{ totalReports }}</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-600 flex items-center gap-1">
                                <v-icon name="bi-arrow-down" class="w-3 h-3" /> 1.2%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers -->
            <div class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                        <v-icon name="bi-person-lines-fill" class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-500 text-sm font-medium">Customers</p>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-bold text-gray-800">{{ totalCustomers }}</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-600 flex items-center gap-1">
                                <v-icon name="bi-arrow-up" class="w-3 h-3" /> 8.7%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Analytics Section -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 mb-6 overflow-hidden">
            <div class="p-6 pb-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <v-icon name="bi-droplet-half" class="text-blue-500 w-5 h-5" />
                            Water Consumption Analytics
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Last 12 months consumption trend</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-1.5">
                            <v-icon name="bi-calendar" class="text-gray-500 w-4 h-4" />
                            <span class="text-sm font-medium text-gray-700">This Year</span>
                            <v-icon name="bi-chevron-down" class="text-gray-500 w-3 h-3 ml-1" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Chart -->
                    <div class="lg:col-span-2">
                        <div class="h-80">
                            <canvas ref="waterChart"></canvas>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div class="space-y-5">
                        <div class="bg-blue-50 rounded-xl p-4 shadow-2xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Current Month</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">52 <span class="text-sm text-gray-500">m³</span></p>
                                </div>
                                <div class="text-green-500 bg-white rounded-full p-2">
                                    <v-icon name="bi-arrow-up" class="w-4 h-4" />
                                </div>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">+12% vs last month</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 shadow-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Average</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">45 <span class="text-sm text-gray-500">m³</span></p>
                                </div>
                                <div class="text-gray-500 bg-white rounded-full p-2">
                                    <v-icon name="bi-dash" class="w-4 h-4" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Monthly average consumption</p>
                        </div>

                        <div class="border border-gray-200 rounded-xl p-4 shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Peak Usage</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">68 <span class="text-sm text-gray-500">m³</span></p>
                                </div>
                                <div class="text-amber-500 bg-white rounded-full p-2">
                                    <v-icon name="bi-exclamation-triangle" class="w-4 h-4" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Recorded in June 2023</p>
                        </div>
                    </div>
                </div>
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
    new Chart(waterChart.value, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Water Consumption (m³)',
                data: [42, 38, 45, 48, 52, 68, 62, 58, 53, 49, 46, 44],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgb(59, 130, 246)',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'white',
                    titleColor: '#1f2937',
                    bodyColor: '#4b5563',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    usePointStyle: true,
                    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.08)',
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} m³`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        drawBorder: false,
                        color: '#f3f4f6'
                    },
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return value + ' m³';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#9ca3af'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>

<style scoped>
.shadow-xs {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.hover\:shadow-sm:hover {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}
</style>
