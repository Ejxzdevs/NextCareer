<template>
  <MainLayout>
    <div class="text-right mt-3 me-3">
      <button
        @click="openModalForCreate"
        class="py-[5px] px-2 border-1 border-gray-300 text-blue-500 hover:text-blue-700 cursor-pointer shadow-md rounded-sm"
      >
        <i class="fas fa-plus fa"></i>
        Post Project
      </button>
    </div>

    <div class="w-full py-10 px-6">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="(project, index) in projects"
          :key="project.id"
          class="bg-white shadow-md rounded-lg p-6 border border-gray-200 relative group"
        >
          <div class="flex justify-between items-start mb-2">
            <h2 class="text-xl font-semibold text-gray-800">{{ project.title }}</h2>

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

              <div
                v-show="activeDropdown === index"
                class="absolute right-0 mt-2 w-28 bg-white border border-gray-200 rounded shadow-md z-10"
              >
                <a
                  href="#"
                  @click.prevent="openModalForEdit(project)"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >Edit</a
                >
                <a
                  href="#"
                  @click.prevent="deleteProject(project.id)"
                  class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                  >Delete</a
                >
              </div>
            </div>
          </div>

          <div class="text-xs text-gray-400 mb-4">
            Posted on {{ formatDate(project.created_at) }}
          </div>

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

    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-2"
      @click.self="closeModal"
    >
      <div
        class="relative bg-white w-[450px] max-w-4xl max-h-[90vh] overflow-y-auto p-6 rounded-md shadow-2xl"
      >
        <button
          @click="closeModal"
          class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-2xl font-bold cursor-pointer"
          aria-label="Close"
        >
          <i class="fas fa-times"></i>
        </button>

        <h3 class="text-[20px] mb-2 mt-3">{{ form.id ? 'Edit Project' : 'Post a Project' }}</h3>

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
          <div class="flex justify-center items-center">
            <button
              type="submit"
              class="py-[5px] px-16 border-1 border-gray-300 text-blue-500 hover:text-blue-700 cursor-pointer shadow-md rounded-sm"
            >
              {{ form.id ? 'Update Project' : 'Post Project' }}
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

// Reactive state variables
const showModal = ref(false) // Controls visibility of the project creation/edit modal
const activeDropdown = ref(null) // Stores the index of the currently open project dropdown menu

// Retrieve projects data passed from the backend via Inertia props
const { projects } = usePage().props

// Form state for creating and editing projects
const form = useForm({
  id: null,
  title: '',
  description: '',
  category: '',
  skills: '',
  budget: '',
  start_date: '',
  deadline: '',
})

/**
 * Opens the modal for creating a new project.
 * Resets the form fields to ensure a clean slate.
 */
function openModalForCreate() {
  form.reset()
  showModal.value = true
}

/**
 * Opens the modal for editing an existing project.
 * Populates the form with the project's current data and formats dates for the input fields.
 * @param {object} project - The project object to be edited.
 */
function openModalForEdit(project) {
  form.id = project.id
  form.title = project.title
  form.description = project.description
  form.category = project.category
  form.skills = project.skills
  form.budget = project.budget
  // Convert dates to 'YYYY-MM-DD' format required by HTML date input
  form.start_date = project.start_date ? new Date(project.start_date).toISOString().split('T')[0] : ''
  form.deadline = project.deadline ? new Date(project.deadline).toISOString().split('T')[0] : ''
  showModal.value = true
  activeDropdown.value = null // Close any open dropdown after initiating edit
}

/**
 * Closes the project creation/edit modal.
 * Resets the form fields.
 */
function closeModal() {
  showModal.value = false
  form.reset()
}

/**
 * Handles the submission of the project form.
 * Performs either a PUT request for updating an existing project or
 * a POST request for creating a new one.
 * Includes a full page reload on success for immediate data refresh.
 */
function submitForm() {
  if (form.id) {
    // Update existing project
    form.put(route('employer.update', form.id), {
      onSuccess: () => {
        form.reset()
        showModal.value = false
        // Reload the page to fetch updated projects from the server
        window.location.reload()
      },
      onError: (errors) => {
        console.log('Form update error:', errors)
      },
    })
  } else {
    // Create new project
    form.post(route('employer.store'), {
      onSuccess: () => {
        form.reset()
        showModal.value = false
        // Reload the page to fetch the newly added project from the server
        window.location.reload()
      },
      onError: (errors) => {
        console.log('Form submission error:', errors)
      },
    })
  }
}

/**
 * Deletes a project after user confirmation.
 * Inertia.js handles the page refresh on successful deletion.
 * @param {number} projectId - The ID of the project to delete.
 */
function deleteProject(projectId) {
  if (confirm('Are you sure you want to delete this project?')) {
    form.delete(route('employer.destroy', projectId), {
      onSuccess: () => {
        activeDropdown.value = null // Close dropdown after deletion
      },
      onError: (errors) => {
        console.error('Error deleting project:', errors)
      },
    })
  }
}

/**
 * Toggles the visibility of a specific project's dropdown menu.
 * @param {number} index - The index of the project whose dropdown is being toggled.
 */
function toggleDropdown(index) {
  activeDropdown.value = activeDropdown.value === index ? null : index
}

/**
 * Closes any open dropdown menu if a click occurs outside of a dropdown wrapper.
 * @param {Event} event - The click event object.
 */
function handleClickOutside(event) {
  if (!event.target.closest('.dropdown-wrapper')) {
    activeDropdown.value = null
  }
}

// Lifecycle hooks for event listener management
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

/**
 * Formats a date string into a localized, human-readable format.
 * @param {string} date - The date string to format.
 * @returns {string} Formatted date string or 'N/A' if input is empty.
 */
function formatDate(date) {
  if (!date) return 'N/A'
  const d = new Date(date)
  return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}

/**
 * Formats a numerical amount into a currency string (e.g., $1,000).
 * @param {number} amount - The amount to format.
 * @returns {string} Formatted currency string or 'N/A' if input is empty.
 */
function formatCurrency(amount) {
  if (!amount) return 'N/A'
  return `$${Number(amount).toLocaleString()}`
}
</script>