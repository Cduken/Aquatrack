<template>
    <CustomerLayout title="Usage">
        <div class="w-full bg-white rounded-lg">

            <!-- Main Content -->
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <v-icon name="bi-graph-up" /> Water Usage History
                </h2>

                <!-- Chart Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium mb-4">Monthly Usage and Billing</h3>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Water Usage (m³)</span>
                        <span class="text-sm text-gray-600">Bill Amount (₱)</span>
                    </div>

                    <div class="h-64">
                        <canvas ref="usageChart"></canvas>
                    </div>
                </div>

                <!-- Usage Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Month</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usage (m³)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bill Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(usage, index) in usageData" :key="index">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ usage.month }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ usage.usage }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ usage.amount }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="statusClasses(usage.status)">
                                        {{ usage.status }}
                                    </span>


                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <v-icon name="bi-eye-fill" class="w-5 h-5" />
                                        </button>
                                        <button class="text-green-600 hover:text-green-800">
                                            <v-icon name="fa-folder-open" class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
const usageData = page.props.usageData ?? [];
const chartData = page.props.chartData ?? [];

const usageChart = ref(null);

const statusClasses = (status) => {
    return {
        'px-2 py-1 rounded-full text-xs font-semibold': true,
        'bg-yellow-100 text-yellow-800': status === 'Pending',
        'bg-green-100 text-green-800': status === 'Paid'
    };
};

onMounted(() => {
    // Prepare labels and data for chart
    const labels = chartData.map(item => item.month);
    const usageValues = chartData.map(item => item.usage);
    const amountValues = chartData.map(item => item.amount);

    // Usage Chart with dynamic data
    new Chart(usageChart.value, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Water Usage (m³)',
                data: usageValues,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }, {
                label: 'Bill Amount (₱)',
                data: amountValues,
                backgroundColor: 'rgba(156, 163, 175, 0.7)',
                borderColor: 'rgb(156, 163, 175)',
                borderWidth: 1
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
                            const label = context.dataset.label || '';
                            const value = context.raw;
                            return `${label}: ${label.includes('Usage') ? value + ' m³' : '₱' + value}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount'
                    }
                }
            }
        }
    });
});
</script>

<style scoped>
/* Custom styles if needed */
</style>
