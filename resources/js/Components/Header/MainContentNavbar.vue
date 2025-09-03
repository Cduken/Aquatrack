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
                        ></path>
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>

                <div class="flex items-center justify-between mr-4">
                    <img
                        src="/images/MainLogo.png"
                        class="w-12 h-12 object-cover mr-2"
                        alt="Logo"
                    />
                    <span
                        class="self-center text-[25px] font-semibold whitespace-nowrap dark:text-white md:inline"
                        >AquaTrack</span
                    >
                    <button
                        @click="$emit('toggle-sidebar')"
                        class="p-2 ml-2 text-gray-600 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white hidden md:block"
                    >
                        <span>
                            <svg
                                aria-hidden="true"
                                class="w-6 h-6 transition-transform duration-300 ease-in-out transform origin-center"
                                :class="{ 'rotate-180': !isSidebarOpen }"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12.71 16.29 8.41 12l4.3-4.29-1.42-1.42L5.59 12l5.7 5.71z"
                                ></path>
                                <path
                                    d="M16.29 6.29 10.59 12l5.7 5.71 1.42-1.42-4.3-4.29 4.3-4.29z"
                                ></path>
                            </svg>
                            <span class="sr-only">Toggle sidebar</span>
                        </span>
                    </button>
                </div>

                <nav
                    class="hidden md:flex items-center mr-4 text-sm text-gray-700 dark:text-gray-400"
                    aria-label="Breadcrumb"
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
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
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
                <!-- Notifications Button -->
                <button
                    type="button"
                    @click="toggleNotificationDropdown"
                    class="relative p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    ref="notificationButton"
                >
                    <span class="sr-only">View notifications</span>
                    <v-icon name="bi-bell-fill" class="w-6 h-6" />
                    <span
                        v-if="unreadCount > 0"
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full border border-white"
                    >
                        {{ unreadCount > 9 ? "9+" : unreadCount }}
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <div
                    v-show="isNotificationDropdownOpen"
                    v-click-outside="closeNotificationDropdown"
                    class="absolute right-0 top-full mt-2 z-50 my-4 w-96 text-base list-none bg-white rounded-lg shadow-lg dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
                    ref="notificationDropdown"
                >
                    <div
                        class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300"
                    >
                        Notifications
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div v-if="notifications.length === 0" class="text-center py-8 text-gray-500">
                            No notifications available
                        </div>
                        <div v-else v-for="notification in notifications" :key="notification.id"
                            class="p-3 border-b border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-600"
                        >
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <v-icon :name="getIconName(notification)" :class="getIconClass(notification)" />
                                </div>
                                <div class="ml-3 flex-1">
                                    <div v-if="hasBadge(notification)" class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ getTitle(notification) }}
                                        </p>
                                        <span :class="getBadgeClass(notification)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ getBadgeText(notification) }}
                                        </span>
                                    </div>
                                    <p v-else class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ getTitle(notification) }}
                                    </p>
                                    <p v-if="notification.type === 'announcement'" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ notification.message }}
                                    </p>
                                    <p v-if="['report_update', 'new_report'].includes(notification.type)" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Location: {{ notification.barangay }}, {{ notification.municipality }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ getDateLabel(notification) }}: {{ formatDate(getDateField(notification)) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Link
                        :href="notificationRoute"
                        class="block py-2 text-md font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline"
                    >
                        <div class="inline-flex items-center">
                            <svg
                                aria-hidden="true"
                                class="mr-2 w-4 h-4 text-gray-500 dark:text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                ></path>
                                <path
                                    fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            View all
                        </div>
                    </Link>
                </div>

                <!-- User Dropdown -->
                <button
                    type="button"
                    @click.stop="toggleDropdown"
                    class="flex mx-3 text-sm rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-200 hover:ring-2 hover:ring-gray-200 dark:hover:ring-gray-600"
                    id="user-menu-button"
                    :aria-expanded="isDropdownOpen"
                    ref="dropdownButton"
                >
                    <span class="sr-only">Open user menu</span>
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
                    v-show="isDropdownOpen"
                    v-click-outside="closeDropdown"
                    class="absolute right-0 top-full mt-2 z-50 my-4 w-auto text-base list-none bg-white rounded-lg shadow-lg dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
                    id="dropdown"
                    ref="dropdownMenu"
                >
                    <div
                        class="py-4 px-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-600"
                    >
                        <span
                            class="block text-sm font-semibold text-gray-900 dark:text-white truncate"
                            >{{ userDisplayName }}</span
                        >
                        <span
                            class="block text-sm text-gray-600 dark:text-gray-300 truncate mt-1"
                            >{{ user?.email }}</span
                        >
                    </div>
                    <ul class="py-2">
                        <li>
                            <Link
                                href="/profile"
                                class="flex items-center gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                @click="closeDropdown"
                            >
                                <div
                                    class="w-5 h-5 flex items-center justify-center text-gray-500 dark:text-gray-400"
                                >
                                    <v-icon name="bi-person" class="w-4 h-4" />
                                </div>
                                <span>Profile Settings</span>
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/settings"
                                class="flex items-center gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                @click="closeDropdown"
                            >
                                <div
                                    class="w-5 h-5 flex items-center justify-center text-gray-500 dark:text-gray-400"
                                >
                                    <v-icon name="bi-gear" class="w-4 h-4" />
                                </div>
                                <span>Account Settings</span>
                            </Link>
                        </li>
                    </ul>
                    <div
                        class="border-t border-gray-100 dark:border-gray-700 pt-2"
                    >
                        <ul>
                            <li>
                                <button
                                    @click.prevent="handleLogout"
                                    class="flex items-center w-full gap-3 py-3 px-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                >
                                    <div
                                        class="w-5 h-5 flex items-center justify-center text-gray-500 dark:text-gray-400"
                                    >
                                        <v-icon
                                            name="bi-box-arrow-right"
                                            class="w-4 h-4"
                                        />
                                    </div>
                                    <span>Sign out</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from "vue";
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
    initialNotifications: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["toggle-sidebar", "toggle-mobile-menu", "logout"]);

const { props: pageProps } = usePage();

// User data
const user = computed(() => pageProps.auth?.user ?? {});
const userRole = computed(() => user.value?.role || "customer");

// Dynamic dashboard and notification routes based on role
const dashboardRoute = computed(() => {
    return userRole.value === "admin" ? "/admin/dashboard" : "/staff/dashboard";
});

const notificationRoute = computed(() => {
    return userRole.value === "admin"
        ? "/admin/notifications"
        : "/staff/notifications";
});

// Compute current route name for breadcrumbs
const currentRouteName = computed(() => {
    const path = window.location.pathname;
    if (path === "/admin/dashboard" || path === "/staff/dashboard")
        return "Dashboard";
    if (path.startsWith("/admin/reports") || path.startsWith("/staff/reports"))
        return "Reports";
    if (path.startsWith("/admin/users")) return "Users";
    if (path.startsWith("/admin/staff")) return "Staff";
    if (path.startsWith("/admin/records") || path.startsWith("/staff/records"))
        return "Records";
    if (
        path.startsWith("/admin/announcements") ||
        path.startsWith("/staff/announcements")
    )
        return "Announcements";
    if (path.startsWith("/staff/reading")) return "Reading";
    if (
        path.startsWith("/admin/analytics") ||
        path.startsWith("/staff/analytics")
    )
        return "Analytics";
    if (path.startsWith("/admin/profile") || path.startsWith("/staff/profile"))
        return "Profile";
    return (
        path.split("/").pop()?.charAt(0).toUpperCase() +
            path.split("/").pop()?.slice(1).replace(/-/g, " ") || "Dashboard"
    );
});

const userDisplayName = computed(() => {
    if (user.value?.name) return user.value.name;
    if (user.value?.email) return user.value.email.split("@")[0];
    return "Guest";
});

const userInitials = computed(() => {
    if (user.value?.name) {
        return user.value.name
            .split(" ")
            .map((n) => n[0])
            .join("")
            .toUpperCase()
            .slice(0, 2);
    }
    if (user.value?.email) {
        return user.value.email[0].toUpperCase();
    }
    return "G";
});

// Notification handling
const notifications = ref(props.initialNotifications);
const isNotificationDropdownOpen = ref(false);
const notificationButton = ref(null);
const notificationDropdown = ref(null);

const unreadCount = computed(() => {
    return notifications.value.filter(n => n.unread).length; // Assuming notifications have an 'unread' property
});

const toggleNotificationDropdown = () => {
    isNotificationDropdownOpen.value = !isNotificationDropdownOpen.value;
    if (isNotificationDropdownOpen.value) {
        setTimeout(() => {
            notificationDropdown.value?.querySelector("a, button")?.focus();
        }, 0);
    }
};

const closeNotificationDropdown = () => {
    isNotificationDropdownOpen.value = false;
};

// Notification formatting functions
const getIconName = (notification) => {
    const icons = {
        announcement: 'bi-megaphone-fill',
        billing_status: 'bi-wallet-fill',
        overdue_warning: 'bi-exclamation-triangle-fill',
        new_report: 'bi-flag-fill',
        report_update: 'bi-flag-fill'
    };
    return icons[notification.type] || 'bi-bell-fill';
};

const getIconClass = (notification) => {
    if (['report_update', 'new_report'].includes(notification.type)) {
        const classes = {
            pending: 'text-yellow-500',
            in_progress: 'text-blue-500',
            resolved: 'text-green-500'
        };
        return `h-5 w-5 ${classes[notification.status] || 'text-gray-500'}`;
    }
    const classes = {
        announcement: 'h-5 w-5 text-purple-500',
        billing_status: 'h-5 w-5 text-blue-500',
        overdue_warning: 'h-5 w-5 text-red-500',
        new_report: 'h-5 w-5 text-orange-500'
    };
    return classes[notification.type] || 'h-5 w-5 text-gray-500';
};

const hasBadge = (notification) => {
    return ['report_update', 'new_report', 'billing_status', 'overdue_warning'].includes(notification.type);
};

const getBadgeClass = (notification) => {
    if (notification.type === 'overdue_warning') return 'bg-red-100 text-red-800';
    if (notification.type === 'billing_status') return 'bg-blue-100 text-blue-800';
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        in_progress: 'bg-blue-100 text-blue-800',
        resolved: 'bg-green-100 text-green-800'
    };
    return classes[notification.status] || 'bg-gray-100 text-gray-800';
};

