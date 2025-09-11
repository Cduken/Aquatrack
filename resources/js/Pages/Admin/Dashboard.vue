<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, onMounted, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import Chart from "chart.js/auto";

const waterChart = ref(null);

const page = usePage();
const totalUsers = page.props.total_users ?? 0;
const totalStaffs = page.props.total_staffs ?? 0;
const totalReports = page.props.total_reports ?? 0;
const totalCustomers = page.props.total_customers ?? 0;
const monthlyConsumption = page.props.monthly_consumption ?? Array(12).fill(0);
const currentMonthConsumption = page.props.current_month_consumption ?? 0;
const averageConsumption = page.props.average_consumption ?? 0;
const peakUsage = page.props.peak_usage ?? 0;
const growthPercentage = page.props.growth_percentage ?? 0;
const resolutionRate = page.props.resolution_rate ?? 0;
const recentActivities = page.props.recent_activities ?? [];
const canViewActivityLog =
    page.props.auth?.user?.can?.("view-activity-log") ?? false;

// Activity display helpers
const activityConfig = {
    created: {
        icon: "plus-circle",
        bg: "bg-blue-50",
        text: "blue-500",
        title: "New record created",
    },
    updated: {
        icon: "pencil-square",
        bg: "bg-green-50",
        text: "green-500",
        title: "Record updated",
    },
    deleted: {
        icon: "trash",
        bg: "bg-red-50",
        text: "red-500",
        title: "Record deleted",
    },
    logged_in: {
        icon: "arrow-right-on-rectangle",
        bg: "bg-purple-50",
        text: "purple-500",
        title: "User login",
    },
    logged_out: {
        icon: "arrow-left-on-rectangle",
        bg: "bg-gray-50",
        text: "gray-500",
        title: "User logout",
    },
    verification_success: {
        icon: "shield-check",
        bg: "bg-teal-50",
        text: "teal-500",
        title: "Verification passed",
    },
    verification_failed: {
        icon: "shield-exclamation",
        bg: "bg-amber-50",
        text: "amber-500",
        title: "Verification failed",
    },
    default: {
        icon: "sparkles",
        bg: "bg-indigo-50",
        text: "indigo-500",
        title: "System activity",
    },
    report_created: {
        icon: "flag",
        bg: "bg-amber-50",
        text: "amber-500",
        title: "New Report Submitted",
    },
    report_status_changed: {
        icon: "arrow-path",
        bg: "bg-blue-50",
        text: "blue-500",
        title: "Report Status Updated",
    },
};

const getActivityConfig = (activity) => {
    if (activity.subject_type === "App\\Models\\Report") {
        return activity.event === "report_created"
            ? activityConfig.report_created
            : activityConfig.report_status_changed;
    }
    return activityConfig[activity.event] || activityConfig.default;
};

const getActivityIcon = (activity) => {
    return getActivityConfig(activity).icon;
};

const getActivityBgColor = (activity) => {
    return getActivityConfig(activity).bg;
};

const getActivityTextColor = (activity) => {
    return getActivityConfig(activity).text;
};

const getActivityTitle = (activity) => {
    return getActivityConfig(activity).title;
};

const formatTimeAgo = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
    };

    if (seconds < 60) return "Just now";

    for (const [unit, secondsInUnit] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInUnit);
        if (interval >= 1) {
            return `${interval} ${unit}${interval === 1 ? "" : "s"} ago`;
        }
    }

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: date.getFullYear() !== now.getFullYear() ? "numeric" : undefined,
    });
};

// Get current month name
const currentMonthName = computed(() => {
    const months = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    return months[new Date().getMonth()];
});

// Get peak usage month
const peakUsageMonth = computed(() => {
    const months = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    const maxConsumption = Math.max(...monthlyConsumption);
    const peakIndex = monthlyConsumption.indexOf(maxConsumption);
    return months[peakIndex];
});

// Water chart initialization
onMounted(() => {
    if (waterChart.value) {
        new Chart(waterChart.value, {
            type: "line",
            data: {
                labels: [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ],
                datasets: [
                    {
                        label: "Water Consumption (m³)",
                        data: monthlyConsumption,
                        borderColor: "rgb(59, 130, 246)",
                        backgroundColor: "rgba(59, 130, 246, 0.05)",
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: "white",
                        pointBorderColor: "rgb(59, 130, 246)",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "white",
                        titleColor: "#1f2937",
                        bodyColor: "#4b5563",
                        borderColor: "#e5e7eb",
                        borderWidth: 1,
                        padding: 12,
                        usePointStyle: true,
                        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.08)",
                        callbacks: {
                            label: (context) => `${context.parsed.y} m³`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { drawBorder: false, color: "#f3f4f6" },
                        ticks: {
                            color: "#9ca3af",
                            callback: (value) => `${value} m³`,
                        },
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: "#9ca3af" },
                    },
                },
                interaction: { intersect: false, mode: "index" },
            },
        });
    }
});
</script>

