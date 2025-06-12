<template>
  <MainLayout>
    <div class="container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-3">
          Find Your Next <span class="text-[#334EAC]">Venture</span>
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Explore a diverse range of available projects and discover exciting opportunities to
          showcase your skills.
        </p>
      </div>

      <div v-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <article
          v-for="project in projects"
          :key="project.id"
          class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col"
        >
          <div class="p-6 pb-4 flex flex-col h-full">
            <div class="flex justify-between items-start mb-4">
              <div
                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl"
              >
                <i class="fas fa-project-diagram"></i>
              </div>
              <button
                aria-label="Bookmark this project"
                class="text-gray-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-full p-2 transition-colors duration-200"
              >
                <i class="far fa-bookmark text-xl"></i>
              </button>
            </div>

            <div class="flex items-center text-sm text-gray-500 mb-2">
              <i class="far fa-clock mr-2"></i>
              <span>Posted {{ formatTimeAgo(project.created_at) }}</span>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
              {{ project.title }}
            </h2>
            <p v-if="project.description" class="text-gray-600 text-sm mb-3 project-description">
              {{ project.description }}
            </p>
            <button
              @click="openDetailsModal(project)"
              class="text-blue-600 hover:underline text-sm text-left mb-4 mt-auto focus:outline-none"
            >
              See more details
            </button>
          </div>

          <div
            class="mt-auto px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex-grow"
          >
            <div class="grid grid-cols-2 text-sm text-gray-700 gap-y-3 gap-x-2 mb-4">
              <div class="flex items-center">
                <i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>
                <div>
                  <span class="font-bold text-gray-900">{{
                    formatCurrency(project.budget)
                  }}</span>
                </div>
              </div>
              <div class="flex items-center">
                <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
                <div>
                  <span class="font-semibold text-gray-800">{{
                    formatDate(project.deadline)
                  }}</span>
                </div>
              </div>
              <div class="col-span-2 flex items-center">
                <i class="far fa-calendar-check text-gray-500 mr-2"></i>
                <div>
                  <span class="font-semibold text-gray-800">{{
                    formatDate(project.start_date)
                  }}</span>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="skill in formatSkillsArray(project.skills)"
                  :key="skill"
                  class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full transform hover:scale-105 transition-transform duration-200"
                >
                  {{ skill }}
                </span>
              </div>
            </div>

            <div class="mb-4 flex items-center text-sm text-gray-600">
              <i class="fas fa-user-circle mr-2 text-gray-500"></i>
              <span>{{ project.user?.email || 'Unknown Client' }}</span>
            </div>

            <button
              @click="openApplyModal(project)"
              class="w-full bg-[#334EAC] cursor-pointer text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              aria-label="Apply for this project"
            >
              Apply Now
            </button>
          </div>
        </article>
      </div>

      <div v-else class="text-center bg-white rounded-xl shadow-lg p-10 mt-10">
        <i class="fas fa-folder-open text-6xl text-gray-300 mb-6"></i>
        <p class="text-2xl font-bold text-gray-800 mb-3">No projects available right now</p>
        <p class="text-gray-600 text-lg">
          We're constantly adding new opportunities. Please check back soon!
        </p>
      </div>

      <!-- APPLY FORM -->
      <div
        v-if="showApplyModal"
        class="fixed inset-0 bg-black/60 bg-opacity-60 flex items-center justify-center z-50 p-4 sm:p-6"
      >
        <div
          class="relative bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto p-8 rounded-xl shadow-2xl transform scale-95 opacity-0 animate-fade-in-scale"
        >
          <button
            @click="closeApplyModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-4xl font-light cursor-pointer transition-colors duration-200 focus:outline-none"
            aria-label="Close"
          >
            &times;
          </button>

          <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
            Apply for <br /><span class="text-[#334EAC]"
              >{{ selectedProject ? selectedProject.title : '' }}</span
            >
          </h2>

          <form @submit.prevent="submitForm" class="space-y-6">
            <div>
              <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1"
                >Portfolio Link</label
              >
              <input
                id="portfolio"
                v-model="form.link_portfolio"
                type="url"
                placeholder="e.g., https://yourportfolio.com"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 shadow-sm"
                required
              />
            </div>

            <div>
              <label for="resume" class="block text-sm font-medium text-gray-700 mb-1"
                >Upload Resume (PDF, DOC, DOCX)</label
              >
              <input
                id="resume"
                type="file"
                @change="form.resume = $event.target.files[0]"
                accept=".pdf, .doc, .docx"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 shadow-sm"
                required
              />
            </div>

            <div>
              <label for="message" class="block text-sm font-medium text-gray-700 mb-1"
                >Cover Letter/Message (Optional)</label
              >
              <textarea
                id="message"
                v-model="form.message"
                rows="5"
                placeholder="Tell us why you're a great fit for this project..."
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 shadow-sm"
              ></textarea>
            </div>

            <div class="flex justify-center pt-4">
              <button
                type="submit"
                class="w-full py-3 bg-[#334EAC] cursor-pointer text-white font-bold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out text-lg"
              >
                Submit Application
              </button>
            </div>
          </form>
        </div>
      </div>

        <!-- SHOW POP UP TO VIEW FULL DESCRIPTION -->
      <div
        v-if="showDetailsModal"
        class="fixed inset-0 bg-black/60 bg-opacity-60 flex items-center justify-center z-50 p-4 sm:p-6"
      >
        <div
          class="relative bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 rounded-xl shadow-2xl transform scale-95 opacity-0 animate-fade-in-scale"
        >
          <button
            @click="closeDetailsModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-4xl font-light cursor-pointer transition-colors duration-200 focus:outline-none"
            aria-label="Close"
          >
            &times;
          </button>
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            {{ selectedProject ? selectedProject.title : 'Project Details' }}
          </h2>
          <p class="text-gray-600 mb-6">
            {{ selectedProject ? selectedProject.description : 'No description available.' }}
          </p>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 mb-6">
            <div class="flex items-center">
              <i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>
              <strong>Budget:</strong>
              <span class="ml-2 font-semibold text-gray-900">{{
                selectedProject ? formatCurrency(selectedProject.budget) : 'N/A'
              }}</span>
            </div>
            <div class="flex items-center">
              <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
              <strong>Deadline:</strong>
              <span class="ml-2 font-semibold text-gray-900">{{
                selectedProject ? formatDate(selectedProject.deadline) : 'N/A'
              }}</span>
            </div>
            <div class="flex items-center col-span-full">
              <i class="far fa-calendar-check text-gray-500 mr-2"></i>
              <strong>Start Date:</strong>
              <span class="ml-2 font-semibold text-gray-900">{{
                selectedProject ? formatDate(selectedProject.start_date) : 'N/A'
              }}</span>
            </div>
            <div class="flex items-center col-span-full">
              <i class="fas fa-user-circle text-gray-500 mr-2"></i>
              <strong>Client:</strong>
              <span class="ml-2 font-semibold text-gray-900">{{
                selectedProject ? (selectedProject.user?.email || 'Unknown Client') : 'N/A'
              }}</span>
            </div>
          </div>

          <div v-if="selectedProject && formatSkillsArray(selectedProject.skills).length" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Required Skills:</h3>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="skill in formatSkillsArray(selectedProject.skills)"
                :key="skill"
                class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full"
              >
                {{ skill }}
              </span>
            </div>
          </div>

          <div class="pt-4 border-t border-gray-100">
            <button
              @click="openApplyModal(selectedProject)"
              class="w-full bg-[#334EAC] cursor-pointer text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
              Apply Now
            </button>
          </div>
        </div>
      </div>

    </div>
  </MainLayout>
