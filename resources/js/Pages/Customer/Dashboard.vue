<template>
    <CustomerLayout title="Dashboard">
        <div class="w-full bg-white rounded-lg">


            <!-- Main Content -->
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <v-icon name="bi-house-door-fill" /> Dashboard
                </h2>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="border rounded-lg p-6">
                        <h3 class="text-gray-500 mb-2 flex items-center gap-2">
                            <v-icon name="bi-calendar-fill" /> Monthly Reading
                        </h3>
                        <p class="text-3xl font-bold flex items-center gap-1">
                            <v-icon name="bi-water" class="text-blue-500" /> {{ monthlyUsage }} m³
                        </p>
                    </div>
                    <div class="border rounded-lg p-6">
                        <h3 class="text-gray-500 mb-2 flex items-center gap-2">
                            <v-icon name="bi-cash-stack" /> Current Bill
                        </h3>
                        <p class="text-3xl font-bold">₱{{ currentBill.toFixed(2) }}</p>
                    </div>
                    <div class="border rounded-lg p-6">
                        <h3 class="text-gray-500 mb-2 flex items-center gap-2">
                            <v-icon name="bi-bell-fill" /> Unread Announcements
                        </h3>
                        <p class="text-3xl font-bold">{{ announcements }}</p>
                    </div>
                </div>

                <!-- Water Consumption Analytics -->
                <div class="border rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <v-icon name="bi-graph-up" /> Water Consumption Analytics
                        </h3>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-1">
                                <v-icon name="bi-circle-fill" class="text-blue-500 text-xs" />
                                <span class="text-sm">Your Usage</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <v-icon name="bi-circle-fill" class="text-gray-400 text-xs" />
                                <span class="text-sm">Area Avg</span>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-md font-medium mb-2 flex items-center gap-2">
                        <v-icon name="bi-calendar-week-fill" /> Monthly Water Consumption
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <v-icon
                                :name="yearlyConsumption > areaAverage ? 'bi-arrow-up-circle-fill' : 'bi-arrow-down-circle-fill'"
                                :class="yearlyConsumption > areaAverage ? 'text-green-500' : 'text-red-500'" />
                            <p class="text-gray-600">Year Consumption: <span class="font-bold">{{ yearlyConsumption }}
                                    m³</span></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <v-icon name="bi-dash-circle-fill" class="text-yellow-500" />
                            <p class="text-gray-600">Area Average: <span class="font-bold">{{ areaAverage }}
                                    m³</span></p>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="h-64 mt-6">
                        <canvas ref="waterChart"></canvas>
                    </div>
                </div>

                <!-- Yield Consumption -->
                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <v-icon name="bi-bar-chart-fill" /> Meter Readings (m³)
                    </h3>
                    <div class="h-64">
                        <canvas ref="yieldChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';

const page = usePage();
const announcements = page.props.announcements ?? 0;
const monthlyUsage = page.props.monthlyUsage ?? 0;
const currentBill = page.props.currentBill ?? 0;
const yearlyConsumption = page.props.yearlyConsumption ?? 0;
const areaAverage = page.props.areaAverage ?? 0;
const chartData = page.props.chartData ?? [];

const waterChart = ref(null);
const yieldChart = ref(null);

onMounted(() => {
    // Prepare labels and data for charts
    const labels = chartData.map(item => item.month);
    const consumptionData = chartData.map(item => item.consumption);
    const readingData = chartData.map(item => item.reading);

    // Area average data (simplified - would need proper implementation)
    const areaAvgData = Array(consumptionData.length).fill(areaAverage / 12);

    // Water Consumption Chart
    new Chart(waterChart.value, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Your Consumption',
                data: consumptionData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'Area Average',
                data: areaAvgData,
                borderColor: 'rgb(156, 163, 175)',
                backgroundColor: 'rgba(156, 163, 175, 0.1)',
                tension: 0.3,
                fill: true,
                borderDash: [5, 5]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.raw} m³`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'm³'
                    }
                }
            }
        }
    });

    // Yield Consumption Chart
    new Chart(yieldChart.value, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Meter Readings',
                data: readingData,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
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
                    callbacks: {
                        label: function (context) {
                            return `Reading: ${context.raw} m³`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
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
