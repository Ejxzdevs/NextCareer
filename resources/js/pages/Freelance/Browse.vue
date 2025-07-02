<template>
  <MainLayout>
    <div class="container mx-auto px-4 py-6">
      <div class="text-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-3">
          Find Your Next <span class="text-[#334EAC]">Client</span>
        </h1>
        <p class="text-md text-gray-600 max-w-2xl mx-auto">
          Explore a diverse range of available projects and discover exciting opportunities to
          showcase your skills.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-[30%_70%] gap-4 lg:gap-0 sm:px-4 md:px-8 lg:px-16 xl:px-32">
        <!-- Sidebar Filters -->
        <aside class="bg-white p-4 rounded-xl shadow h-full md:h-screen overflow-y-auto sticky top-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>

          <!-- Search -->
          <input
            v-model="searchTitle"
            type="text"
            placeholder="Search title"
            class="w-full border border-gray-300 rounded-md p-2 mb-4"
          />

          <!-- Category -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select
              v-model="category"
              class="w-full border border-gray-300 rounded-md p-2"
            >
              <option value="">All</option>
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
              <option value="design">UI/UX Design</option>
              <option value="marketing">Marketing</option>
              <option value="writing">Content Writing</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
            <input
              v-model="budgetRange"
              type="text"
              placeholder="e.g. 100 - 1000"
              class="w-full border border-gray-300 rounded-md p-2"
            />
            <small class="text-xs text-gray-400">Format: min - max</small>
          </div>
        </aside>

        <!-- Projects List -->
        <section class="space-y-3 h-full md:h-screen overflow-y-auto pr-2">
          <div v-if="projects.length > 0" class="space-y-4">
            <div
              v-for="project in projects"
              :key="project.id"
              class="max-w-full sm:max-w-xl mx-auto lg:mx-2 bg-white rounded-xl shadow-md p-4 flex items-center justify-between space-x-4"
            >
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                  <i class=" text-gray-700 fas fa-building"></i>
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

      <!-- Modals -->
      <ApplyModal
        :show="showApplyModal"
        :selectedProject="selectedProject"
        @close="closeApplyModal"
        @submitted="handleApplicationSubmitted"
      />

      <ProjectDetailsModal
        :show="showDetailsModal"
        :project="selectedProject"
        @close="closeDetailsModal"
        @openApplyModal="openApplyModal"
      />
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { formatTimeAgo } from '../../utils/datetimeUtils';
import ApplyModal from '../../components/modals/ApplyForm.vue';
import ProjectDetailsModal from '../../components/modals/ShowProjectDetails.vue';

const showApplyModal = ref(false);
const showDetailsModal = ref(false);
const selectedProject = ref(null);

const { projects: allProjects } = usePage().props;

// Filters
const searchTitle = ref('');
const category = ref('');
const budgetRange = ref('');

// Filtered Projects
const projects = computed(() => {
  return allProjects.filter(project => {
    const matchesTitle = project.title?.toLowerCase().includes(searchTitle.value.toLowerCase());

    const matchesCategory = category.value === '' || project.category === category.value;

    const matchesBudget = (() => {
      if (!budgetRange.value.includes('-')) return true;

      const [minStr, maxStr] = budgetRange.value.split('-').map(s => s.trim());
      const min = parseFloat(minStr);
      const max = parseFloat(maxStr);
      if (isNaN(min) || isNaN(max)) return true;

      const budget = parseFloat(project.budget);
      if (isNaN(budget)) return false;

      return budget >= min && budget <= max;
    })();

    return matchesTitle && matchesCategory && matchesBudget;
  });
});

// Modal Functions
function openApplyModal(project) {
  selectedProject.value = project;
  showApplyModal.value = true;
  showDetailsModal.value = false;
}

function closeApplyModal() {
  showApplyModal.value = false;
  selectedProject.value = null;
}

function handleApplicationSubmitted() {
  alert('Your application has been submitted successfully!');
  window.location.reload();
}

function openDetailsModal(project) {
  selectedProject.value = project;
  showDetailsModal.value = true;
  showApplyModal.value = false;
}

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
</style>
