<template>
  <div class="bg-white p-6 rounded-lg shadow-md max-w-xl w-full mx-auto mt-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Submit Water Problem Report</h2>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Problem Type -->
      <div>
        <label for="problem" class="block text-sm font-medium text-gray-700">Problem Type</label>
        <select id="problem" v-model="form.problem"
                class="mt-1 block w-full border rounded-md px-3 py-2 text-sm text-gray-700">
          <option value="">Select a problem</option>
          <option>Low Pressure</option>
          <option>No Water</option>
          <option>Leakage</option>
          <option>Others</option>
        </select>
      </div>

      <!-- Purok -->
      <div>
        <label for="purok" class="block text-sm font-medium text-gray-700">Purok</label>
        <input id="purok" v-model="form.purok" type="text"
               class="mt-1 block w-full border rounded-md px-3 py-2 text-sm text-gray-700" />
      </div>

      <!-- Barangay -->
      <div>
        <label for="barangay" class="block text-sm font-medium text-gray-700">Barangay</label>
        <select id="barangay" v-model="form.barangay"
                class="mt-1 block w-full border rounded-md px-3 py-2 text-sm text-gray-700">
          <option value="">Select Barangay</option>
          <option>Poblacion</option>
          <option>Tubod</option>
          <option>Cabulihan</option>
          <!-- Add more as needed -->
        </select>
      </div>

      <!-- Optional Details -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Additional Details (optional)</label>
        <textarea id="description" v-model="form.details"
                  class="mt-1 block w-full border rounded-md px-3 py-2 text-sm text-gray-700"
                  rows="3" placeholder="Describe the issue..."></textarea>
      </div>

      <!-- Submit / Cancel -->
      <div class="flex justify-end gap-3">
        <button type="button" @click="$emit('close')" class="text-sm text-gray-600 hover:text-gray-800">
          Cancel
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
          Submit Report
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const emit = defineEmits(['close'])

const form = ref({
  problem: '',
  purok: '',
  barangay: '',
  details: ''
})

function handleSubmit() {
  // 🚧 Replace with actual POST logic via Inertia or API
  console.log('Submitting form:', form.value)
  emit('close')
}
</script>
