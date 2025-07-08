<script setup>
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

// Lucide icons
import {
  User, Phone, Landmark, Globe, MapPin, Home, FileText, Image as ImageIcon, XCircle, Droplet
} from 'lucide-vue-next'

const form = useForm({
  municipality: 'Clarin',
  province: 'Bohol',
  problem_type: '',
  problem_other: '',
  barangay: '',
  purok: '',
  description: '',
  photos: [],
  photo_previews: [],
  reporter_name: '',
  reporter_phone: ''
})

const barangays = [
  "Bacani", "Bogtongbod", "Bonbon", "Bontud", "Buacao", "Buangan", "Cabog", "Caboy", "Caluwasan",
  "Candajec", "Cantoyoc", "Comaang", "Danahao", "Katipunan", "Lajog", "Mataub", "Nahawan",
  "Poblacion Centro", "Poblacion Norte", "Poblacion Sur", "Tangaran", "Tontunan", "Tubod", "Villaflor"
]

const MAX_FILES = 5
const MAX_FILE_SIZE = 10 * 1024 * 1024

const handleFileUpload = (e) => {
  const files = Array.from(e.target.files)
  const valid = [], invalid = []

  files.forEach(file => {
    if (file.size > MAX_FILE_SIZE || !file.type.startsWith('image/')) {
      invalid.push(file.name)
    } else {
      valid.push(file)
    }
  })

  if (invalid.length) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid File(s)',
      html: invalid.join('<br>'),
      confirmButtonColor: '#3085d6'
    })
  }

  form.photos.push(...valid)
  valid.forEach(f => form.photo_previews.push(URL.createObjectURL(f)))
  e.target.value = ''
}

const removePhoto = (i) => {
  URL.revokeObjectURL(form.photo_previews[i])
  form.photos.splice(i, 1)
  form.photo_previews.splice(i, 1)
}

const submit = () => {
  if (!form.problem_type) {
    Swal.fire({ icon: 'error', title: 'Please select a water issue type.' }); return
  }
  if (form.problem_type === 'Others' && !form.problem_other.trim()) {
    Swal.fire({ icon: 'error', title: 'Please describe the other issue.' }); return
  }
  if (!form.photos.length) {
    Swal.fire({ icon: 'error', title: 'At least one photo is required.' }); return
  }

  form.post(route('reports.store'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      form.photo_previews.forEach(URL.revokeObjectURL)
      form.photo_previews = []
      Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Report submitted!',
        timer: 3000,
        showConfirmButton: false
      })
    }
  })
}
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10">
    <!-- 🔵 Header with Logo and Title -->
    <div class="bg-blue-600 text-white rounded-t-lg px-6 py-4 flex items-center gap-4">
      <img src="/images/logo.png" alt="Logo" class="h-10 w-auto" />
      <h2 class="text-lg font-semibold">Report Water Service Issue</h2>
    </div>

    <!-- 📝 Form Body -->
    <form @submit.prevent="submit" class="bg-white p-6 rounded-b-lg shadow space-y-6">
      <!-- Problem Type -->
      <div>
        <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
          <Droplet class="w-4 h-4 text-blue-500" />
          Type of Water Problem
        </label>
        <select v-model="form.problem_type" required
          class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500">
          <option value="" disabled>Select a problem type</option>
          <option>Murky Water</option>
          <option>Low Pressure</option>
          <option>Water Leaks</option>
          <option>Others</option>
        </select>
      </div>

      <div v-if="form.problem_type === 'Others'">
        <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
          <FileText class="w-4 h-4 text-blue-500" />
          Specify the Problem
        </label>
        <input v-model="form.problem_other" required placeholder="e.g. Burst pipe, broken valve..."
          class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
      </div>

      <!-- Reporter Info -->
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label for="reporter_name" class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <User class="w-4 h-4 text-blue-500" />
            Enter your full name
          </label>
          <input v-model="form.reporter_name" required id="reporter_name" placeholder="e.g. Juan Dela Cruz"
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
        </div>

        <div>
          <label for="reporter_phone" class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Phone class="w-4 h-4 text-blue-500" />
            Phone Number (optional)
          </label>
          <input v-model="form.reporter_phone" id="reporter_phone" placeholder="e.g. 09123456789"
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
        </div>

        <div>
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Landmark class="w-4 h-4 text-blue-500" />
            Municipality
          </label>
          <input readonly value="Clarin" class="mt-1 w-full bg-gray-100 border rounded px-3 py-2 text-sm" />
        </div>

        <div>
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Globe class="w-4 h-4 text-blue-500" />
            Province
          </label>
          <input readonly value="Bohol" class="mt-1 w-full bg-gray-100 border rounded px-3 py-2 text-sm" />
        </div>

        <div class="sm:col-span-2">
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <MapPin class="w-4 h-4 text-blue-500" />
            Barangay
          </label>
          <select v-model="form.barangay" required
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500">
            <option value="" disabled>Select your barangay</option>
            <option v-for="b in barangays" :key="b" :value="b">{{ b }}</option>
          </select>
        </div>

        <div class="sm:col-span-2">
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Home class="w-4 h-4 text-blue-500" />
            Enter Purok / Street
          </label>
          <input v-model="form.purok" required placeholder="e.g. Purok 2, Main Street"
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
          <FileText class="w-4 h-4 text-blue-500" />
          Describe the issue
        </label>
        <textarea v-model="form.description" rows="3" required
          class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500"
          placeholder="Tell us what happened, when, and where the issue occurred..." />
      </div>

<!-- File Upload Section (New Design) -->
<div>
  <label class="text-sm flex items-center gap-1 text-gray-700 font-medium mb-1">
    <ImageIcon class="w-4 h-4 text-blue-500" />
    Upload Photos
  </label>

  <label for="file-upload"
         class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition">
    <ImageIcon class="mx-auto h-10 w-10 text-gray-400 mb-3" />
    <span class="text-blue-600 font-medium hover:underline">
      Click to upload files
    </span>
    <p class="mt-1 text-xs text-gray-500">
      PNG, JPG up to 10MB each (max {{ MAX_FILES }} files)
    </p>
    <input id="file-upload" type="file" multiple accept="image/*" @change="handleFileUpload" class="sr-only" />
  </label>
</div>


      <!-- Photo Previews -->
      <div v-if="form.photo_previews.length" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        <div v-for="(src, i) in form.photo_previews" :key="i" class="relative group">
          <img :src="src" class="h-24 w-full object-cover rounded border" />
          <button type="button" @click="removePhoto(i)"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
            <XCircle class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- Submit Actions -->
      <div class="flex justify-end gap-3">
        <button type="button" @click="form.reset()"
          class="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200">
          Cancel
        </button>
        <button type="submit" :disabled="form.processing"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium disabled:opacity-70">
          Submit Report
        </button>
      </div>
    </form>
  </div>
</template>
