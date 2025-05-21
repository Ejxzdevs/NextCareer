<template>
    <MainLayout>
  
      <div class="text-right mt-3 me-3">
        <button
          @click="showModal = true"
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
        @click.self="showModal = false"
      >
        <!-- Modal Content Container -->
        <div class="relative bg-white w-[450px] max-w-4xl max-h-[90vh] overflow-y-auto p-6 rounded-md shadow-2xl">
      
          <button
            @click="showModal = false"
            class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-2xl font-bold cursor-pointer"
            aria-label="Close"
          >
          <i class="fas fa-times"></i>
          </button>
  
          <!-- Title -->
          <h3 class="text-[20px] mb-2 mt-3">Post a Project</h3>
          <form @submit.prevent="submitForm" class="space-y-6">

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
                <input type="text" 
                placeholder="Skill set: " class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                v-model="form.skills" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Budget Amount ($)</label>
              <input
                type="number"
                v-model="form.budget"
                placeholder="budget:"
                class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Project Start Date</label>
              <input
                type="date"
                v-model="form.startDate"
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
  
  <script>
  export default {
    data() {
      return {
        showModal: false,
        form: {
          title: '',
          description: '',
          category: '',
          skills: '',
          budget: '',
          startDate: '',
          deadline: '',
        },
      };
    },
    methods: {
      submitForm() {
        console.log('Form submitted:', this.form);
        this.showModal = false;
        // Optional: Reset form if needed
      },
    },
  };
  </script>
  