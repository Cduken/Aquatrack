<!-- MainContentNavbar.vue -->
<template>
    <nav
        class="bg-white border-b border-gray-200 px-4 py-2.5 shadow-md dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50"
    >
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <!-- Mobile menu button -->
                <button
                    @click="$emit('toggle-mobile-menu')"
                    class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            v-if="!isMobileMenuOpen"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

                <!-- Logo and Title -->
                <div class="flex items-center justify-between mr-4">
                    <img
                        src="/images/MainLogo.png"
                        class="w-12 h-12 object-cover mr-2"
                        alt="Logo"
                    />
                    <span
                        class="self-center text-[25px] font-semibold whitespace-nowrap dark:text-white md:inline"
                    >
                        AquaTrack
                    </span>
                    <button
                        @click="$emit('toggle-sidebar')"
                        class="p-2 ml-2 text-gray-600 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white hidden md:block"
                    >
                        <svg
                            class="w-6 h-6 transition-transform duration-300 ease-in-out transform origin-center"
                            :class="{ 'rotate-180': !isSidebarOpen }"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12.71 16.29 8.41 12l4.3-4.29-1.42-1.42L5.59 12l5.7 5.71z"
                            />
                            <path
                                d="M16.29 6.29 10.59 12l5.7 5.71 1.42-1.42-4.3-4.29 4.3-4.29z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Breadcrumb -->
                <nav
                    class="hidden md:flex items-center mr-4 text-sm text-gray-700 dark:text-gray-400"
                >
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <Link
                                :href="dashboardRoute"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                            >
                                {{
                                    userRole.charAt(0).toUpperCase() +
                                    userRole.slice(1)
                                }}
                            </Link>
                        </li>
                        <li v-if="currentRouteName">
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span
                                    class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 capitalize"
                                >
                                    {{ currentRouteName }}
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex items-center lg:order-2">
                <!-- Notifications Button with Dropdown -->
                <div class="relative">
                    <button
                        type="button"
                        @click="toggleNotificationDropdown"
                        class="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-150"
                        ref="notificationButton"
                    >
                        <v-icon name="bi-bell-fill" class="w-6 h-6" />
                        <span
                            v-if="unreadCount > 0"
                            class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"
                        >
                            {{ unreadCount > 99 ? "99+" : unreadCount }}
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div
                        v-show="isNotificationDropdownOpen"
                        v-click-outside="closeNotificationDropdown"
                        class="absolute right-0 mt-2 w-80 origin-top-right rounded-lg shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                        ref="notificationDropdown"
                        style="max-height: 400px; overflow-y: auto"
                        :class="{
                            'scale-y-100 opacity-100':
                                isNotificationDropdownOpen,
                            'scale-y-95 opacity-0': !isNotificationDropdownOpen,
                        }"
                        @keydown.esc="closeNotificationDropdown"
                    >
                        <!-- Header -->
                        <div
                            class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center"
                        >
                            <h3
                                class="text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                Notifications
                                <span
                                    v-if="unreadCount > 0"
                                    class="ml-2 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full"
                                >
                                    {{ unreadCount }}
                                </span>
                            </h3>
                            <button
                                @click="closeNotificationDropdown"
                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div
                            v-if="isLoadingNotifications"
                            class="p-4 text-center"
                        >
                            <div
                                class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"
                            ></div>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-sm mt-1"
                            >
                                Loading notifications...
                            </p>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-else-if="localNotifications.length === 0"
                            class="p-6 text-center"
                        >
                            <div
                                class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center"
                            >
                                <v-icon
                                    name="bi-bell"
                                    class="w-8 h-8 text-gray-400"
                                />
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
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
                                class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group relative"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20':
                                        notification.unread,
                                }"
                                @click="handleNotificationClick(notification)"
                            >
                                <div class="flex items-start space-x-3">
                                    <!-- Avatar/Icon -->
                                    <div class="flex-shrink-0 relative">
                                        <img
                                            v-if="notification.user_avatar"
                                            :src="notification.user_avatar"
                                            :alt="
                                                notification.user_name || 'User'
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
                                            <v-icon
                                                :name="
                                                    getIconName(notification)
                                                "
                                                class="w-5 h-5 text-white"
                                            />
                                        </div>
                                        <div
                                            v-if="notification.unread"
                                            class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"
                                        />
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
                                                        >{{
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

                                                <!-- Additional Details for Reports -->
                                                <div
                                                    v-if="
                                                        [
                                                            'report_update',
                                                            'new_report',
                                                        ].includes(
                                                            notification.type
                                                        )
                                                    "
                                                    class="mt-2 space-y-1 text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <v-icon
                                                            name="bi-geo-alt"
                                                            class="w-3 h-3 mr-1 flex-shrink-0"
                                                        />
                                                        <span class="truncate"
                                                            >{{
                                                                notification.barangay
                                                            }},
                                                            {{
                                                                notification.municipality
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <v-icon
                                                            name="bi-hash"
                                                            class="w-3 h-3 mr-1 flex-shrink-0"
                                                        />
                                                        <span>{{
                                                            notification.tracking_code
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Timestamp -->
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400 text-right"
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

                                <!-- Unread indicator line -->
                                <div
                                    v-if="notification.unread"
                                    class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"
                                ></div>
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
                                    class="flex-1 py-2 text-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border-r border-gray-200 dark:border-gray-600 disabled:opacity-50"
                                >
                                    <span v-if="!isMarkingAllRead"
                                        >Mark All Read</span
                                    >
                                    <span
                                        v-else
                                        class="flex items-center justify-center"
                                    >
                                        <div
                                            class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-600 mr-1"
                                        ></div>
                                        Marking...
                                    </span>
                                </button>
                                <Link
                                    :href="notificationRoute"
                                    @click="closeNotificationDropdown"
                                    class="flex-1 py-2 text-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                    :class="{
                                        'flex-1': unreadCount > 0,
                                        'w-full': unreadCount === 0,
                                    }"
                                >
                                    <div class="inline-flex items-center">
                                        <v-icon
                                            name="bi-eye"
                                            class="w-4 h-4 mr-1"
                                        />
                                        View All Notifications
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <button
                    type="button"
                    @click.stop="toggleUserDropdown"
                    class="flex mx-3 text-sm rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-200 hover:ring-2 hover:ring-gray-200 dark:hover:ring-gray-600"
                    ref="userButton"
                >
                    <img
                        v-if="user.avatar_url"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-700 shadow-sm"
                        :src="user.avatar_url"
                        :alt="userDisplayName"
                    />
                    <div
                        v-else
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-500 text-white flex items-center justify-center font-medium text-sm border-2 border-white dark:border-gray-700 shadow-sm"
                    >
                        {{ userInitials }}
                    </div>
                </button>

                <!-- User Dropdown Menu -->
                <div
                    v-show="isUserDropdownOpen"
                    v-click-outside="closeUserDropdown"
                    class="absolute right-0 top-full mt-2 z-50 my-4 w-auto text-base list-none bg-white rounded-lg shadow-lg dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
                    ref="userDropdownMenu"
                >
                    <div
                        class="py-4 px-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-600"
                    >
                        <span
                            class="block text-sm font-semibold text-gray-900 dark:text-white truncate"
                        >
                            {{ userDisplayName }}
                        </span>
                        <span
                            class="block text-sm text-gray-600 dark:text-gray-300 truncate mt-1"
                        >
                            {{ user?.email }}
                        </span>
                    </div>
                    <ul class="py-2">
                        <li>
                            <Link
                                href="/profile"
                                class="flex items-center gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                @click="closeUserDropdown"
                            >
                                <v-icon
                                    name="bi-person"
                                    class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                />
                                <span>Profile Settings</span>
                            </Link>
                        </li>
                        <!-- <li>
                            <Link
                                href="/settings"
                                class="flex items-center gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                @click="closeUserDropdown"
                            >
                                <v-icon
                                    name="bi-gear"
                                    class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                />
                                <span>Account Settings</span>
                            </Link>
                        </li> -->
                    </ul>
                    <div
                        class="border-t border-gray-100 dark:border-gray-700 pt-2"
                    >
                        <button
                            @click.prevent="handleLogout"
                            class="flex items-center w-full gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                        >
                            <v-icon
                                name="bi-box-arrow-right"
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
                            />
                            <span>Sign out</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, watch, nextTick } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";

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

// Current route
const currentRouteName = computed(() => {
    const route = pageProps.component;
    return route
        ? route
              .split("/")
              .pop()
              .replace(/([A-Z])/g, " $1")
              .trim()
        : "";
});

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
const notificationDropdown = ref(null);
const userButton = ref(null);
const userDropdownMenu = ref(null);

const unreadCount = computed(
    () => localNotifications.value.filter((n) => n.unread).length
);

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
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: `Failed to load notifications: ${error.message}`,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
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

// Notification methods
const getIconName = (notification) => {
    const icons = {
        announcement: "bi-megaphone-fill",
        billing_status: "bi-wallet-fill",
        overdue_warning: "bi-exclamation-triangle-fill",
        new_report: "bi-flag-fill",
        report_update: "bi-flag-fill",
    };
    return icons[notification.type] || "bi-bell-fill";
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

const hasBadge = (notification) => {
    return [
        "report_update",
        "new_report",
        "billing_status",
        "overdue_warning",
    ].includes(notification.type);
};

const getBadgeClass = (notification) => {
    if (notification.type === "overdue_warning")
        return "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400";
    if (notification.type === "billing_status")
        return "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400";
    const classes = {
        pending:
            "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
        in_progress:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
        resolved:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    };
    return (
        classes[notification.status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
    );
};

const getBadgeText = (notification) => {
    if (notification.type === "overdue_warning") return "Overdue";
    if (notification.type === "billing_status") return "Billing";
    return formatStatus(notification.status);
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

onMounted(() => {
    window.addEventListener("keydown", handleKeydown);
    startNotificationPolling();
});

onUnmounted(() => {
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
    img.w-12 {
        width: 2.5rem;
        height: 2.5rem;
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
