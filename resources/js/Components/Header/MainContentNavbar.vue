<template>
    <nav
        class="bg-white border-b border-gray-200 px-6 py-[13px] shadow-lg dark:bg-gray-800 dark:border-gray-700 fixed right-0 top-0 z-40 transition-all duration-300 ease-in-out"
        :class="[
            isSidebarOpen ? 'left-64' : 'left-16',
            isMobileMenuOpen ? 'left-0' : '',
        ]"
        :style="navStyle"
    >
        <div class="flex justify-between items-center">
            <!-- Left section with mobile menu and search -->
            <div class="flex items-center flex-1 max-w-3xl">
                <!-- Mobile menu button -->
                <button
                    @click="$emit('toggle-mobile-menu')"
                    class="p-2 mr-3 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200"
                >
                    <Bars3Icon v-if="!isMobileMenuOpen" class="w-6 h-6" />
                    <XMarkIcon v-else class="w-6 h-6" />
                </button>

                <!-- Search Bar -->
                <div class="relative w-full ml-2">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                        <MagnifyingGlassIcon class="w-5 h-5 text-gray-900" />
                    </div>
                    <input
                        type="text"
                        v-model="searchQuery"
                        @focus="isSearchFocused = true"
                        @blur="isSearchFocused = false"
                        class="bg-gray-50 border border-gray-300 shadow-lg w-[38%]  text-gray-900 text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 block pl-10 pr-4 py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-200"
                        placeholder="Search reports, announcements"
                    />
                    <div
                        v-if="searchQuery"
                        class="absolute inset-y-0 right-0 flex items-center pr-3"
                    >
                        <button
                            @click="clearSearch"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <XMarkIcon class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Search suggestions dropdown -->
                    <div
                        v-if="isSearchFocused && searchQuery.length > 0"
                        class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600 overflow-hidden"
                    >
                        <div class="py-2">
                            <div
                                v-if="isLoadingSearch"
                                class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 flex items-center"
                            >
                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"
                                ></div>
                                Searching...
                            </div>
                            <div
                                v-else-if="searchSuggestions.length === 0"
                                class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"
                            >
                                No results found for "{{ searchQuery }}"
                            </div>
                            <div v-else>
                                <div
                                    v-for="suggestion in searchSuggestions"
                                    :key="suggestion.id"
                                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors"
                                    @mousedown="selectSuggestion(suggestion)"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                            >{{ suggestion.title }}</span
                                        >
                                        <span
                                            class="text-xs px-2 py-1 rounded-full"
                                            :class="
                                                getSuggestionBadgeClass(
                                                    suggestion
                                                )
                                            "
                                        >
                                            {{ suggestion.type }}
                                        </span>
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate"
                                    >
                                        {{ suggestion.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right section with notifications and user menu -->
            <div class="flex items-center space-x-3 ml-4">
                <!-- Notifications Button with Dropdown -->
                <div class="relative">
                    <button
                        type="button"
                        @click="toggleNotificationDropdown"
                        class="relative p-2 text-gray-500 rounded-full hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-200"
                        ref="notificationButton"
                    >
                        <BellIcon class="w-6 h-6" />
                        <span
                            v-if="unreadCount > 0"
                            class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"
                        >
                            {{ unreadCount > 99 ? "99+" : unreadCount }}
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div
                        v-show="isNotificationDropdownOpen"
                        v-click-outside="closeNotificationDropdown"
                        class="absolute right-0 mt-3 w-96 origin-top-right rounded-xl shadow-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden z-50 transform transition-all duration-200"
                        :class="{
                            'scale-100 opacity-100': isNotificationDropdownOpen,
                            'scale-95 opacity-0': !isNotificationDropdownOpen,
                        }"
                    >
                        <!-- Header -->
                        <div
                            class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 border-b border-gray-100 dark:border-gray-600"
                        >
                            <div class="flex justify-between items-center">
                                <h3
                                    class="text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    Notifications
                                    <span
                                        v-if="unreadCount > 0"
                                        class="ml-2 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full"
                                    >
                                        {{ unreadCount }}
                                    </span>
                                </h3>
                                <button
                                    @click="closeNotificationDropdown"
                                    class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg hover:bg-white dark:hover:bg-gray-700 transition-colors duration-200"
                                >
                                    <XMarkIcon class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Loading State -->
                            <div
                                v-if="isLoadingNotifications"
                                class="p-8 text-center"
                            >
                                <div
                                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"
                                ></div>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-sm mt-3"
                                >
                                    Loading notifications...
                                </p>
                            </div>

                            <!-- Empty State -->
                            <div
                                v-else-if="localNotifications.length === 0"
                                class="p-8 text-center"
                            >
                                <div
                                    class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center"
                                >
                                    <BellIcon class="w-8 h-8 text-gray-400" />
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">
                                    No notifications available
                                </p>
                            </div>

                            <!-- Notifications List -->
                            <div
                                v-else
                                class="divide-y divide-gray-100 dark:divide-gray-700"
                            >
                                <div
                                    v-for="notification in localNotifications"
                                    :key="notification.id"
                                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group relative"
                                    :class="{
                                        'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500':
                                            notification.unread,
                                    }"
                                    @click="
                                        handleNotificationClick(notification)
                                    "
                                >
                                    <div class="flex items-start space-x-3">
                                        <!-- Avatar/Icon -->
                                        <div class="flex-shrink-0 relative">
                                            <img
                                                v-if="notification.user_avatar"
                                                :src="notification.user_avatar"
                                                :alt="
                                                    notification.user_name ||
                                                    'User'
                                                "
                                                class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-700 shadow-sm"
                                            />
                                            <div
                                                v-else
                                                class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm"
                                                :class="
                                                    getNotificationAvatarClass(
                                                        notification
                                                    )
                                                "
                                            >
                                                <component
                                                    :is="
                                                        getNotificationIcon(
                                                            notification
                                                        )
                                                    "
                                                    class="w-5 h-5 text-white"
                                                />
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="flex items-start justify-between"
                                            >
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white"
                                                    >
                                                        <span
                                                            v-if="
                                                                notification.user_name &&
                                                                notification.type !==
                                                                    'announcement'
                                                            "
                                                            class="font-semibold"
                                                        >
                                                            {{
                                                                notification.user_name
                                                            }}
                                                        </span>
                                                        {{
                                                            getNotificationTitle(
                                                                notification
                                                            )
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2"
                                                    >
                                                        {{
                                                            getNotificationMessage(
                                                                notification
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400 text-right ml-2"
                                                >
                                                    {{
                                                        getRelativeTime(
                                                            getDateField(
                                                                notification
                                                            )
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30"
                        >
                            <div class="flex">
                                <button
                                    v-if="unreadCount > 0"
                                    @click="markAllAsRead"
                                    :disabled="isMarkingAllRead"
                                    class="flex-1 py-3 text-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border-r border-gray-200 dark:border-gray-600 disabled:opacity-50"
                                >
                                    Mark All Read
                                </button>
                                <Link
                                    :href="notificationRoute"
                                    @click="closeNotificationDropdown"
                                    class="flex-1 py-3 text-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                >
                                    View All
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="relative">
                    <button
                        type="button"
                        @click.stop="toggleUserDropdown"
                        class="flex items-center space-x-3 text-sm rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-200"
                        ref="userButton"
                    >
                        <img
                            v-if="user.avatar_url"
                            class="w-8 h-8 rounded-full border-2 border-gray-200 dark:border-gray-600 shadow-sm"
                            :src="user.avatar_url"
                            :alt="userDisplayName"
                        />
                        <div
                            v-else
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 text-white flex items-center justify-center font-medium text-sm border-2 border-gray-200 dark:border-gray-600 shadow-sm"
                        >
                            {{ userInitials }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ userDisplayName }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{
                                    userRole.charAt(0).toUpperCase() +
                                    userRole.slice(1)
                                }}
                            </p>
                        </div>
                        <ChevronDownIcon class="w-4 h-4 text-gray-400" />
                    </button>

                    <!-- User Dropdown Menu -->
                    <div
                        v-show="isUserDropdownOpen"
                        v-click-outside="closeUserDropdown"
                        class="absolute right-0 top-full mt-2 z-50 w-64 text-base bg-white rounded-xl shadow-xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden transform transition-all duration-200"
                        :class="{
                            'scale-100 opacity-100': isUserDropdownOpen,
                            'scale-95 opacity-0': !isUserDropdownOpen,
                        }"
                    >
                        <div
                            class="py-4 px-4 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 border-b border-gray-100 dark:border-gray-600"
                        >
                            <div class="flex items-center space-x-3">
                                <img
                                    v-if="user.avatar_url"
                                    class="w-12 h-12 rounded-full border-2 border-white dark:border-gray-700 shadow-sm"
                                    :src="user.avatar_url"
                                    :alt="userDisplayName"
                                />
                                <div
                                    v-else
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 text-white flex items-center justify-center font-medium border-2 border-white dark:border-gray-700 shadow-sm"
                                >
                                    {{ userInitials }}
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ userDisplayName }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-600 dark:text-gray-300"
                                    >
                                        {{ user?.email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <Link
                                href="/profile"
                                class="flex items-center gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                @click="closeUserDropdown"
                            >
                                <UserIcon
                                    class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                />
                                <span>Profile Settings</span>
                            </Link>
                        </div>
                        <div
                            class="border-t border-gray-100 dark:border-gray-700 pt-2"
                        >
                            <button
                                @click.prevent="handleLogout"
                                class="flex items-center w-full gap-3 py-3 px-4 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                            >
                                <ArrowRightOnRectangleIcon class="w-4 h-4" />
                                <span>Sign out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import {
    HomeIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    BellIcon,
    UserIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon,
    FlagIcon,
    SpeakerWaveIcon,
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";
import Swal from "sweetalert2";
import debounce from "lodash/debounce";

const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        default: true,
    },
    isMobileMenuOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["toggle-sidebar", "toggle-mobile-menu", "logout"]);

const { props: pageProps } = usePage();

// User data
const user = computed(() => pageProps.auth?.user ?? {});
const userRole = computed(() => user.value?.role || "customer");
const userDisplayName = computed(() => user.value?.name || "Unknown User");
const userInitials = computed(() => {
    if (!user.value?.name) return "??";
    const names = user.value.name.split(" ");
    return names
        .map((n) => n.charAt(0))
        .slice(0, 2)
        .join("")
        .toUpperCase();
});

// Search state
const searchQuery = ref("");
const isSearchFocused = ref(false);
const searchSuggestions = ref([]);
const isLoadingSearch = ref(false);

// Search methods
const searchService = {
    async search(query) {
        if (!query || query.length < 2) {
            return [];
        }

        try {
            isLoadingSearch.value = true;
            // Simulate API call - replace with actual API endpoint
            await new Promise((resolve) => setTimeout(resolve, 500));

            // Mock data - replace with actual API response
            return [
                {
                    id: 1,
                    title: "Water Quality Report - Barangay San Jose",
                    type: "report",
                    description:
                        "Submitted on Oct 15, 2023 - Status: Pending Review",
                },
                {
                    id: 2,
                    title: "Monthly Maintenance Announcement",
                    type: "announcement",
                    description: "Scheduled maintenance on Nov 5, 2023",
                },
                {
                    id: 3,
                    title: "Billing Statement - October 2023",
                    type: "billing",
                    description: "Due date: Nov 15, 2023 - Amount: ₱1,250.00",
                },
            ];
        } catch (error) {
            console.error("Search error:", error);
            return [];
        } finally {
            isLoadingSearch.value = false;
        }
    },
};

const performSearch = debounce(async () => {
    if (searchQuery.value.length > 1) {
        searchSuggestions.value = await searchService.search(searchQuery.value);
    } else {
        searchSuggestions.value = [];
    }
}, 300);

const clearSearch = () => {
    searchQuery.value = "";
    searchSuggestions.value = [];
};

const getSuggestionBadgeClass = (suggestion) => {
    const classes = {
        report: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
        announcement:
            "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200",
        billing:
            "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
    };
    return (
        classes[suggestion.type] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200"
    );
};

const selectSuggestion = (suggestion) => {
    // Handle suggestion selection based on type
    switch (suggestion.type) {
        case "report":
            window.location.href = `${dashboardRoute.value.replace(
                "/dashboard",
                ""
            )}/reports`;
            break;
        case "announcement":
            window.location.href = `${dashboardRoute.value.replace(
                "/dashboard",
                ""
            )}/announcements`;
            break;
        case "billing":
            window.location.href = `${dashboardRoute.value.replace(
                "/dashboard",
                ""
            )}/billing`;
            break;
    }
    clearSearch();
};

// Routes
const dashboardRoute = computed(() =>
    userRole.value === "admin"
        ? "/admin/dashboard"
        : userRole.value === "staff"
        ? "/staff/dashboard"
        : "/customer/dashboard"
);
const notificationRoute = computed(() =>
    userRole.value === "admin"
        ? "/admin/notifications"
        : userRole.value === "staff"
        ? "/staff/notifications"
        : "/customer/notifications"
);

// Notification state
const localNotifications = ref([]);
const isNotificationDropdownOpen = ref(false);
const isUserDropdownOpen = ref(false);
const isLoadingNotifications = ref(false);
const isMarkingAllRead = ref(false);
const notificationButton = ref(null);
const userButton = ref(null);

const unreadCount = computed(
    () => localNotifications.value.filter((n) => n.unread).length
);

// Window width state
const windowWidth = ref(
    typeof window !== "undefined" ? window.innerWidth : 1024
); // Default to a safe value for SSR

// Computed property for nav style
const navStyle = computed(() => ({
    left:
        windowWidth.value < 768 ? "0" : props.isSidebarOpen ? "16rem" : "4rem",
}));

// Handle window resize
const handleResize = () => {
    windowWidth.value = window.innerWidth;
};

// Notification methods
const getNotificationIcon = (notification) => {
    const icons = {
        announcement: SpeakerWaveIcon,
        billing_status: BellIcon,
        overdue_warning: ExclamationTriangleIcon,
        new_report: FlagIcon,
        report_update: FlagIcon,
    };
    return icons[notification.type] || BellIcon;
};

const getNotificationAvatarClass = (notification) => {
    const classes = {
        new_report: "bg-orange-500",
        report_update: "bg-blue-500",
        announcement: "bg-purple-500",
        billing_status: "bg-green-500",
        overdue_warning: "bg-red-500",
    };
    return classes[notification.type] || "bg-gray-500";
};

const getNotificationTitle = (notification) => {
    switch (notification.type) {
        case "new_report":
            return "submitted a new water quality report";
        case "report_update":
            return `updated report #${notification.tracking_code}`;
        case "announcement":
            return notification.title;
        case "billing_status":
            return "billing status updated";
        case "overdue_warning":
            return "payment overdue warning";
        default:
            return "sent you a notification";
    }
};

const getNotificationMessage = (notification) => {
    switch (notification.type) {
        case "new_report":
            return `A new water quality report has been submitted for ${notification.barangay}, ${notification.municipality}. Please review and take appropriate action.`;
        case "report_update":
            return `Report status has been updated to "${formatStatus(
                notification.status
            )}". Location: ${notification.barangay}, ${
                notification.municipality
            }`;
        case "announcement":
            return notification.message;
        case "billing_status":
            return "Your billing information has been updated. Please check your account for details.";
        case "overdue_warning":
            return "You have an overdue payment. Please settle your account to avoid service interruption.";
        default:
            return notification.message || "You have a new notification";
    }
};

const getDateField = (notification) => {
    if (notification.type === "overdue_warning") return notification.due_date;
    if (notification.type === "announcement") return notification.created_at;
    return notification.updated_at || notification.created_at;
};

const formatStatus = (status) => {
    if (!status) return "";
    return status
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

const getRelativeTime = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) return "a few seconds ago";
    if (diffInSeconds < 3600)
        return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400)
        return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 604800)
        return `${Math.floor(diffInSeconds / 86400)} days ago`;

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: date.getFullYear() !== now.getFullYear() ? "numeric" : undefined,
    });
};

// Notification service
const notificationService = {
    async getNotifications() {
        try {
            const response = await fetch("/api/notifications", {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                },
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(
                    `HTTP error! status: ${response.status}, message: ${
                        errorData.message || "Unknown error"
                    }`
                );
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching notifications:", error);
            return { success: false, notifications: [], unread_count: 0 };
        }
    },

    async markAsRead(notificationId) {
        try {
            const response = await fetch("/api/notifications/mark-read", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                },
                body: JSON.stringify({ notification_id: notificationId }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("Error marking notification as read:", error);
            return { success: false };
        }
    },

    async markAllAsRead() {
        try {
            const response = await fetch("/api/notifications/mark-all-read", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("Error marking all notifications as read:", error);
            return { success: false };
        }
    },
};

// Load notifications
const loadNotifications = async () => {
    isLoadingNotifications.value = true;
    try {
        const response = await notificationService.getNotifications();
        if (response.success) {
            localNotifications.value = response.notifications || [];
        }
    } catch (error) {
        console.error("Failed to load notifications:", error);
    } finally {
        isLoadingNotifications.value = false;
    }
};

// Dropdown controls
const toggleNotificationDropdown = async () => {
    if (!isNotificationDropdownOpen.value) {
        await loadNotifications();
    }
    isNotificationDropdownOpen.value = !isNotificationDropdownOpen.value;
};

const closeNotificationDropdown = () => {
    isNotificationDropdownOpen.value = false;
};

const toggleUserDropdown = () => {
    isUserDropdownOpen.value = !isUserDropdownOpen.value;
};

const closeUserDropdown = () => {
    isUserDropdownOpen.value = false;
};

const handleNotificationClick = async (notification) => {
    if (notification.unread) {
        const response = await notificationService.markAsRead(notification.id);
        if (response.success) {
            notification.unread = false;
        }
    }

    switch (notification.type) {
        case "new_report":
        case "report_update":
            if (notification.report_id) {
                window.location.href = `${dashboardRoute.value.replace(
                    "/dashboard",
                    ""
                )}/reports/${notification.report_id}`;
            } else {
                window.location.href = `${dashboardRoute.value.replace(
                    "/dashboard",
                    ""
                )}/reports`;
            }
            break;
        case "announcement":
            window.location.href = `${dashboardRoute.value.replace(
                "/dashboard",
                ""
            )}/announcements`;
            break;
        default:
            window.location.href = notificationRoute.value;
    }

    closeNotificationDropdown();
};

const markAllAsRead = async () => {
    isMarkingAllRead.value = true;
    try {
        const response = await notificationService.markAllAsRead();
        if (response.success) {
            localNotifications.value.forEach((notification) => {
                notification.unread = false;
            });

            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "All notifications have been marked as read.",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        } else {
            throw new Error("Failed to mark notifications as read");
        }
    } catch (error) {
        console.error("Error marking all notifications as read:", error);
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Failed to mark notifications as read. Please try again.",
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
        });
    } finally {
        isMarkingAllRead.value = false;
    }
};

const handleLogout = async () => {
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to log out?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, log out!",
        cancelButtonText: "Cancel",
    });

    if (result.isConfirmed) {
        closeUserDropdown();
        emit("logout");
    }
};

