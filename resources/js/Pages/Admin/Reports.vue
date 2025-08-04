<template>
  <AdminLayout title="Reports">
    <div class="w-full bg-white shadow-md rounded-lg">
      <div class="p-6">
        <h1 class="text-2xl font-bold border-b pb-4 text-gray-800">Reports</h1>

        <div class="mt-6 overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reporter</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="report in reports.data" :key="report.id">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ report.id }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ report.reporter_name || 'Unknown' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ report.description || 'N/A' }}</td>

                <!-- ✅ FIXED PRIORITY DISPLAY -->
                <td class="px-6 py-4 text-sm text-gray-500">
                  <template v-if="editingId === report.id">
                    <select v-model="editForm.priority" class="border rounded px-2 py-1 text-sm">
                      <option value="Low">Low</option>
                      <option value="Medium">Medium</option>
                      <option value="High">High</option>
                      <option value="Low-Medium">Low-Medium</option>
                    </select>
                  </template>
                  <template v-else>
                    <span :class="priorityClasses(report.priority)">
                      {{ report.priority || 'Varies' }}
                    </span>
                  </template>
                </td>

                <td class="px-6 py-4 text-sm text-gray-500">
                  <template v-if="editingId === report.id">
                    <select v-model="editForm.status" class="border rounded px-2 py-1 text-sm">
                      <option value="Pending">Pending</option>
                      <option value="In Progress">In Progress</option>
                      <option value="Resolved">Resolved</option>
                    </select>
                  </template>
                  <template v-else>
                    <span :class="statusClasses(report.status)">
                      {{ report.status || 'Pending' }}
                    </span>
                  </template>
                </td>

                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(report.created_at) }}</td>

                <td class="px-6 py-4 text-sm text-gray-500">
                  <div class="flex space-x-2 items-center">
                    <button class="text-blue-600 hover:text-blue-800" title="View">
                      <i class="bi bi-eye-fill text-lg"></i>
                    </button>

                    <button @click="startEdit(report)" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                      <i class="bi bi-pencil-square text-lg"></i>
                    </button>

                    <template v-if="editingId === report.id">
                      <button @click="saveReport(report.id)" class="text-green-600 hover:text-green-800" title="Save">
                        <i class="bi bi-check-circle-fill text-lg"></i>
                      </button>
                      <button @click="cancelEdit" class="text-gray-500 hover:text-gray-700" title="Cancel">
                        <i class="bi bi-x-circle-fill text-lg"></i>
                      </button>
                    </template>

                    <button @click="deleteReport(report.id)" class="text-red-600 hover:text-red-800" title="Delete">
                      <i class="bi bi-trash-fill text-lg"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({ reports: Object });

const editingId = ref(null);
const editForm = ref({ priority: '', status: '' });

const startEdit = (report) => {
  editingId.value = editingId.value === report.id ? null : report.id;

  if (editingId.value) {
    editForm.value = {
      priority: report.priority || 'Varies',
      status: report.status || 'Pending'
    };
  }
};

const cancelEdit = () => {
  editingId.value = null;
  editForm.value = { priority: '', status: '' };
};

const saveReport = (id) => {
  router.put(route('admin.reports.update', id), {
    priority: editForm.value.priority,
    status: editForm.value.status
  }, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('Saved!', 'Report updated successfully.', 'success');
      cancelEdit();
      router.reload(); // ✅ ensures updated data is shown
    },
    onError: (errors) => {
      console.error(errors);
      Swal.fire('Error', 'Failed to update report.', 'error');
    }
  });
};

const deleteReport = (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/reports/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire('Deleted!', 'Report has been removed.', 'success');
        },
        onError: () => {
          Swal.fire('Error', 'Failed to delete report.', 'error');
        }
      });
    }
  });
};

const priorityClasses = (priority) => ({
  'px-3 py-1 rounded-full text-xs font-semibold': true,
  'bg-yellow-100 text-yellow-800': priority === 'Medium',
  'bg-orange-100 text-orange-800': priority === 'Low-Medium',
  'bg-red-100 text-red-800': priority === 'High',
  'bg-green-100 text-green-800': priority === 'Low',
  'bg-gray-100 text-gray-800': priority === 'Varies'
});

const statusClasses = (status) => ({
  'px-3 py-1 rounded-full text-xs font-semibold': true,
  'bg-blue-100 text-blue-800': status === 'In Progress',
  'bg-gray-100 text-gray-800': status === 'Pending',
  'bg-green-100 text-green-800': status === 'Resolved'
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};
</script>

<style scoped>
table {
  width: 100%;
  table-layout: auto;
}
select {
  background-color: white;
}
</style>