<template>
    <AdminLayout title="Dashboard">
        <div class="lg:items-center lg:flex mb-4 hidden">
            <v-icon
                name="md-dashboard-sharp"
                class="mr-2 text-green-500"
                scale="1.5"
            />
            <h1 class="text-2xl">Dashboard Management</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Users Card -->
            <div
                class="rounded-2xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition-all duration-200 group"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">
                            Total Users
                        </p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ totalUsers }}
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-blue-600 shadow-inner group-hover:bg-white transition-all"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <svg
                            class="w-4 h-4 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium text-green-600"
                            >4.5%</span
                        >
                    </div>
                    <div
                        class="w-24 h-2 bg-blue-200 rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-blue-500 rounded-full"
                            style="width: 72%"
                        ></div>
                    </div>
                </div>
                <p class="text-xs text-blue-500 mt-2">72% of monthly target</p>
            </div>

            <!-- Active Staff Card -->
            <div
                class="rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition-all duration-200 group"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">
                            Active Staff
                        </p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ totalStaffs }}
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-green-600 shadow-inner group-hover:bg-white transition-all"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <svg
                            class="w-4 h-4 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium text-green-600"
                            >2.3%</span
                        >
                    </div>
                    <div
                        class="w-24 h-2 bg-green-200 rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-green-500 rounded-full"
                            style="width: 85%"
                        ></div>
                    </div>
                </div>
                <p class="text-xs text-green-500 mt-2">85% of capacity</p>
            </div>

            <!-- Customers Card -->
            <div
                class="rounded-2xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-all duration-200 group"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">
                            Customers
                        </p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ totalCustomers }}
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-purple-600 shadow-inner group-hover:bg-white transition-all"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="size-6"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                clip-rule="evenodd"
                            />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z"
                            />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <svg
                            class="w-4 h-4 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium text-green-600"
                            >8.7%</span
                        >
                    </div>
                    <div
                        class="w-24 h-2 bg-purple-200 rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-purple-500 rounded-full"
                            style="width: 63%"
                        ></div>
                    </div>
                </div>
                <p class="text-xs text-purple-500 mt-2">63% growth target</p>
            </div>

            <!-- Reports Card -->
            <div
                class="rounded-2xl p-6 shadow-sm border border-amber-100 hover:shadow-md transition-all duration-200 group"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600 mb-1">
                            Reports
                        </p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ totalReports }}
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-amber-600 shadow-inner group-hover:bg-white transition-all"
                    >
                        <v-icon name="bi-flag" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <svg
                            v-if="growthPercentage >= 0"
                            class="w-4 h-4 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 text-red-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"
                            ></path>
                        </svg>
                        <span
                            :class="`text-sm font-medium ${growthPercentage >= 0 ? 'text-green-600' : 'text-red-600'}`"
                        >
                            {{ Math.abs(growthPercentage) }}%
                        </span>
                    </div>
                    <div
                        class="w-24 h-2 bg-amber-200 rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-amber-500 rounded-full"
                            :style="`width: ${resolutionRate}%`"
                        ></div>
                    </div>
                </div>
                <p class="text-xs text-amber-500 mt-2">{{ resolutionRate }}% resolved</p>
            </div>
        </div>

        <!-- Water Analytics Section -->
        <div
            class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden h-full mb-4"
        >
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h2
                        class="text-xl font-semibold text-gray-800 flex items-center gap-2"
                    >
                        <svg
                            class="w-6 h-6 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                            ></path>
                        </svg>
                        Water Consumption Analytics
                    </h2>
                    <p class="text-sm text-gray-500">
                        Last 12 months water consumption trends and metrics
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 rounded-lg px-3 py-2 transition-colors"
                    >
                        <svg
                            class="w-4 h-4 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-600"
                            >This Year</span
                        >
                    </button>
                    <button
                        class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 rounded-lg px-3 py-2 transition-colors"
                    >
                        <svg
                            class="w-4 h-4 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600"
                            >Export</span
                        >
                    </button>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart Area -->
                <div class="lg:col-span-2">
                    <div class="h-full">
                        <canvas ref="waterChart"></canvas>
                    </div>
                </div>
                <!-- Key Metrics -->
                <div class="space-y-4">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 rounded-xl p-4 shadow-xs"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">
                                    Current Month ({{ currentMonthName }})
                                </p>
                                <p
                                    class="text-2xl font-bold text-gray-800 mt-1"
                                >
                                    {{ currentMonthConsumption }}
                                    <span
                                        class="text-sm font-normal text-gray-500"
                                        >m³</span
                                    >
                                </p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <svg
                                    v-if="growthPercentage >= 0"
                                    class="w-5 h-5 text-green-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18"
                                    ></path>
                                </svg>
                                <svg
                                    v-else
                                    class="w-5 h-5 text-red-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <p
                            :class="`text-xs ${growthPercentage >= 0 ? 'text-green-500' : 'text-red-500'} mt-2`"
                        >
                            {{ growthPercentage >= 0 ? '+' : '' }}{{ growthPercentage }}% vs last month
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-100 rounded-xl p-4 shadow-xs"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Average
                                </p>
                                <p
                                    class="text-2xl font-bold text-gray-800 mt-1"
                                >
                                    {{ averageConsumption }}
                                    <span
                                        class="text-sm font-normal text-gray-500"
                                        >m³</span
                                    >
                                </p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <svg
                                    class="w-5 h-5 text-gray-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 12H4"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Monthly average consumption
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-100 rounded-xl p-4 shadow-xs"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-amber-600">
                                    Peak Usage
                                </p>
                                <p
                                    class="text-2xl font-bold text-gray-800 mt-1"
                                >
                                    {{ peakUsage }}
                                    <span
                                        class="text-sm font-normal text-gray-500"
                                        >m³</span
                                    >
                                </p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <svg
                                    class="w-5 h-5 text-amber-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-amber-500 mt-2">
                            Recorded in {{ peakUsageMonth }} {{ new Date().getFullYear() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div
            class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden h-full"
        >
            <div
                class="p-6 border-b border-gray-100 flex items-center justify-between"
            >
                <h2
                    class="text-xl font-semibold text-gray-800 flex items-center gap-2"
                >
                    <v-icon name="bi-clock-history" class="text-blue-500" scale="1.5" />
                    Recent Activity
                </h2>
                <Link
                    v-if="canViewActivityLog"
                    :href="route('admin.activity-logs')"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1"
                >
                    View All
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </Link>
            </div>
            <div class="divide-y divide-gray-100">
                <div
                    v-for="activity in recentActivities"
                    :key="activity.id"
                    class="p-4 hover:bg-gray-50 transition-colors flex items-start gap-4"
                >
                    <div
                        :class="`p-2 rounded-full ${getActivityBgColor(
                            activity
                        )}`"
                    >
                        <svg
                            v-if="getActivityIcon(activity) === 'plus-circle'"
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 01118 0z"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) === 'pencil-square'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="getActivityIcon(activity) === 'trash'"
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0H6a1 1 0 00-1 1v1m14 0V4a1 1 0 00-1-1h-4"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) ===
                                'arrow-right-on-rectangle'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) ===
                                'arrow-left-on-rectangle'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6m0-2v-1a3 3 0 013-3h7a3 3 0 013 3v7a3 3 0 01-3 3H9a3 3 0 01-3-3v-1"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) === 'shield-check'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) ===
                                'shield-exclamation'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="getActivityIcon(activity) === 'sparkles'"
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="getActivityIcon(activity) === 'flag'"
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                        <svg
                            v-else-if="
                                getActivityIcon(activity) === 'arrow-path'
                            "
                            class="w-5 h-5"
                            :class="`text-${getActivityTextColor(activity)}`"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                            ></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ getActivityTitle(activity) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1 truncate">
                            {{ activity.description }}
                            <span
                                v-if="activity.properties?.tracking_code"
                                class="font-medium"
                            >
                                ({{ activity.properties.tracking_code }})
                            </span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1 truncate">
                            <span v-if="activity.causer_name"
                                >By {{ activity.causer_name }}</span
                            >
                            <span v-else>By System</span>
                            <span
                                v-if="activity.properties?.old_status"
                                class="ml-2"
                            >
                                Status: {{ activity.properties.old_status }} →
                                {{ activity.properties.new_status }}
                            </span>
                        </p>
                    </div>
                    <div class="text-xs text-gray-400 whitespace-nowrap ml-2">
                        {{ formatTimeAgo(activity.created_at) }}
                    </div>
                </div>
                <div
                    v-if="recentActivities.length === 0"
                    class="p-6 text-center text-gray-500"
                >
                    No recent activities found
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
