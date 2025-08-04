<template>
  <AdminLayout title="Users">
    <div class="w-full bg-white shadow-md rounded-lg">
      <div class="p-6">
        <div class="flex justify-between items-center border-b pb-4">
          <h1 class="text-2xl font-bold text-gray-800">Users</h1>
          <div class="flex space-x-4">
            <button @click="showModal = true"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
              <v-icon name="bi-plus" class="w-5 h-5" /> Add New User
            </button>
            <button
              class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center gap-2">
              <v-icon name="bi-download" class="w-4 h-4" /> Export
            </button>
          </div>
        </div>

        <div class="mt-6 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Meter Brand</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Serial #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Meter Size</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Reading</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Consumption</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount Due</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in users" :key="user.id">
                <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ user.id }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.name }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.email }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.phone }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.account_number }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.address }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.meter_brand }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.meter_serial_number }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ user.meter_size }} mm</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ formatNumber(user.last_reading) }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ formatNumber(user.consumption) }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">₱{{ formatCurrency(user.amount_due) }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ formatDate(user.due_date) }}</td>
                <td class="px-4 py-2 text-sm">
                  <span :class="statusClasses(user.payment_status)">
                    {{ user.payment_status }}
                  </span>
                </td>
                <td class="px-4 py-2 text-sm">
                  <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800" title="View">
                      <v-icon name="bi-eye-fill" class="w-5 h-5" />
                    </button>
                    <button class="text-yellow-500 hover:text-yellow-700" title="Edit">
                      <v-icon name="ri-edit-box-fill" class="w-5 h-5" />
                    </button>
                    <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-800" title="Delete">
                    <v-icon name="fa-trash" class="w-5 h-5" />
                    </button>

                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add New User Modal -->
    <Modal :show="showModal" @close="showModal = false">
      <template #title>
        <h3 class="text-lg font-medium text-white">Add New User</h3>
      </template>

      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name*</label>
            <input v-model="form.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email*</label>
            <input v-model="form.email" type="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input v-model="form.phone" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Account #</label>
            <input v-model="form.account_number" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>

          <!-- Barangay Dropdown -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Barangay*</label>
            <select v-model="selectedBarangay"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="" disabled>Select Barangay</option>
              <option v-for="brgy in barangays" :key="brgy" :value="brgy">{{ brgy }}</option>
            </select>
          </div>

          <!-- Auto-filled Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Address</label>
            <input v-model="form.address" type="text" readonly
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Meter Brand</label>
            <input v-model="form.meter_brand" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Serial #</label>
            <input v-model="form.meter_serial_number" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Meter Size (mm)</label>
            <input v-model="form.meter_size" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Generated Password</label>
                <input v-model="form.password" type="text" readonly
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Temporary Email</label>
                <input
                    type="text"
                    :value="tempEmail"
                    readonly
                    class="mt-2 w-[28rem] border rounded px-4 py-2 bg-gray-100 text-gray-700"
                />
            </div>


        </div>
      </div>

            <template #footer>
        <button @click="showModal = false"
          class="mr-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
          Cancel
        </button>
        <button @click="submitUser"
          :disabled="form.processing"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
          Save
        </button>
      </template>
    </Modal>
  </AdminLayout>
</template>


<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

defineProps({
  users: Array
});

// Modal visibility
const showModal = ref(false);

// Barangay list (Clarin, Bohol only)
const barangays = [
  "Bacani", "Bogtongbod", "Bonbon", "Bontud", "Buacao", "Buangan", "Cabog", "Caboy", "Caluwasan",
  "Candajec", "Cantoyoc", "Comaang", "Danahao", "Katipunan", "Lajog", "Mataub", "Nahawan",
  "Poblacion Centro", "Poblacion Norte", "Poblacion Sur", "Tangaran", "Tontunan", "Tubod", "Villaflor"
];

// Selected barangay
const selectedBarangay = ref('');

// Form data
const form = useForm({
  name: '',
  email: '',
  phone: '',
  account_number: '',
  address: '',
  meter_brand: '',
  meter_serial_number: '',
  meter_size: '',
  password: '',
  payment_status: 'Pending'
});

// Auto-fill address when barangay changes
watch(selectedBarangay, (value) => {
  form.address = value ? `${value}, Clarin, Bohol` : '';
});

// Auto-generate password from name and account number
watch([() => form.name, () => form.account_number], ([name, account]) => {
  if (name && account) {
    const namePart = name.slice(0, 3).toUpperCase();
    const accountPart = account.slice(0, 3).toUpperCase();
    form.password = namePart + accountPart;
  } else {
    form.password = '';
  }
});

// Temporary email generator
const tempEmail = computed(() => {
  const nameParts = form.name.trim().split(' ');
  const last = nameParts[0]?.toLowerCase() || '';
  const first = nameParts[1]?.toLowerCase() || '';
  return last && first ? `${last}${first}@email.com` : '';
});

// Submit form
const submitUser = () => {
  if (!selectedBarangay.value) {
    alert('Please select a barangay.');
    return;
  }

  form.post('/admin/users', {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
      selectedBarangay.value = '';
    }
  });
};

// Delete user
const deleteUser = (userId) => {
  if (confirm('Are you sure you want to delete this user?')) {
    router.delete(`/admin/users/${userId}`, {
      onSuccess: () => {
        alert('User deleted successfully.');
      },
      onError: () => {
        alert('Failed to delete user.');
      }
    });
  }
};

// Status badge styling
const statusClasses = (status) => ({
  'px-3 py-1 rounded-full text-xs font-semibold': true,
  'bg-green-100 text-green-800': status === 'Paid',
  'bg-yellow-100 text-yellow-800': status === 'Pending',
  'bg-red-100 text-red-800': status === 'Overdue'
});

// Utility formatters
const formatCurrency = (value) => value ? parseFloat(value).toFixed(2) : '0.00';
const formatNumber = (value) => value !== null ? parseFloat(value).toFixed(2) : '-';
const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>



<style scoped>
table {
  width: 100%;
  table-layout: auto;
}

/* Smooth modal transition */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Disabled button appearance */
button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

/* Input focus ring override */
input:focus,
select:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /* Tailwind blue-500 */
}

/* Read-only input styling */
input[readonly] {
  background-color: #f3f4f6; /* Tailwind gray-100 */
  color: #4b5563; /* Tailwind gray-700 */
}
</style>
