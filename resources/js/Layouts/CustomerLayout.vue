<template>
  <div class="min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <img src="/logo.svg" class="h-6 w-6" alt="Logo" />
        <span class="font-bold text-lg">AquaTrack</span>
      </div>

      <!-- Right Controls -->
      <div class="flex items-center gap-4">
        <div class="hidden md:flex flex-col text-right">
          <p class="font-semibold">John Doe</p>
          <p class="text-sm text-gray-500">qwe@gmail.com</p>
        </div>

        <!-- Burger Button -->
        <button @click="toggleSidebar">
          <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 relative">
      <slot />
    </div>

    <!-- Right Sidebar Slideout -->
    <transition name="slide">
      <aside
        v-if="sidebarOpen"
        class="fixed top-0 right-0 w-64 h-full bg-white shadow-lg z-50 p-6"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Quick Panel</h2>
          <button @click="toggleSidebar" class="text-gray-500 hover:text-gray-800 text-2xl leading-none">&times;</button>
        </div>
        <!-- Sidebar content -->
        <ul class="space-y-2 text-sm">
          <li><a href="#" class="text-blue-600 hover:underline">Account Settings</a></li>
          <li><a href="#" class="text-blue-600 hover:underline">Activity Logs</a></li>
          <li><a href="#" class="text-blue-600 hover:underline">Logout</a></li>
        </ul>
      </aside>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const sidebarOpen = ref(false);
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};
</script>

<style scoped>
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from, .slide-leave-to {
  transform: translateX(100%);
}
</style>