const getBadgeText = (notification) => {
    if (notification.type === 'overdue_warning') return 'Overdue';
    return formatStatus(notification.status);
};

const getTitle = (notification) => {
    switch (notification.type) {
        case 'report_update':
            return `Report #${notification.tracking_code}`;
        case 'new_report':
            return `New Report #${notification.tracking_code}`;
        case 'announcement':
            return notification.title;
        case 'billing_status':
            return `Billing Status Update`;
        case 'overdue_warning':
            return `Overdue Payment Warning`;
        default:
            return 'Notification';
    }
};

const getDateLabel = (notification) => {
    if (notification.type === 'announcement') return 'Announced';
    if (notification.type === 'overdue_warning') return 'Due';
    return 'Updated';
};

const getDateField = (notification) => {
    if (notification.type === 'overdue_warning') return notification.due_date;
    if (notification.type === 'announcement') return notification.created_at;
    return notification.updated_at;
};

const formatStatus = (status) => {
    if (!status) return '';
    return status.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Dropdown handling (user menu)
const isDropdownOpen = ref(false);
const dropdownButton = ref(null);
const dropdownMenu = ref(null);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
    if (isDropdownOpen.value) {
        setTimeout(() => {
            dropdownMenu.value?.querySelector("a, button")?.focus();
        }, 0);
    }
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
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
        closeDropdown();
        emit("logout");
    }
};

