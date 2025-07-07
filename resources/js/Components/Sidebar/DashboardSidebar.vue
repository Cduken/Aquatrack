<script setup>
import { ref } from 'vue';

const props = defineProps({
  logo: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: 'Menu'
  },
  bgColor: {
    type: String,
    default: '#2c3e50'
  },
  textColor: {
    type: String,
    default: 'white'
  },
  width: {
    type: String,
    default: '280px'
  },
  position: {
    type: String,
    default: 'left',
    validator: (value) => ['left', 'right'].includes(value)
  },
  links: {
    type: Array,
    default: () => [
      { name: 'Home', url: '#', icon: '🏠' },
      { name: 'About', url: '#', icon: 'ℹ️' },
      { name: 'Contact', url: '#', icon: '✉️' }
    ]
  },
  useRouterLinks: {
    type: Boolean,
    default: false
  }
});

const isOpen = ref(false);

const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};

// Expose the toggle function if needed
defineExpose({
  toggleSidebar
});
</script>

<template>
  <div class="sidebar-wrapper">
    <!-- Toggle Button (outside sidebar) -->
    <button
      class="sidebar-toggle"
      @click="toggleSidebar"
      :style="{
        left: position === 'left' ? '20px' : 'auto',
        right: position === 'right' ? '20px' : 'auto',
        backgroundColor: bgColor
      }"
    >
      {{ isOpen ? '✕' : '☰' }}
    </button>

    <!-- Sidebar Content -->
    <aside
      class="sidebar"
      :class="{
        'sidebar-open': isOpen,
        'sidebar-left': position === 'left',
        'sidebar-right': position === 'right'
      }"
      :style="{
        width: width,
        backgroundColor: bgColor,
        color: textColor
      }"
    >
      <div class="sidebar-content">
        <!-- Logo Section -->
        <div v-if="logo || $slots.logo" class="sidebar-logo">
          <slot name="logo">
            <img :src="logo" alt="Logo" class="logo-image" v-if="logo">
          </slot>
        </div>

        <!-- Header Section -->
        <slot name="header">
          <h2 class="sidebar-title">{{ title }}</h2>
        </slot>

        <!-- Navigation Links -->
        <nav class="sidebar-nav">
          <slot name="links">
            <!-- Dynamic links from props -->
            <template v-if="useRouterLinks">
              <router-link
                v-for="(link, index) in links"
                :key="index"
                :to="link.url"
                class="sidebar-link"
                active-class="active-link"
              >
                <span class="link-icon" v-if="link.icon">{{ link.icon }}</span>
                {{ link.name }}
              </router-link>
            </template>
            <template v-else>
              <a
                v-for="(link, index) in links"
                :key="index"
                :href="link.url"
                class="sidebar-link"
              >
                <span class="link-icon" v-if="link.icon">{{ link.icon }}</span>
                {{ link.name }}
              </a>
            </template>
          </slot>
        </nav>

        <!-- Footer Section -->
        <div class="sidebar-footer">
          <slot name="footer"></slot>
        </div>
      </div>
    </aside>

    <!-- Overlay when sidebar is open -->
    <div
      class="sidebar-overlay"
      :class="{ 'overlay-visible': isOpen }"
      @click="toggleSidebar"
    ></div>
  </div>

  <div class="sidebar-footer">
  <slot name="footer">
    <!-- Default footer content with logout button -->
    <button
      @click="$emit('logout')"
      class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center justify-center gap-2"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
      </svg>
      Logout
    </button>
  </slot>
</div>
</template>

<style scoped>
.sidebar-wrapper {
  position: relative;
}

.sidebar-toggle {
  position: fixed;
  top: 20px;
  z-index: 1001;
  color: v-bind(textColor);
  border: none;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.3s ease;
}

.sidebar-toggle:hover {
  opacity: 0.9;
}

.sidebar {
  position: fixed;
  top: 0;
  height: 100vh;
  transition: all 0.3s ease;
  z-index: 1000;
  overflow-y: auto;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-left {
  left: calc(-1 * v-bind(width));
}

.sidebar-right {
  right: calc(-1 * v-bind(width));
}

.sidebar-left.sidebar-open {
  left: 0;
}

.sidebar-right.sidebar-open {
  right: 0;
}

.sidebar-content {
  padding: 20px;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.sidebar-logo {
  padding: 20px 0;
  text-align: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
}

.logo-image {
  max-width: 80%;
  max-height: 60px;
  object-fit: contain;
}

.sidebar-title {
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex-grow: 1;
}

.sidebar-link {
  color: v-bind(textColor);
  text-decoration: none;
  padding: 12px 15px;
  border-radius: 6px;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 10px;
}

.sidebar-link:hover {
  background: rgba(255, 255, 255, 0.1);
}

.active-link {
  background: rgba(255, 255, 255, 0.2);
  font-weight: bold;
}

.link-icon {
  font-size: 1.1em;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.overlay-visible {
  opacity: 1;
  visibility: visible;
}
</style>
