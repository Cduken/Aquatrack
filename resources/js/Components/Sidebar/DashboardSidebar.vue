<script setup>
import { ref } from 'vue';

const props = defineProps({
  logo: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: 'AquaTrack'
  },
  bgColor: {
    type: String,
    default: '#1e88e5' // Primary blue color
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
      { name: 'Dashboard', url: '#', icon: '📊', active: false },
      { name: 'Manage Users', url: '#', icon: '👥', active: true },
      { name: 'Manage Staff', url: '#', icon: '👔', active: false },
      { name: 'Reports', url: '#', icon: '📄', active: false },
      { name: 'Analytics', url: '#', icon: '📈', active: false }
    ]
  },
  useRouterLinks: {
    type: Boolean,
    default: false
  },
  showLogout: {
    type: Boolean,
    default: true
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

const emit = defineEmits(['logout']);
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
            <h1 v-else class="text-2xl font-bold">{{ title }}</h1>
          </slot>
        </div>

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
                :class="{ 'active-link': link.active }"
                active-class="active-link"
              >
                <span class="link-icon" v-if="link.icon">{{ link.icon }}</span>
                {{ link.name }}
                <span v-if="link.active" class="checkmark">✓</span>
              </router-link>
            </template>
            <template v-else>
              <a
                v-for="(link, index) in links"
                :key="index"
                :href="link.url"
                class="sidebar-link"
                :class="{ 'active-link': link.active }"
              >
                <span class="link-icon" v-if="link.icon">{{ link.icon }}</span>
                {{ link.name }}
                <span v-if="link.active" class="checkmark">✓</span>
              </a>
            </template>
          </slot>
        </nav>

        <!-- Footer Section -->
        <div class="sidebar-footer" v-if="showLogout">
          <slot name="footer">
            <button
              @click="emit('logout')"
              class="logout-button"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              Logout
            </button>
          </slot>
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
  padding: 10px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.sidebar-toggle:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

.sidebar {
  position: fixed;
  top: 0;
  height: 100vh;
  transition: all 0.3s ease;
  z-index: 1000;
  overflow-y: auto;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  background: linear-gradient(to bottom, #1e88e5, #0d47a1);
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
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  margin-bottom: 30px;
}

.logo-image {
  max-width: 80%;
  max-height: 60px;
  object-fit: contain;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 5px;
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
  gap: 12px;
  font-size: 0.95rem;
  position: relative;
}

.sidebar-link:hover {
  background: rgba(255, 255, 255, 0.15);
}

.active-link {
  background: rgba(255, 255, 255, 0.25);
  font-weight: 500;
}

.active-link::after {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: white;
  border-radius: 0 4px 4px 0;
}

.link-icon {
  font-size: 1.1em;
}

.checkmark {
  margin-left: auto;
  font-weight: bold;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.logout-button {
  width: 100%;
  padding: 10px 15px;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 0.9rem;
}

.logout-button:hover {
  background: rgba(255, 255, 255, 0.2);
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