</template>

---

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { formatTimeAgo , formatDate } from '../../utils/datetimeUtils';
import { formatCurrency } from '../../utils/numberUtils';

// Form state for creating and editing projects
const form = useForm({
  project_id: null,
  resume: null,
  message: '',
  link_portfolio: '',
});

const showApplyModal = ref(false);
const showDetailsModal = ref(false);
const selectedProject = ref(null);

// Access projects from the Inertia page props
const { projects } = usePage().props;


/**
 * Opens the application modal for a specific project.
 * Resets the form and sets the selected project's ID.
 * @param {Object} project - The project object to apply for.
 */
function openApplyModal(project) {
  form.reset(); // Clear previous form data
  selectedProject.value = project;
  form.project_id = project.id;
  showDetailsModal.value = false; // Close details modal if open
  showApplyModal.value = true;
}

/**
 * Closes the application modal and resets the form.
 */
function closeApplyModal() {
  form.reset();
  showApplyModal.value = false;
}



/**
 * Opens the project details modal for a specific project.
 * @param {Object} project - The project object to display details for.
 */
function openDetailsModal(project) {
  selectedProject.value = project;
  showDetailsModal.value = true;
}

/**
 * Closes the project details modal.
 */
function closeDetailsModal() {
  showDetailsModal.value = false;
  selectedProject.value = null;
}

/**
 * Handles the submission of the application form.
 * Reloads the page on success for immediate data refresh (consider more dynamic updates in a real app).
 */
function submitForm() {
  // Submit the form
  form.post(route('freelance.application'), {
    onSuccess: () => {
      showApplyModal.value = false;
      window.location.reload();
    },
    onError: (errors) => {
      console.error('Form submission error:', errors);
    },
  });
}

/**
 * Formats a comma-separated string of skills into an array of trimmed, uppercase strings.
 * Filters out any empty strings.
 * @param {string} skills - The comma-separated skills string.
 * @returns {string[]} An array of formatted skill strings.
 */
function formatSkillsArray(skills) {
  if (!skills) return [];
  return skills.split(',').map((skill) => skill.trim().toUpperCase()).filter(Boolean);
}
</script>

<style scoped>
/* Utility class for line clamping */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 2;
  line-clamp: 2;
}
.line-clamp-3 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 3;
  line-clamp: 3;
}

.project-description {
  height: 4.5rem;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; 
  -webkit-box-orient: vertical;
  line-clamp: 3; 
}

/* Keyframe for modal apply form */
@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fade-in-scale {
  animation: fadeInScale 0.3s ease-out forwards;
}
</style>