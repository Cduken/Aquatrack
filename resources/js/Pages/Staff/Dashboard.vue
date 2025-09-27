<template>
    <StaffLayout title="Staff Dashboard">
        <div class="w-full bg-white rounded-2xl shadow-sm p-6">
            <!-- Header Section -->
            <div class="mb-8">
                <h1
                    class="text-3xl font-bold text-gray-800 flex items-center gap-3"
                >
                    Dashboard Overview
                </h1>
                <p class="text-gray-600 mt-2">
                    Welcome back <span class="font-bold">{{ dashboardData.staffName || "Staff" }}</span> !
                    Here's your daily performance summary.
                </p>
            </div>

            <!-- Main Metrics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Today's Readings Card -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-200 rounded-xl">
                            <v-icon
                                name="bi-droplet"
                                class="text-blue-600 text-xl"
                            />
                        </div>
                        <span
                            class="text-sm font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full"
                        >
                            Today
                        </span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-bold text-blue-800 mb-2">
                            {{ dashboardData.todaysReadings || 0 }}
                        </h3>
                        <p class="text-blue-600 font-medium">Meter Readings</p>
                        <p class="text-sm text-blue-500 mt-1">
                            Completed today
                        </p>
                    </div>
                </div>

                <!-- Weekly Readings Card -->
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-200 rounded-xl">
                            <v-icon
                                name="bi-calendar-week"
                                class="text-green-600 text-xl"
                            />
                        </div>
                        <span
                            class="text-sm font-medium text-green-700 bg-green-100 px-3 py-1 rounded-full"
                        >
                            This Week
                        </span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-bold text-green-800 mb-2">
                            {{ dashboardData.weeklyReadings || 0 }}
                        </h3>
                        <p class="text-green-600 font-medium">Total Readings</p>
                        <p class="text-sm text-green-500 mt-1">
                            Weekly progress
                        </p>
                    </div>
                </div>

                <!-- Monthly Readings Card -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-200 rounded-xl">
                            <v-icon
                                name="bi-calendar-month"
                                class="text-purple-600 text-xl"
                            />
                        </div>
                        <span
                            class="text-sm font-medium text-purple-700 bg-purple-100 px-3 py-1 rounded-full"
                        >
                            This Month
                        </span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-bold text-purple-800 mb-2">
                            {{ dashboardData.monthlyReadings || 0 }}
                        </h3>
                        <p class="text-purple-600 font-medium">Monthly Total</p>
                        <p class="text-sm text-purple-500 mt-1">
                            Current month
                        </p>
                    </div>
                </div>

                <!-- Performance Card -->
                <div
                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-200 rounded-xl">
                            <v-icon
                                name="bi-graph-up"
                                class="text-orange-600 text-xl"
                            />
                        </div>
                        <span
                            class="text-sm font-medium text-orange-700 bg-orange-100 px-3 py-1 rounded-full"
                        >
                            Performance
                        </span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-bold text-orange-800 mb-2">
                            {{
                                Math.round(
                                    ((dashboardData.todaysReadings || 0) / 20) *
                                        100
                                )
                            }}%
                        </h3>
                        <p class="text-orange-600 font-medium">Daily Target</p>
                        <p class="text-sm text-orange-500 mt-1">
                            20 readings goal
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Status Overview -->

            <!-- Recent Activity Section -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 w-full">
                <div class="flex items-center justify-between mb-6">
                    <h2
                        class="text-xl font-semibold text-gray-800 flex items-center gap-2"
                    >
                        <v-icon name="bi-activity" class="text-blue-500" />
                        Recent Activity
                    </h2>
                    <span class="text-sm text-gray-500"
                        >{{
                            dashboardData.recentActivity?.length || 0
                        }}
                        activities</span
                    >
                </div>

                <div class="space-y-4">
                    <div
                        v-for="activity in dashboardData.recentActivity || []"
                        :key="activity.id"
                        class="p-4 rounded-xl border border-gray-100 hover:border-blue-200 transition-all duration-200"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                :class="getActivityIconContainer(activity)"
                                class="p-2 rounded-lg"
                            >
                                <v-icon
                                    :name="getActivityIcon(activity)"
                                    :class="getActivityIconColor(activity)"
                                    class="text-lg"
                                />
                            </div>
                            <div class="flex-1">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <span class="font-medium text-gray-800">
                                        {{
                                            activity.customer_name ||
                                            "Unknown Customer"
                                        }}
                                    </span>
                                    <span
                                        :class="
                                            getStatusBadgeClass(activity.status)
                                        "
                                        class="text-xs px-2 py-1 rounded-full"
                                    >
                                        {{ activity.status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ activity.consumption || 0 }} m³
                                    consumption
                                    <span
                                        v-if="activity.serial_number"
                                        class="text-gray-400 ml-2"
                                    >
                                        • Serial #{{ activity.serial_number }}
                                    </span>
                                </p>
                                <div
                                    class="flex items-center justify-between text-xs text-gray-500"
                                >
                                    <span>{{
                                        formatActivityDate(
                                            activity.reading_date,
                                            activity.reading_time
                                        )
                                    }}</span>
                                    <span class="flex items-center gap-1">
                                        <v-icon
                                            name="bi-clock"
                                            class="text-xs"
                                        />
                                        {{ activity.reading_time }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="
                            !dashboardData.recentActivity ||
                            dashboardData.recentActivity.length === 0
                        "
                        class="text-center py-12"
                    >
                        <div
                            class="flex flex-col items-center justify-center text-gray-400"
                        >
                            <v-icon name="bi-inbox" class="text-4xl mb-3" />
                            <p class="text-lg font-medium">
                                No recent activity
                            </p>
                            <p class="text-sm">Activities will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StaffLayout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import StaffLayout from "@/Layouts/StaffLayout.vue";

const dashboardData = ref({});

const statusTypes = computed(() => [
    {
        type: "paid",
        label: "Paid",
        icon: "bi-check-circle-fill",
        iconColor: "text-green-500",
        iconBg: "bg-green-100",
        bgColor: "bg-green-50 border border-green-200",
        textColor: "text-green-700",
        countColor: "text-green-800",
    },
    {
        type: "pending",
        label: "Pending",
        icon: "bi-hourglass-split",
        iconColor: "text-yellow-500",
        iconBg: "bg-yellow-100",
        bgColor: "bg-yellow-50 border border-yellow-200",
        textColor: "text-yellow-700",
        countColor: "text-yellow-800",
    },
    {
        type: "overdue",
        label: "Overdue",
        icon: "bi-exclamation-triangle-fill",
        iconColor: "text-red-500",
        iconBg: "bg-red-100",
        bgColor: "bg-red-50 border border-red-200",
        textColor: "text-red-700",
        countColor: "text-red-800",
    },
]);

const formatActivityDate = (dateString, timeString) => {
    if (!dateString || !timeString) return "Unknown date";

    const today = new Date().toDateString();
    const activityDate = new Date(dateString).toDateString();

    if (today === activityDate) {
        return "Today";
    }

    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    if (yesterday.toDateString() === activityDate) {
        return "Yesterday";
    }

    return new Date(dateString).toLocaleDateString();
};

const getActivityIcon = (activity) => {
    if (activity.is_high_consumption) {
        return "bi-exclamation-triangle-fill";
    }
    if (activity.status === "Paid") {
        return "bi-check-circle-fill";
    }
    if (activity.status === "Overdue") {
        return "bi-exclamation-circle-fill";
    }
    return "bi-clock-fill";
};

const getActivityIconColor = (activity) => {
    if (activity.is_high_consumption) {
        return "text-red-500";
    }
    if (activity.status === "Paid") {
        return "text-green-500";
    }
    if (activity.status === "Overdue") {
        return "text-red-500";
    }
    return "text-yellow-500";
};

const getActivityIconContainer = (activity) => {
    if (activity.is_high_consumption) {
        return "bg-red-100";
    }
    if (activity.status === "Paid") {
        return "bg-green-100";
    }
    if (activity.status === "Overdue") {
        return "bg-red-100";
    }
    return "bg-yellow-100";
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case "Paid":
            return "bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs";
        case "Pending":
            return "bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs";
        case "Overdue":
            return "bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs";
        default:
            return "bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs";
    }
};

const fetchDashboardData = async () => {
    try {
        const response = await axios.get(route("staff.dashboard.data"));
        dashboardData.value = response.data;
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
        dashboardData.value = {
            todaysReadings: 0,
            weeklyReadings: 0,
            monthlyReadings: 0,
            recentActivity: [],
            completedToday: 0,
            pendingCount: 0,
            statusCounts: {
                paid: 0,
                pending: 0,
                overdue: 0,
            },
        };
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script>

<style scoped>
/* Smooth transitions for hover effects */
.transition-all {
    transition: all 0.3s ease;
}

/* Custom gradient backgrounds */
.bg-gradient-to-br {
    background-image: linear-gradient(
        135deg,
        var(--tw-gradient-from),
        var(--tw-gradient-to)
    );
}

/* Improved card shadows */
.rounded-2xl {
    border-radius: 1rem;
}
</style>
