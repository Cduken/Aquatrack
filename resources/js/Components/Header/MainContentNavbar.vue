<template>
  <nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
    <div class="flex flex-wrap justify-between items-center">
      <div class="flex justify-start items-center">
        <!-- Mobile menu button -->
        <button @click="$emit('toggle-mobile-menu')" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <div class="flex items-center justify-between mr-4">
          <img src="/images/MainLogo.png" class="w-12 h-12 object-cover mr-2" alt="Logo">
          <span class="self-center text-[25px] font-semibold whitespace-nowrap dark:text-white">AquaTrack</span>
          <button @click="$emit('toggle-sidebar')" class="p-2 ml-2 text-gray-600 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white hidden md:block">
            <span>
              <svg aria-hidden="true" class="w-6 h-6 transition-transform duration-300 ease-in-out transform origin-center" :class="{ 'rotate-180': !isSidebarOpen }" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.71 16.29 8.41 12l4.3-4.29-1.42-1.42L5.59 12l5.7 5.71z"></path>
                <path d="M16.29 6.29 10.59 12l5.7 5.71 1.42-1.42-4.3-4.29 4.3-4.29z"></path>
              </svg>
              <span class="sr-only">Toggle sidebar</span>
            </span>
          </button>
        </div>

                <!-- Dynamic Breadcrumbs -->
                 <nav class="hidden md:flex items-center mr-4 text-sm text-gray-700 dark:text-gray-400" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                        <Link :href="dashboardRoute" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            {{ userRole.charAt(0).toUpperCase() + userRole.slice(1) }}
                        </Link>
                        </li>
                        <li v-if="currentRouteName">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 capitalize">
                            {{ currentRouteName }}
                            </span>
                        </div>
                        </li>
                    </ol>
                    </nav>
                </div>
                <div class="flex items-center lg:order-2">
        <!-- Mobile breadcrumb (simplified) -->
        <div class="md:hidden text-sm font-medium text-gray-700 dark:text-gray-400 mr-2 capitalize">
          {{ currentRouteName }}
        </div>
            </div>

            <div class="flex items-center lg:order-2">
                <!-- Notifications -->
                <button
                    type="button"
                    data-dropdown-toggle="notification-dropdown"
                    class="relative p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                >
                    <span class="sr-only">View notifications</span>
                    <svg
                        aria-hidden="true"
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                        ></path>
                    </svg>
                    <span
                        v-if="unreadCount > 0"
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full border border-white"
                    >
                        {{ unreadCount > 9 ? "9+" : unreadCount }}
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <div
                    class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700 rounded-xl"
                    id="notification-dropdown"
                >
                    <div
                        class="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300"
                    >
                        Notifications
                    </div>
                    <div>
                        <Link
                            v-for="report in initialReports"
                            :key="report.id"
                            :href="report.href || '#'"
                            class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
                        >
                            <div class="flex-shrink-0">
                                <img
                                    class="w-11 h-11 rounded-full"
                                    :src="
                                        report.avatar_url ||
                                        'https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png'
                                    "
                                    :alt="report.name || 'User avatar'"
                                />
                                <div
                                    v-if="report.unread"
                                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 rounded-full border border-white bg-primary-700 dark:border-gray-700"
                                >
                                    <svg
                                        aria-hidden="true"
                                        class="w-3 h-3 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"
                                        ></path>
                                        <path
                                            d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="pl-3 w-full">
                                <div
                                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                                >
                                    {{ report.message }}
                                </div>
                                <div
                                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                                >
                                    {{ report.time }}
                                </div>
                            </div>
                        </Link>
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
                    class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button"
                    :aria-expanded="isDropdownOpen"
                    ref="dropdownButton"
                >
                    <span class="sr-only">Open user menu</span>
                    <img
                        v-if="user.avatar_url"
                        class="w-8 h-8 rounded-full"
                        :src="user.avatar_url"
                        :alt="userDisplayName"
                    />
                    <div
                        v-else
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 text-white flex items-center justify-center font-medium text-sm"
                    >
                        {{ userInitials }}
                    </div>
                </button>

                <!-- User Dropdown Menu -->
                <div
                    v-show="isDropdownOpen"
                    v-click-outside="closeDropdown"
                    class="absolute right-0 top-full mt-2 z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600 rounded-xl"
                    id="dropdown"
                    ref="dropdownMenu"
                >
                    <div class="py-3 px-4">
                        <span
                            class="block text-sm font-semibold text-gray-900 dark:text-white"
                            >{{ userDisplayName }}</span
                        >
                        <span
                            class="block text-sm text-gray-900 truncate dark:text-white"
                            >{{ user?.email }}</span
                        >
                    </div>
                    <ul
                        class="py-1 text-gray-700 dark:text-gray-300"
                        aria-labelledby="dropdown"
                    >
                        <li>
                            <Link
                                href="/profile"
                                class="flex items-center gap-2 py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white"
                            >
                                <v-icon name="bi-people" />
                                Profile Settings
                            </Link>
                        </li>
                    </ul>
                    <ul
                        class="py-1 text-gray-700 dark:text-gray-300"
                        aria-labelledby="dropdown"
                    >
                        <li>
                            <button
                                @click.prevent="handleLogout"
                                class="flex items-center w-full gap-2 py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                <svg
                                    class="w-4 h-4 text-gray-400 dark:text-gray-300"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"
                                    />
                                </svg>
                                Sign out
                            </button>
                        </li>
                    </ul>
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
    initialReports: {
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

const unreadCount = computed(() => {
    return props.initialReports.length;
});

// Dropdown handling
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
    if (e.key === "Escape" && isDropdownOpen.value) {
        closeDropdown();
        dropdownButton.value?.focus();
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
    if (typeof Flowbite !== "undefined") {
        const notificationDropdown = document.getElementById(
            "notification-dropdown"
        );
        const userDropdown = document.getElementById("dropdown");
        if (notificationDropdown) new Flowbite.Dropdown(notificationDropdown);
        if (userDropdown) new Flowbite.Dropdown(userDropdown);
    }
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeydown);
});
</script>

<style scoped>
/* Improved mobile styles */
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
</style>
