<template>
    <CustomerLayout title="Dashboard">
        <div
            class="w-full min-h-screen bg-gradient-to-br from-gray-50 to-blue-50"
        >
            <!-- Header Section -->
            <div class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1
                                class="text-2xl font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    name="bi-house-door-fill"
                                    class="text-blue-600"
                                />
                                Dashboard
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Welcome back <span class="font-bold">{{ customerName }}</span>! Here's your water usage overview
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Current Period</p>
                            <p class="font-semibold text-gray-800">
                                {{
                                    new Date().toLocaleDateString("en-US", {
                                        month: "long",
                                        year: "numeric",
                                    })
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-6">
                <!-- Quick Stats with Enhanced Design -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Monthly Reading Card -->
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2"
                        ></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div
                                    class="p-3 bg-blue-100 rounded-lg group-hover:scale-110 transition-transform"
                                >
                                    <v-icon
                                        name="bi-water"
                                        class="text-blue-600 text-xl"
                                    />
                                </div>
                                <v-icon
                                    name="bi-calendar-fill"
                                    class="text-gray-400 text-sm"
                                />
                            </div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">
                                Monthly Reading
                            </h3>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ monthlyUsage }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                cubic meters (m³)
                            </p>
                            <div class="mt-4 flex items-center text-xs">
                                <v-icon
                                    :name="
                                        monthlyUsage > 15
                                            ? 'bi-arrow-up'
                                            : 'bi-arrow-down'
                                    "
                                    :class="
                                        monthlyUsage > 15
                                            ? 'text-red-500'
                                            : 'text-green-500'
                                    "
                                />
                                <span
                                    :class="
                                        monthlyUsage > 15
                                            ? 'text-red-600'
                                            : 'text-green-600'
                                    "
                                    class="ml-1 font-medium"
                                >
                                    {{
                                        monthlyUsage > 15 ? "Above" : "Below"
                                    }}
                                    average
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Bill Card -->
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-green-500 to-emerald-500 h-2"
                        ></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div
                                    class="p-3 bg-green-100 rounded-lg group-hover:scale-110 transition-transform"
                                >
                                    <v-icon
                                        name="bi-cash-stack"
                                        class="text-green-600 text-xl"
                                    />
                                </div>
                                <v-icon
                                    name="bi-receipt"
                                    class="text-gray-400 text-sm"
                                />
                            </div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">
                                Current Bill
                            </h3>
                            <p class="text-3xl font-bold text-gray-800">
                                ₱{{ currentBill.toFixed(2) }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Due in 15 days
                            </p>
                            <div class="mt-4">
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2"
                                >
                                    <div
                                        class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                                        :style="`width: ${Math.min(
                                            (currentBill / 1000) * 100,
                                            100
                                        )}%`"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements Card -->
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-purple-500 to-pink-500 h-2"
                        ></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div
                                    class="p-3 bg-purple-100 rounded-lg group-hover:scale-110 transition-transform"
                                >
                                    <v-icon
                                        name="bi-bell-fill"
                                        class="text-purple-600 text-xl"
                                    />
                                </div>
                                <span
                                    v-if="announcements > 0"
                                    class="relative flex h-3 w-3"
                                >
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"
                                    ></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"
                                    ></span>
                                </span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">
                                Unread Announcements
                            </h3>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ announcements }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{
                                    announcements === 0
                                        ? "All caught up!"
                                        : "New updates"
                                }}
                            </p>
                            <button
                                class="mt-4 text-sm text-purple-600 hover:text-purple-700 font-medium flex items-center gap-1"
                            >
                                View all
                                <v-icon name="bi-arrow-right" class="text-xs" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Water Consumption Analytics with Enhanced Design -->
                <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <h3
                                    class="text-xl font-bold flex items-center gap-2"
                                >
                                    <v-icon name="bi-graph-up" /> Water
                                    Consumption Analytics
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">
                                    Track and compare your water usage patterns
                                </p>
                            </div>
                            <div
                                class="bg-white/20 backdrop-blur-sm rounded-lg p-3"
                            >
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-3 h-3 bg-white rounded-full"
                                        ></div>
                                        <span class="text-sm">Your Usage</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-3 h-3 bg-blue-300 rounded-full"
                                        ></div>
                                        <span class="text-sm"
                                            >Area Average</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Stats Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div
                                class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4 border border-blue-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            Year Total
                                        </p>
                                        <p
                                            class="text-2xl font-bold text-gray-800"
                                        >
                                            {{ yearlyConsumption }} m³
                                        </p>
                                    </div>
                                    <v-icon
                                        name="bi-calendar-check-fill"
                                        class="text-blue-500 text-2xl"
                                    />
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg p-4 border border-gray-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            Area Average
                                        </p>
                                        <p
                                            class="text-2xl font-bold text-gray-800"
                                        >
                                            {{ areaAverage }} m³
                                        </p>
                                    </div>
                                    <v-icon
                                        name="bi-people-fill"
                                        class="text-gray-500 text-2xl"
                                    />
                                </div>
                            </div>

                            <div
                                :class="
                                    yearlyConsumption > areaAverage
                                        ? 'bg-gradient-to-r from-red-50 to-orange-50 border-red-200'
                                        : 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200'
                                "
                                class="rounded-lg p-4 border"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            Status
                                        </p>
                                        <p
                                            class="text-lg font-bold"
                                            :class="
                                                yearlyConsumption > areaAverage
                                                    ? 'text-red-600'
                                                    : 'text-green-600'
                                            "
                                        >
                                            {{
                                                yearlyConsumption > areaAverage
                                                    ? "Above Average"
                                                    : "Below Average"
                                            }}
                                        </p>
                                    </div>
                                    <v-icon
                                        :name="
                                            yearlyConsumption > areaAverage
                                                ? 'bi-arrow-up-circle-fill'
                                                : 'bi-arrow-down-circle-fill'
                                        "
                                        :class="
                                            yearlyConsumption > areaAverage
                                                ? 'text-red-500'
                                                : 'text-green-500'
                                        "
                                        class="text-2xl"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Chart Container -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4
                                class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"
                            >
                                <v-icon
                                    name="bi-calendar-week-fill"
                                    class="text-gray-500"
                                />
                                Monthly Consumption Trend
                            </h4>
                            <div class="h-64">
                                <canvas ref="waterChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meter Readings with Enhanced Design -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 text-white"
                    >
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <v-icon name="bi-bar-chart-fill" /> Meter Readings
                        </h3>
                        <p class="text-indigo-100 text-sm mt-1">
                            Historical meter readings in cubic meters (m³)
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="h-64">
                                <canvas ref="yieldChart"></canvas>
                            </div>
                        </div>

                        <!-- Quick Stats Below Chart -->
                        <div class="mt-6 grid grid-cols-3 gap-4">
                            <div
                                class="text-center p-3 bg-indigo-50 rounded-lg"
                            >
                                <p class="text-xs text-gray-600 mb-1">
                                    Highest
                                </p>
                                <p class="text-lg font-bold text-indigo-600">
                                    {{
                                        Math.max(
                                            ...(chartData.map(
                                                (item) => item.reading
                                            ) || [0])
                                        )
                                    }}
                                    m³
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-purple-50 rounded-lg"
                            >
                                <p class="text-xs text-gray-600 mb-1">
                                    Average
                                </p>
                                <p class="text-lg font-bold text-purple-600">
                                    {{
                                        (
                                            chartData.reduce(
                                                (a, b) => a + b.reading,
                                                0
                                            ) / (chartData.length || 1)
                                        ).toFixed(1)
                                    }}
                                    m³
                                </p>
                            </div>
                            <div class="text-center p-3 bg-pink-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Lowest</p>
                                <p class="text-lg font-bold text-pink-600">
                                    {{
                                        Math.min(
                                            ...(chartData.map(
                                                (item) => item.reading
                                            ) || [0])
                                        )
                                    }}
                                    m³
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import Chart from "chart.js/auto";

const page = usePage();
const announcements = page.props.announcements ?? 0;
const monthlyUsage = page.props.monthlyUsage ?? 0;
const currentBill = page.props.currentBill ?? 0;
const yearlyConsumption = page.props.yearlyConsumption ?? 0;
const areaAverage = page.props.areaAverage ?? 0;
const chartData = page.props.chartData ?? [];

const customerName = page.props.customerName ?? "Customer";

const waterChart = ref(null);
const yieldChart = ref(null);

onMounted(() => {
    // Prepare labels and data for charts
    const labels = chartData.map((item) => item.month);
    const consumptionData = chartData.map((item) => item.consumption);
    const readingData = chartData.map((item) => item.reading);

    // Area average data
    const areaAvgData = Array(consumptionData.length).fill(areaAverage / 12);

    // Water Consumption Chart with Enhanced Styling
    new Chart(waterChart.value, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Your Consumption",
                    data: consumptionData,
                    borderColor: "rgb(59, 130, 246)",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: "rgb(59, 130, 246)",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: "Area Average",
                    data: areaAvgData,
                    borderColor: "rgb(156, 163, 175)",
                    backgroundColor: "rgba(156, 163, 175, 0.05)",
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    pointHoverRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: "index",
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: "bold",
                    },
                    bodyFont: {
                        size: 13,
                    },
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.raw} m³`;
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)",
                        drawBorder: false,
                    },
                    ticks: {
                        font: {
                            size: 11,
                        },
                        callback: function (value) {
                            return value + " m³";
                        },
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 11,
                        },
                    },
                },
            },
        },
    });

    // Meter Readings Chart with Enhanced Styling
    new Chart(yieldChart.value, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Meter Readings",
                    data: readingData,
                    backgroundColor: "rgba(99, 102, 241, 0.7)",
                    borderColor: "rgb(99, 102, 241)",
                    borderWidth: 0,
                    borderRadius: 8,
                    barThickness: "flex",
                    maxBarThickness: 50,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: "bold",
                    },
                    bodyFont: {
                        size: 13,
                    },
                    callbacks: {
                        label: function (context) {
                            return `Reading: ${context.raw} m³`;
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)",
                        drawBorder: false,
                    },
                    ticks: {
                        font: {
                            size: 11,
                        },
                        callback: function (value) {
                            return value + " m³";
                        },
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 11,
                        },
                    },
                },
            },
        },
    });
});
</script>

<style scoped>
/* Add subtle animations */
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-ping {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
    75%,
    100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style>
