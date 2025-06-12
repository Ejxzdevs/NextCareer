<template>
  <MainLayout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Projects</h1>
      <p class="text-gray-600 mb-8">Explore available projects and find your next opportunity.</p>

      <div v-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <article
          v-for="project in projects"
          :key="project.id"
          class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 border border-gray-100"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-building text-gray-500 text-xl"></i>
            </div>
            <button
              aria-label="Bookmark this project"
              class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors duration-200"
            >
              <i class="far fa-bookmark text-xl"></i>
            </button>
          </div>

          <div class="flex items-center text-sm text-gray-500 mb-2">
            <span>{{ formatTimeAgo(project.created_at) }}</span>
          </div>

          <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-3">{{ project.title }}</h2>
          <p v-if="project.description" class="text-gray-600 text-sm my-5 line-clamp-3">
            {{ project.description }}
          </p>

          <div class="flex flex-wrap gap-3 mb-4">
            <span class="px-3 py-1 bg-green-200 text-black text-sm font-medium rounded-md">Contract</span>
            <span class="px-3 py-1 bg-green-200 text-black text-sm font-medium rounded-md">Remote</span>
          </div>

          <div class="flex items-center justify-between my-4">
            <div class="text-gray-600 text-sm">
              <i class="fas fa-user mr-2"></i>
              <span>{{ project.user?.email || 'Unknown' }}</span>
            </div>
            <div class="font-semibold text-gray-900">{{ formatCurrency(project.budget) }}</div>
          </div>

          <div class="mb-4">
            <div class="flex flex-wrap gap-2">
              <span
                v-for="skill in formatSkillsArray(project.skills)"
                :key="skill"
                class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded"
              >
                {{ skill }}
              </span>
            </div>
          </div>

          <button
            @click="openApplyModal(project)"
            class="w-full bg-[#334EAC] text-white font-semibold my-3 py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors duration-200 cursor-pointer"
            aria-label="Apply for this project"
          >
            Apply Now
          </button>
        </article>
      </div>

      <div v-else class="text-center bg-white rounded-xl shadow-lg p-10 mt-10">
        <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
        <p class="text-xl font-semibold text-gray-800 mb-2">No projects found</p>
        <p class="text-gray-600">Check back later for new opportunities.</p>
      </div>

      <div
        v-if="showApplyModal"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4 py-6"
      >
        <div class="relative bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto p-8 rounded-lg shadow-2xl">
          <button
            @click="closeApplyModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-3xl font-bold cursor-pointer transition-colors duration-200"
            aria-label="Close"
          >
            &times;
          </button>

          <form @submit.prevent="submitForm" class="space-y-5">

             <div>
              <h2 class="block font-medium text-gray-700 mb-1">
               Application for
                  <span>{{ selectedProject ? selectedProject.title : ' ' }}</span>
              </h2>
            </div>


            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Portfolio</label>
              <input type="hidden" v-model="form.project_id"  />
              <input
               v-model="form.link_portfolio"
                type="text"
                placeholder="Link your portfolio:"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Upload resume</label>
                <input
                type="file"
                @change="form.resume = $event.target.files[0]"
                accept=".pdf, .doc, .docx"
                placeholder="Link your portfolio:"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                required
              />
            </div>

            

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
              <textarea
               v-model="form.message"
                rows="4"
                placeholder="message(optional):"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                required
              ></textarea>
            </div>

            <div class="flex justify-center pt-4">
              <button
                type="submit"
                class="w-full py-3 bg-[#334EAC] text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out"
              >
                Submit Application
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

---

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3'
import { formatTimeAgo } from '../../utils/datetimeUtils';
import { formatCurrency } from '../../utils/numberUtils';

// Form state for creating and editing projects
const form = useForm({
  project_id: null,
  resume: null,
  message: '',
  link_portfolio: ''
})


const showApplyModal = ref(false);
const selectedProject = ref(null);

// Access projects from the Inertia page props
const { projects } = usePage().props;

function openApplyModal(project) {
  form.reset()
  // console.log('Opening apply modal for project:', project.id);
  selectedProject.value = project;
  form.project_id = project.id;
  showApplyModal.value = true;
}

function closeApplyModal() {
  form.reset()
  showApplyModal.value = false;
}

/**
 * Handles the submission of the application form.
 * page reload on success for immediate data refresh.
 */
function submitForm() {
    // Submit the form
    form.post(route('freelance.application'), {
      onSuccess: () => {
        showApplyModal.value = false
        window.location.reload()
      },
      onError: (errors) => {
        console.log('Form submission error:', errors)
        // You might want to display these errors to the user
      },
    })
}


function formatSkillsArray(skills) {
  if (!skills) return [];
  return skills.split(',').map(skill => skill.trim().toUpperCase()).filter(Boolean);
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 3;
  line-clamp: 3;
}
</style>