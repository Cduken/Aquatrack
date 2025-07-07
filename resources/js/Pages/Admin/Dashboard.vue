<template>
  <MainLayout>
    <DashboardSidebar
      @logout="logout"
      :links="dashboardLinks"
      :useRouterLinks="true"
    >
      <template #default>
        <div class="flex flex-col items-center pt-10">
          <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
          <!-- Content specific to your dashboard -->
        </div>
      </template>

      <!-- Optional: Custom footer content -->
      <template #footer>
        <button
          @click="logout"
          class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center justify-center gap-2"
          :disabled="form.processing"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <span v-if="form.processing">Logging out...</span>
          <span v-else>Logout</span>
        </button>
      </template>
    </DashboardSidebar>
  </MainLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import MainLayout from '@/Layouts/MainLayout.vue';
import DashboardSidebar from '@/Components/Sidebar/DashboardSidebar.vue';

const form = useForm({});

// Define your dashboard links
const dashboardLinks = [
  { name: 'Dashboard', url: '/admin/dashboard', icon: '📊' },
  { name: 'Users', url: '/admin/users', icon: '👥' },
  { name: 'Settings', url: '/admin/settings', icon: '⚙️' }
];

const logout = () => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You will be logged out of the system.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, logout!'
  }).then((result) => {
    if (result.isConfirmed) {
      form.post(route('logout'), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Logged out',
            text: 'You have been successfully logged out.',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            window.location.href = route('login');
          });
        },
        onError: () => {
          Swal.fire({
            icon: 'error',
            title: 'Logout failed',
            text: 'Please try again.',
          });
        }
      });
    }
  });
};
</script>
