<template>
    <AdminLayout title="Dashboard">


        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">Total Users</p>
                        <p class="text-3xl font-bold text-gray-800">{{ totalUsers }}</p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-blue-600 shadow-inner group-hover:bg-white transition-all">
                        <v-icon name="bi-people" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <v-icon name="bi-arrow-up" class="w-4 h-4 text-green-500" />
                        <span class="text-sm font-medium text-green-600">4.5%</span>
                    </div>
                    <div class="w-24 h-2 bg-blue-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: 72%"></div>
                    </div>
                </div>
                <p class="text-xs text-blue-500 mt-2">72% of monthly target</p>
            </div>

            <!-- Active Staff Card -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">Active Staff</p>
                        <p class="text-3xl font-bold text-gray-800">{{ totalStaffs }}</p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-green-600 shadow-inner group-hover:bg-white transition-all">
                        <v-icon name="bi-person-badge" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <v-icon name="bi-arrow-up" class="w-4 h-4 text-green-500" />
                        <span class="text-sm font-medium text-green-600">2.3%</span>
                    </div>
                    <div class="w-24 h-2 bg-green-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                <p class="text-xs text-green-500 mt-2">85% of capacity</p>
            </div>


            <!-- Customers Card -->
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">Customers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ totalCustomers }}</p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-purple-600 shadow-inner group-hover:bg-white transition-all">
                        <v-icon name="bi-person-lines-fill" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <v-icon name="bi-arrow-up" class="w-4 h-4 text-green-500" />
                        <span class="text-sm font-medium text-green-600">8.7%</span>
                    </div>
                    <div class="w-24 h-2 bg-purple-200 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 63%"></div>
                    </div>
                </div>
                <p class="text-xs text-purple-500 mt-2">63% growth target</p>
            </div>

             <!-- Reports Card -->
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 shadow-sm border border-amber-100 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600 mb-1">Reports</p>
                        <p class="text-3xl font-bold text-gray-800">{{ totalReports }}</p>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-white/50 text-amber-600 shadow-inner group-hover:bg-white transition-all">
                        <v-icon name="bi-flag" class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <v-icon name="bi-arrow-down" class="w-4 h-4 text-red-500" />
                        <span class="text-sm font-medium text-red-600">1.2%</span>
                    </div>
                    <div class="w-24 h-2 bg-amber-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: 42%"></div>
                    </div>
                </div>
                <p class="text-xs text-amber-500 mt-2">42% resolved</p>
            </div>
        </div>

        <!-- Water Analytics Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <v-icon name="bi-droplet-half" class="text-blue-500" />
                        Water Consumption Analytics
                    </h2>
                    <p class="text-sm text-gray-500">Last 12 months water consumption trends and metrics</p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 rounded-lg px-3 py-2 transition-colors">
                        <v-icon name="bi-calendar" class="text-blue-600 w-4 h-4" />
                        <span class="text-sm font-medium text-blue-600">This Year</span>
                    </button>
                    <button
                        class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 rounded-lg px-3 py-2 transition-colors">
                        <v-icon name="bi-download" class="text-gray-600 w-4 h-4" />
                        <span class="text-sm font-medium text-gray-600">Export</span>
                    </button>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart Area -->
                <div class="lg:col-span-2">
                    <div class="h-80">
                        <canvas ref="waterChart"></canvas>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="space-y-4">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 rounded-xl p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Current Month</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">52 <span
                                        class="text-sm font-normal text-gray-500">m³</span></p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <v-icon name="bi-arrow-up" class="w-5 h-5 text-green-500" />
                            </div>
                        </div>
                        <p class="text-xs text-blue-500 mt-2">+12% vs last month</p>
                    </div>

                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-100 rounded-xl p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Average</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">45 <span
                                        class="text-sm font-normal text-gray-500">m³</span></p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <v-icon name="bi-dash" class="w-5 h-5 text-gray-500" />
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Monthly average consumption</p>
                    </div>

                    <div
                        class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-100 rounded-xl p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-amber-600">Peak Usage</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">68 <span
                                        class="text-sm font-normal text-gray-500">m³</span></p>
                            </div>
                            <div class="bg-white rounded-full p-2 shadow-sm">
                                <v-icon name="bi-exclamation-triangle" class="w-5 h-5 text-amber-500" />
                            </div>
                        </div>
                        <p class="text-xs text-amber-500 mt-2">Recorded in June 2023</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <v-icon name="bi-clock-history" class="text-blue-500" />
                    Recent Activity
                </h2>
                <Link v-if="$page.props.auth.user?.role === 'admin'" :href="route('admin.activities.index')"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                View All
                <v-icon name="bi-arrow-right" class="w-3 h-3" />
                </Link>
            </div>
            <div class="divide-y divide-gray-100">
                <div v-for="activity in recentActivities" :key="activity.id"
                    class="p-4 hover:bg-gray-50 transition-colors flex items-start gap-4">
                    <div :class="`p-2 rounded-full ${getActivityBgColor(activity)}`">
                        <v-icon :name="getActivityIcon(activity)"
                            :class="`w-5 h-5 text-${getActivityTextColor(activity)}`" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ getActivityTitle(activity) }}</p>
                        <p class="text-xs text-gray-500 mt-1 truncate">
                            {{ activity.description }}
                            <span v-if="activity.properties?.tracking_code" class="font-medium">
                                ({{ activity.properties.tracking_code }})
                            </span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1 truncate">
                            <span v-if="activity.causer_name">By {{ activity.causer_name }}</span>
                            <span v-else>By System</span>
                            <span v-if="activity.properties?.old_status" class="ml-2">
                                Status: {{ activity.properties.old_status }} → {{ activity.properties.new_status }}
                            </span>
                        </p>
                    </div>
                    <div class="text-xs text-gray-400 whitespace-nowrap ml-2">
                        {{ formatTimeAgo(activity.created_at) }}
                    </div>
                </div>

                <div v-if="recentActivities.length === 0" class="p-6 text-center text-gray-500">
                    No recent activities found
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';