// Accessibility
const handleKeydown = (e) => {
    if (e.key === "Escape") {
        if (isDropdownOpen.value) {
            closeDropdown();
            dropdownButton.value?.focus();
        }
        if (isNotificationDropdownOpen.value) {
            closeNotificationDropdown();
            notificationButton.value?.focus();
        }
    }
    if (isNotificationDropdownOpen.value && e.key === "Tab") {
        const focusableElements = notificationDropdown.value?.querySelectorAll(
            "a[href], button:not([disabled])"
        );
        if (focusableElements?.length) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }
    if (isDropdownOpen.value && e.key === "Tab") {
        const focusableElements = dropdownMenu.value?.querySelectorAll(
            "a[href], button:not([disabled])"
        );
        if (focusableElements?.length) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
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

onMounted(() => {
    window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeydown);
});
</script>

<style scoped>
@media (max-width: 767px) {
    nav {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .flex-wrap {
        flex-wrap: nowrap;
    }

    .text-\[25px\] {
        font-size: 1.25rem;
    }

    img.w-12 {
        width: 2.5rem;
        height: 2.5rem;
    }
}

.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.15s ease-out;
    transform-origin: top right;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-2px);
}

button:active {
    transform: scale(0.98);
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

#dropdown,
#notification-dropdown {
    transition: all 0.2s ease-out;
    transform-origin: top right;
}

.hover-lift:hover {
    transform: translateY(-1px);
}
</style>
