<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AquatrackLogo from '../AquatrackLogo.vue';

// Declare emitted events
const emit = defineEmits(['close', 'addReport']);

const props = defineProps({
    isOpen: Boolean,
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    },
    version: {
        type: String,
        default: '1.0'
    }
});

const page = usePage();

const isActive = (href) => {
  const current = page.url;

  if (typeof href === 'object' && href.route) {
    const routePath = new URL(router.url(href.route)).pathname;
    const currentPath = new URL(current, window.location.origin).pathname;

    if (href.route === 'reports.index') {
      return currentPath === routePath;
    }
    return currentPath.startsWith(routePath);
  }

  const currentPath = new URL(current, window.location.origin).pathname;
  const targetPath = new URL(href, window.location.origin).pathname;

  if (targetPath === '/') {
    return currentPath === targetPath;
  }
  return currentPath.startsWith(targetPath);
};

const handleClose = () => {
  emit('close');
};

const handleAddReport = () => {
  emit('addReport');
};
</script>

<template>
    <!-- Sidebar Overlay -->
    <div v-show="isOpen" @click="handleClose"
        class="fixed inset-0 bg-black/50 z-50 transition-opacity duration-300"
        :class="{ 'opacity-0 pointer-events-none': !isOpen, 'opacity-100': isOpen }">
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg z-50 transform transition-transform duration-300 ease-in-out"
        :class="{ 'translate-x-full': !isOpen, 'translate-x-0': isOpen }">
        <div class="h-full flex flex-col">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <AquatrackLogo class="h-14 w-14" />
                <button @click="handleClose" class="p-1 rounded-full hover:bg-gray-100">
                    <v-icon name="bi-x-lg" />
                </button>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <Link href="/" @click="handleClose"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors"
                    :class="isActive('/') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'">
                <v-icon name="bi-house" scale="0.9" />
                <span>Home</span>
                </Link>

                <Link :href="route('reports.index')" @click="handleClose"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors"
                    :class="isActive(route('reports.index')) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'">
                <v-icon name="bi-flag" scale="0.9" />
                <span>Reports</span>
                </Link>

                <button @click="handleAddReport"
                    class="flex items-center gap-3 w-full text-left px-3 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <v-icon name="bi-plus-circle" scale="0.9" />
                    <span>Add Report</span>
                </button>

                <div class="border-t border-gray-200 my-2"></div>

                <Link v-if="canLogin" :href="route('login')" @click="handleClose"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors"
                    :class="isActive(route('login')) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'">
                <v-icon name="bi-box-arrow-in-right" scale="0.9" />
                <span>Log In</span>
                </Link>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t text-sm text-gray-500">
                <p>Aquatrack v{{ version }}</p>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