const waterChart = ref(null);

const page = usePage();
const totalUsers = page.props.total_users ?? 0;
const totalStaffs = page.props.total_staffs ?? 0;
const totalReports = page.props.total_reports ?? 0;
const totalCustomers = page.props.total_customers ?? 0;
const recentActivities = page.props.recent_activities ?? [];
const canViewActivityLog = page.props.auth?.user?.can?.('view-activity-log') ?? false;
// Activity display helpers
const activityConfig = {
    created: {
        icon: 'bi-file-earmark-plus',
        bg: 'bg-blue-50',
        text: 'blue-500',
        title: 'New record created'
    },
    updated: {
        icon: 'bi-pencil-square',
        bg: 'bg-green-50',
        text: 'green-500',
        title: 'Record updated'
    },
    deleted: {
        icon: 'bi-trash',
        bg: 'bg-red-50',
        text: 'red-500',
        title: 'Record deleted'
    },
    logged_in: {
        icon: 'bi-box-arrow-in-right',
        bg: 'bg-purple-50',
        text: 'purple-500',
        title: 'User login'
    },
    logged_out: {
        icon: 'bi-box-arrow-left',
        bg: 'bg-gray-50',
        text: 'gray-500',
        title: 'User logout'
    },
    verification_success: {
        icon: 'bi-shield-check',
        bg: 'bg-teal-50',
        text: 'teal-500',
        title: 'Verification passed'
    },
    verification_failed: {
        icon: 'bi-shield-exclamation',
        bg: 'bg-amber-50',
        text: 'amber-500',
        title: 'Verification failed'
    },
    default: {
        icon: 'bi-activity',
        bg: 'bg-indigo-50',
        text: 'indigo-500',
        title: 'System activity'
    },

    report_created: {
        icon: 'bi-flag',
        bg: 'bg-amber-50',
        text: 'amber-500',
        title: 'New Report Submitted'
    },
    report_status_changed: {
        icon: 'bi-arrow-repeat',
        bg: 'bg-blue-50',
        text: 'blue-500',
        title: 'Report Status Updated'
    }
};

const getActivityConfig = (activity) => {
    if (activity.subject_type === 'App\\Models\\Report') {
        return activity.event === 'report_created'
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
        minute: 60
    };

    if (seconds < 60) return 'Just now';

    for (const [unit, secondsInUnit] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInUnit);
        if (interval >= 1) {
            return `${interval} ${unit}${interval === 1 ? '' : 's'} ago`;
        }
    }

    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
    });
};

// Water chart initialization
onMounted(() => {
    if (waterChart.value) {
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
                    legend: { display: false },
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
                            label: (context) => `${context.parsed.y} m³`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { drawBorder: false, color: '#f3f4f6' },
                        ticks: {
                            color: '#9ca3af',
                            callback: (value) => `${value} m³`
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#9ca3af' }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }
});
</script>

<style scoped>
.shadow-xs {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.shadow-sm {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
