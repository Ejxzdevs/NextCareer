<template>
  <MainLayout>
    <!-- Button to Open Modal -->
    <div class="text-right mt-3 me-3">
      <button
        @click="openModal"
        class="py-[5px] px-2 border-1 border-gray-300 text-blue-500 hover:text-blue-700 cursor-pointer shadow-md rounded-sm">
        <i class="fas fa-plus fa"></i>
        Post Project
      </button>
    </div>

    <div>
      <p>hello</p>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-2"
      @click.self="closeModal"
    >
      <!-- Modal Content -->
      <div class="relative bg-white w-[450px] max-w-4xl max-h-[90vh] overflow-y-auto p-6 rounded-md shadow-2xl">

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

          <!-- Hidden User ID Input -->
          <input type="hidden" v-model="form.id" />

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
            <input
              type="text"
              v-model="form.title"
              placeholder="Title:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Project Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Description:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category / Industry</label>
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
            <label class="block text-sm font-medium text-gray-700 mb-1">Required Skills</label>
            <input
              type="text"
              v-model="form.skills"
              placeholder="Skill set:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Budget Amount ($)</label>
            <input
              type="number"
              v-model="form.budget"
              placeholder="Budget:"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Project Start Date</label>
            <input
              type="date"
              v-model="form.start_date"
              class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline / End Date</label>
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
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const page = usePage()
const showModal = ref(false)
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

// Open modal and pre-fill user ID
function openModal() {
  form.id = page.props.user?.id || ''
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
      console.log('Form submission error:', errors);
    },
  })
}

</script>