const handleKeydown = (e) => {
    if (e.key === "Escape") {
        if (isUserDropdownOpen.value) {
            closeUserDropdown();
            userButton.value?.focus();
        }
        if (isNotificationDropdownOpen.value) {
            closeNotificationDropdown();
            notificationButton.value?.focus();
        }
        if (isSearchFocused.value) {
            isSearchFocused.value = false;
        }
    }
};

const vClickOutside = {
    beforeMount(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener("click", el.clickOutsideEvent, {
            passive: true,
        });
    },
    unmounted(el) {
        document.removeEventListener("click", el.clickOutsideEvent);
    },
};

let notificationInterval = null;

const startNotificationPolling = () => {
    loadNotifications();
    notificationInterval = setInterval(() => {
        if (!isNotificationDropdownOpen.value) {
            loadNotifications();
        }
    }, 30000);
};

const stopNotificationPolling = () => {
    if (notificationInterval) {
        clearInterval(notificationInterval);
        notificationInterval = null;
    }
};

// Watch for search query changes
watch(searchQuery, () => {
    performSearch();
});

// Mount and unmount event listeners
onMounted(() => {
    window.addEventListener("resize", handleResize);
    window.addEventListener("keydown", handleKeydown);
    startNotificationPolling();
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
    window.removeEventListener("keydown", handleKeydown);
    stopNotificationPolling();
});

watch(
    () => pageProps,
    () => {
        loadNotifications();
    },
    { deep: true }
);
</script>

<style scoped>
@media (max-width: 767px) {
    nav {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .text-\[25px\] {
        font-size: 1.25rem;
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

button:active {
    transform: scale(0.98);
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.max-h-96::-webkit-scrollbar {
    width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-96::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.8);
}

.dark .max-h-96::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}

.dark .max-h-96::-webkit-scrollbar-thumb:hover {
    background-color: rgba(75, 85, 99, 0.8);
}

.group:hover .animate-pulse {
    animation: none;
}

@keyframes badge-pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.animate-pulse {
    animation: badge-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth dropdown transition */
.notification-dropdown {
    transform-origin: top right;
    transition: transform 0.2s ease-out, opacity 0.2s ease-out;
}

.scale-y-100 {
    transform: scaleY(1);
    opacity: 1;
}

.scale-y-95 {
    transform: scaleY(0.95);
    opacity: 0;
}
</style>
