<template>
  <MainLayout>
    <div class="container mx-auto px-4 py-6">
      <div class="text-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-3">
          Find Your Next <span class="text-[#334EAC]">Career</span>
        </h1>
        <p class="text-md text-gray-600 max-w-2xl mx-auto">
          Explore a diverse range of available projects and discover exciting opportunities to
          showcase your skills.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-[30%_70%] gap-4 lg:gap-0 sm:px-4 md:px-8 lg:px-16 xl:px-32">
        <aside class="bg-white p-4 rounded-xl shadow h-full md:h-screen overflow-y-auto sticky top-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select class="w-full border border-gray-300 rounded-md p-2">
                <option>All</option>
                <option>Web Development</option>
                <option>Design</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
              <input
                type="text"
                placeholder="e.g. 100 - 1000"
                class="w-full border border-gray-300 rounded-md p-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
              <select class="w-full border border-gray-300 rounded-md p-2">
                <option>Newest</option>
                <option>Budget: High to Low</option>
              </select>
            </div>
          </div>
        </aside>

        <section class="space-y-3 h-full md:h-screen overflow-y-auto pr-2">
          <div v-if="projects.length > 0" class="space-y-4">
            <div
              v-for="project in projects"
              :key="project.id"
              class="max-w-full sm:max-w-xl mx-auto lg:mx-2 bg-white rounded-xl shadow-md p-4 flex items-center justify-between space-x-4"
            >
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                  <img
                    src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg"
                    alt="Nike Logo"
                    class="w-6 h-6"
                  />
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">{{ project.title }}</h2>
                  <div class="flex items-center text-sm text-gray-500 space-x-2">
                    <button
                      class="cursor-pointer underline text-blue-600"
                      @click="openDetailsModal(project)"
                    >
                      See details
                    </button>
                  </div>
                </div>
              </div>

              <div class="text-right space-y-1">
                <button
                  @click="openApplyModal(project)"
                  class="text-xs font-semibold bg-blue-100 text-green-700 px-6 py-2 rounded-full cursor-pointer"
                >
                  Apply
                </button>
                <div class="text-sm text-gray-400">{{ formatTimeAgo(project.created_at) }}</div>
              </div>
            </div>
          </div>

          <div v-else class="text-center bg-white rounded-xl shadow p-10 mt-10">
            <i class="fas fa-folder-open text-6xl text-gray-300 mb-6"></i>
            <p class="text-2xl font-bold text-gray-800 mb-3">No projects available</p>
            <p class="text-gray-600 text-lg">
              We’re constantly adding new opportunities. Please check back soon!
            </p>
          </div>
        </section>
      </div>

      <ApplyModal
        :show="showApplyModal"
        :selectedProject="selectedProject"
        @close="closeApplyModal"
        @submitted="handleApplicationSubmitted"
      />

      <div v-if="showDetailsModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div
          class="bg-white max-w-2xl w-full p-6 rounded-xl shadow-xl animate-fade-in-scale relative overflow-y-auto max-h-[90vh]"
        >
          <button @click="closeDetailsModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">
            &times;
          </button>
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            {{ selectedProject?.title || 'Project Details' }}
          </h2>
          <p class="text-gray-700 mb-4">
            {{ selectedProject?.description || 'No description available.' }}
          </p>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800 mb-4">
            <div class="flex items-center">
              <i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>
              <strong>Budget:</strong>
              <span class="ml-2">{{ formatCurrency(selectedProject?.budget) }}</span>
            </div>
            <div class="flex items-center">
              <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
              <strong>Deadline:</strong>
              <span class="ml-2">{{ formatDate(selectedProject?.deadline) }}</span>
            </div>
            <div class="flex items-center col-span-full">
              <i class="far fa-calendar-check text-gray-500 mr-2"></i>
              <strong>Start Date:</strong>
              <span class="ml-2">{{ formatDate(selectedProject?.start_date) }}</span>
            </div>
            <div class="flex items-center col-span-full">
              <i class="fas fa-user-circle text-gray-500 mr-2"></i>
              <strong>Client:</strong>
              <span class="ml-2">{{ selectedProject?.user?.email || 'Unknown Client' }}</span>
            </div>
          </div>

          <div class="mb-4">
            <h3 class="text-sm font-semibold mb-2">Required Skills:</h3>
            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                {{ formatSkills(selectedProject?.skills) }}
              </span>
            </div>
          </div>

          <button
            @click="openApplyModal(selectedProject)"
            class="w-full bg-[#334EAC] text-white py-3 rounded-lg font-semibold hover:bg-blue-700"
          >
            Apply Now
          </button>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { formatTimeAgo, formatDate } from '../../utils/datetimeUtils';
import { formatSkills } from '../../utils/stringUtils';
import { formatCurrency } from '../../utils/numberUtils';
import ApplyModal from '../../components/modals/ApplyForm.vue';

const showApplyModal = ref(false);
const showDetailsModal = ref(false);
const selectedProject = ref(null);

const { projects } = usePage().props;

/**
 * Opens the application modal and sets the selected project.
 * @param {Object} project - The project data for which to open the application modal.
 */
function openApplyModal(project) {
  selectedProject.value = project;
  showApplyModal.value = true;
  showDetailsModal.value = false;
}

/**
 * Closes the application modal and clears the selected project.
 */
function closeApplyModal() {
  showApplyModal.value = false;
  selectedProject.value = null;
}

/**
 * Handles the successful submission event from the application modal.
 * Displays a success alert and reloads the page.
 */
function handleApplicationSubmitted() {
  alert('Your application has been submitted successfully!');
  window.location.reload();
}

/**
 * Opens the project details modal and sets the selected project.
 * @param {Object} project - The project data for which to open the details modal.
 */
function openDetailsModal(project) {
  selectedProject.value = project;
  showDetailsModal.value = true;
  showApplyModal.value = false;
}

/**
 * Closes the project details modal and clears the selected project.
 */
function closeDetailsModal() {
  showDetailsModal.value = false;
  selectedProject.value = null;
}
</script>

<style scoped>
.project-description {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  line-clamp: 3;
  overflow: hidden;
  text-overflow: ellipsis;
}

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