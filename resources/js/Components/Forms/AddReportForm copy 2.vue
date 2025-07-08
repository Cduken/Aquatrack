<script setup>
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { ref } from 'vue'
import {
  User, Phone, Landmark, Globe, MapPin, Home, FileText, Image as ImageIcon, XCircle, Droplet, Camera
} from 'lucide-vue-next'

import { ref } from 'vue'

const cameraInput = ref(null)

const openCameraInput = () => {
  if (!cameraInput.value) return
  cameraInput.value.click()
}


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
const MAX_FILE_SIZE = 10 * 1024 * 1024 // 10 MB
const captureMode = ref('upload')

const handleFileUpload = (e) => {
  const files = Array.from(e.target.files)
  const valid = []
  const invalid = []

  files.forEach(file => {
    if (file.size > MAX_FILE_SIZE || !['image/', 'video/'].some(type => file.type.startsWith(type))) {
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

  if ((form.photos.length + valid.length) > MAX_FILES) {
    Swal.fire({
      icon: 'error',
      title: 'File Limit Exceeded',
      text: `Maximum ${MAX_FILES} files allowed. You already selected ${form.photos.length}.`
    })
    return
  }

  valid.forEach(file => {
    form.photos.push(file)
    form.photo_previews.push(URL.createObjectURL(file))
  })

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
    Swal.fire({ icon: 'error', title: 'Please attach at least one file.' }); return
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
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <User class="w-4 h-4 text-blue-500" />
            Enter your full name
          </label>
          <input v-model="form.reporter_name" required placeholder="e.g. Juan Dela Cruz"
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
        </div>

        <div>
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Phone class="w-4 h-4 text-blue-500" />
            Phone Number (optional)
          </label>
          <input v-model="form.reporter_phone" placeholder="e.g. 09123456789"
            class="mt-1 w-full border rounded px-3 py-2 text-sm focus:ring-blue-300 focus:border-blue-500" />
        </div>

        <div>
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Landmark class="w-4 h-4 text-blue-500" />
            Municipality
          </label>
          <input readonly value="Clarin"
            class="mt-1 w-full bg-gray-100 border rounded px-3 py-2 text-sm cursor-not-allowed" />
        </div>

        <div>
          <label class="text-sm flex items-center gap-1 text-gray-700 font-medium">
            <Globe class="w-4 h-4 text-blue-500" />
            Province
          </label>
          <input readonly value="Bohol"
            class="mt-1 w-full bg-gray-100 border rounded px-3 py-2 text-sm cursor-not-allowed" />
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
            Purok / Street
          </label>
          <input v-model="form.purok" required placeholder="e.g. Purok 5, Sitio Mabini"
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
          placeholder="Briefly describe the water service issue..." />
      </div>

      <!-- 📸 Upload vs. Camera Mode -->
      <div class="space-y-4">
        <label class="text-sm flex items-center gap-1 text-gray-700 font-medium mb-1">
          <Camera class="w-4 h-4 text-blue-500" />
          Attach Photos or Video
        </label>

        <!-- Toggle Buttons -->
        <div class="flex gap-4">
          <button type="button"
            @click="captureMode = 'upload'"
            :class="captureMode === 'upload' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
            class="px-4 py-2 rounded-md text-sm font-medium shadow hover:bg-blue-700">
            Upload Files
          </button>
          <button type="button"
            @click="captureMode = 'camera'"
            :class="captureMode === 'camera' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
            class="px-4 py-2 rounded-md text-sm font-medium shadow hover:bg-blue-700">
            Take Photo / Video
          </button>
        </div>

        <!-- Upload Mode UI -->
        <div v-if="captureMode === 'upload'">
          <label for="file-upload"
            class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition">
            <ImageIcon class="mx-auto h-10 w-10 text-gray-400 mb-3" />
            <span class="text-blue-600 font-medium hover:underline">Click to upload files</span>
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, MP4 up to 10MB each (max {{ MAX_FILES }} files)</p>
            <input id="file-upload" type="file" multiple accept="image/*,video/mp4" @change="handleFileUpload"
              class="sr-only" />
          </label>
        </div>

        <!-- Camera Mode -->
        <div v-else>
          <input
            type="file"
            accept="image/*,video/*"
            capture="environment"
            @change="handleFileUpload"
            class="w-full border rounded px-3 py-2 text-sm"
          />
          <p class="text-xs text-gray-500 mt-2 text-center">
            Video capture is limited to ~5 seconds to keep files under 10MB. Compression to 720p is handled server-side.
          </p>
        </div>
      </div>

      <!-- Media Previews -->
      <div v-if="form.photo_previews.length" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        < v-for="(src, i) in form.photo_previews" :key="i" class="relative group">
          <video v-if="src.endsWith('.mp4')" :src="src" controls class="h-24 w-full object-cover rounded border" />
          <img v-else :src="src" class="h-24 w-full object-cover rounded border" />
          <button type="button" @click="removePhoto(i)"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
            <XCircle class="w-4 h-4" />
          </button>

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

