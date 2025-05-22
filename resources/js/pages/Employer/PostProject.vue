<template>
  <MainLayout>
    <!-- Button to Open Modal -->
    <div class="text-right mt-3 me-3">
      <button
        @click="openModal"
        class="py-[5px] px-2 border-1 border-gray-300 text-blue-500 hover:text-blue-700 cursor-pointer shadow-md rounded-sm"
      >
        <i class="fas fa-plus fa"></i>
        Post Project
      </button>
    </div>

    <div class="w-full py-10 px-6">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Loop over projects -->
        <div
          v-for="(project, index) in projects"
          :key="project.id"
          class="bg-white shadow-md rounded-lg p-6 border border-gray-200 relative group"
        >
          <div class="flex justify-between items-start mb-2">
            <!-- Project title -->
            <h2 class="text-xl font-semibold text-gray-800">{{ project.title }}</h2>

            <!-- Dropdown Button -->
            <div class="relative dropdown-wrapper">
              <button
                @click="toggleDropdown(index)"
                class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div
                v-show="activeDropdown === index"
                class="absolute right-0 mt-2 w-28 bg-white border border-gray-200 rounded shadow-md z-10"
              >
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >Edit</a
                >
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                  >Delete</a
                >
              </div>
            </div>
          </div>

          <!-- Posted Date -->
          <div class="text-xs text-gray-400 mb-4">
            Posted on {{ formatDate(project.created_at) }}
          </div>

          <!-- Project Description -->
          <p class="text-gray-700 text-sm mb-4">
            {{ project.description }}
          </p>

          <div class="grid grid-cols-2 text-sm text-gray-700 gap-2 mb-4">
            <div><strong>Category:</strong> {{ project.category }}</div>
            <div><strong>Skills:</strong> {{ project.skills || 'N/A' }}</div>
            <div><strong>Budget:</strong> {{ formatCurrency(project.budget) }}</div>
            <div><strong>Start Date:</strong> {{ formatDate(project.start_date) }}</div>
            <div><strong>Deadline:</strong> {{ formatDate(project.deadline) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-2"
      @click.self="closeModal"
    >
      <!-- Modal Content -->
      <div
        class="relative bg-white w-[450px] max-w-4xl max-h-[90vh] overflow-y-auto p-6 rounded-md shadow-2xl"
      >
        <!-- Close Button -->
        <button
          @click="closeModal"
          class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-2xl font-bold cursor-pointer"
          aria-label="Close"
        >
          <i class="fas fa-times"></i>
        </button>

        <!-- Modal Title -->
        <h3 class="text-[20px] mb-2 mt-3">Post a Project</h3>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
          <input type="hidden" v-model="form.id" />
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Project Title</label
            >
            <input
              type="text"
              v-model="form.title"
              placeholder="Title:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Project Description</label
            >
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Description:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Category / Industry</label
            >
            <select
              v-model="form.category"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Select a category</option>
              <option value="web-dev">Web Development</option>
              <option value="mobile-dev">Mobile Development</option>
              <option value="software-dev">Software Development</option>
              <option value="game-dev">Game Development</option>
              <option value="data-science">Data Science</option>
              <option value="ai-ml">AI / Machine Learning</option>
              <option value="cybersecurity">Cybersecurity</option>
              <option value="devops">DevOps</option>
              <option value="cloud-computing">Cloud Computing</option>
              <option value="blockchain">Blockchain</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Required Skills</label
            >
            <input
              type="text"
              v-model="form.skills"
              placeholder="Skill set:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Budget Amount ($)</label
            >
            <input
              type="number"
              v-model="form.budget"
              placeholder="Budget:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Project Start Date</label
            >
            <input
              type="date"
              v-model="form.start_date"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Deadline / End Date</label
            >
            <input
              type="date"
              v-model="form.deadline"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <!-- Submit -->
          <div class="flex justify-center items-center">
            <button
              type="submit"
              class="py-[5px] px-16 border-1 border-gray-300 text-blue-500 hover:text-blue-700 cursor-pointer shadow-md rounded-sm"
            >
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const showModal = ref(false)
const activeDropdown = ref(null)
const { projects, user } = usePage().props

const form = useForm({
  id: '',
  title: '',
  description: '',
  category: '',
  skills: '',
  budget: '',
  start_date: '',
  deadline: '',
})

// Open modal and pre-fill user ID (not sure why you assign id here, but keeping as you did)
function openModal() {
  form.id = user?.id || ''
  showModal.value = true
}

// Close modal
function closeModal() {
  showModal.value = false
}

// Submit form to the backend
function submitForm() {
  form.post(route('employer.store'), {
    onSuccess: () => {
      form.reset()
      showModal.value = false
    },
    onError: (errors) => {
      console.log('Form submission error:', errors)
    },
  })
}

// Toggle Dropdown visibility
function toggleDropdown(index) {
  activeDropdown.value = activeDropdown.value === index ? null : index
}

// Close dropdown on click outside
function handleClickOutside(event) {
  if (!event.target.closest('.dropdown-wrapper')) {
    activeDropdown.value = null
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Helper to format dates nicely
function formatDate(date) {
  if (!date) return 'N/A'
  const d = new Date(date)
  return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}

// Helper to format budget/currency nicely
function formatCurrency(amount) {
  if (!amount) return 'N/A'
  return `$${Number(amount).toLocaleString()}`
}
</script>
