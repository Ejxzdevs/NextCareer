<template>
  <MainLayout>
    <div class="text-right mt-6 me-6">
      <button
        @click="openModalForCreate"
        class="py-2 px-4 cursor-pointer bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition duration-300 ease-in-out"
      >
        <i class="fas fa-plus mr-2 "></i>
        Post Project
      </button>
    </div>

    <div class="w-full py-10 px-6">
      <div class="max-w-7xl mx-auto">
        <div class="text-center sm:text-left mb-8">
          <h1 class="text-4xl font-extrabold text-gray-900">Your Job Posts</h1>
          <p class="text-xl text-gray-600 mt-2">Manage and view the projects you've listed.</p>
        </div>

        <div
          v-if="projects && projects.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <div
            v-for="(project, index) in projects"
            :key="project.id"
            class="bg-white rounded-xl shadow-lg p-7 border border-gray-100 flex flex-col relative group hover:shadow-xl transition-shadow duration-300 ease-in-out"
          >
            <div class="absolute top-4 right-4 dropdown-wrapper">
              <button
                @click.stop="toggleDropdown(index)"
                class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer p-1 rounded-full hover:bg-gray-50"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
              </button>

              <div
                v-show="activeDropdown === index"
                class="absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-md shadow-lg z-10 overflow-hidden origin-top-right scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition duration-200 ease-out"
                :class="{ 'scale-100 opacity-100': activeDropdown === index }"
              >
                <a
                  href="#"
                  @click.prevent="openModalForEdit(project)"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                  >Edit</a
                >
                <a
                  href="#"
                  @click.prevent="deleteProject(project.id)"
                  class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
                  >Delete</a
                >
              </div>
            </div>

            <div class="flex items-center mb-4">
              <div
                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl"
              >
                <i class="fas fa-project-diagram"></i>
              </div>
              <div class="ms-2">
                <p class="text-md font-medium text-gray-800">{{ userEmail }}</p>
                <p class="text-xs text-gray-500">
                  Posted {{ formatTimeAgo(project.created_at) }}
                </p>
              </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2 leading-tight">
              {{ project.title }}
            </h2>

            <div class="flex flex-wrap gap-2 mb-4">
              <span
                v-if="project.category"
                class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
              >
                  {{ formatSkills(project?.category) }}
              </span>
              <span
                v-if="project.skills"
                class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
              >
                {{ formatSkills(project.skills) }}
              </span>
            </div>

            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
              {{ project.description }}
            </p>

            <div
              class="grid grid-cols-2 text-sm text-gray-700 gap-2 mb-4 mt-auto pt-4 border-t border-gray-100"
            >
              <div>
                <strong>Budget: </strong>
                <span class="font-semibold text-gray-900">{{
                  formatCurrency(project.budget)
                }}</span>
              </div>
              <div>
                <strong>Start Date: </strong>
                <span class="font-semibold text-gray-900">{{
                  formatDate(project.start_date)
                }}</span>
              </div>
              <div class="col-span-2">
                <strong>Deadline: </strong>
                <span class="font-semibold text-gray-900">{{
                  formatDate(project.deadline)
                }}</span>
              </div>
            </div>
            <button
              @click="openDetailsModal(project)"
              class="mt-4 w-full cursor-pointer py-2 px-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition duration-300 ease-in-out"
            >
              See More
            </button>
          </div>
        </div>

        <div
          v-else
          class="text-center bg-white rounded-xl shadow-lg p-10 mt-10 text-gray-500 text-lg"
        >
          <i class="fas fa-box-open text-5xl mb-4 text-gray-300"></i>
          <p class="text-xl font-semibold mb-2">No projects posted yet!</p>
          <p>
            It looks like you haven't listed any projects. Start by clicking the "Post New Project"
            button.
          </p>
        </div>
      </div>
    </div>

  <!-- EDIT & ADD PROJECT MODAL -->
  <div
      v-if="showEditCreateModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4 py-6"
      @click.self="closeEditCreateModal"
    >
      <div
        class="relative bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto p-8 rounded-lg shadow-2xl"
      >
        <button
          @click="closeEditCreateModal"
          class="absolute top-3 right-3 text-red-400 hover:text-red-600 text-3xl font-bold cursor-pointer transition-colors duration-200"
          aria-label="Close"
        >
          &times;
        </button>

        <h3 class="text-2xl text-center font-bold text-gray-800 mb-6 mt-2">
          {{ form.id ? 'Update Details' : 'Post a New Project' }}
        </h3>

        <form @submit.prevent="submitForm" class="space-y-5">
          <input type="hidden" v-model="form.id" />
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
            <input
              type="text"
              v-model="form.title"
              placeholder="e.g: Build an E-commerce Site"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Project Description</label
            >
            <textarea
              v-model="form.description"
              rows="4"
              placeholder="Descriptions:"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              required
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Category / Industry</label
            >
            <select
              v-model="form.category"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
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
              <option value="design">UI/UX Design</option>
              <option value="marketing">Marketing</option>
              <option value="writing">Content Writing</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Required Skills (comma-separated)</label
            >
            <input
              type="text"
              v-model="form.skills"
              placeholder="e.g: PHP, HTML, CSS, JavaScript"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Budget Amount ($)</label
            >
            <input
              type="number"
              v-model="form.budget"
              placeholder="Amount:"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
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
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
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
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              required
            />
          </div>
          <div class="flex justify-center pt-4">
            <button
              type="submit"
              class="w-full py-3 bg-blue-600 cursor-pointer text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition duration-300 ease-in-out"
            >
              {{ form.id ? 'Update' : 'Post Project' }}
            </button>
          </div>
        </form>
      </div>
  </div>

  <!-- VIEW FULL DETAILS POP UP -->
    <ProjectDetailsModal
      :show="showDetailsModal"
      :project="selectedProject"
      @close="closeDetailsModal"
    />
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { formatTimeAgo, formatDate } from '../../utils/datetimeUtils';
import { formatSkills} from '../../utils/stringUtils';
import { formatCurrency } from '../../utils/numberUtils';
import ProjectDetailsModal from '../../components/modals/ShowProjectDetails.vue';

// Reactive state variables
const showEditCreateModal = ref(false);
const showDetailsModal = ref(false);
const activeDropdown = ref(null);
const selectedProject = ref(null);

// Get user and projects data from Inertia props
// Then extract the email from the user object
const { user, projects } = usePage().props;
const userEmail = user.email;

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
});

/**
 * Opens the modal for creating a new project.
 * Resets the form fields to ensure a clean slate.
 */
function openModalForCreate() {
  form.reset();
  showEditCreateModal.value = true;
}

/**
 * Opens the modal for editing an existing project.
 * Populates the form with the project's current data and formats dates for the input fields.
 * @param {object} project - The project object to be edited.
 */
function openModalForEdit(project) {
  form.id = project.id;
  form.title = project.title;
  form.description = project.description;
  form.category = project.category;
  form.skills = project.skills;
  form.budget = project.budget;
  // Convert dates to 'YYYY-MM-DD' format required by HTML date input
  form.start_date = project.start_date ? new Date(project.start_date).toISOString().split('T')[0] : '';
  form.deadline = project.deadline ? new Date(project.deadline).toISOString().split('T')[0] : '';
  showEditCreateModal.value = true;
  activeDropdown.value = null;
}

/**
 * Closes the project creation/edit modal.
 * Resets the form fields.
 */
function closeEditCreateModal() {
  showEditCreateModal.value = false;
  form.reset();
}

/**
 * Opens the project details modal.
 * @param {object} project - The project object to display details for.
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
        form.reset();
        showEditCreateModal.value = false;
        window.location.reload();
      },
      onError: (errors) => {
        console.log('Form update error:', errors);
      },
    });
  } else {
    // Create new project
    form.post(route('employer.store'), {
      onSuccess: () => {
        form.reset();
        showEditCreateModal.value = false;
        window.location.reload();
      },
      onError: (errors) => {
        console.log('Form submission error:', errors);
      },
    });
  }
}

/**
 * Deletes a project after user confirmation.
 * Inertia.js handles the page refresh on successful deletion.
 * @param {number} projectId - The ID of the project to delete.
 */
function deleteProject(projectId) {
  if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
    form.delete(route('employer.destroy', projectId), {
      onSuccess: () => {
        activeDropdown.value = null;
        window.location.reload();
      },
      onError: (errors) => {
        console.error('Error deleting project:', errors);
        // You might want to display these errors to the user
      },
    });
  }
}

/**
 * Toggles the visibility of a specific project's dropdown menu.
 * @param {number} index - The index of the project whose dropdown is being toggled.
 */
function toggleDropdown(index) {
  activeDropdown.value = activeDropdown.value === index ? null : index;
}

/**
 * Closes any open dropdown menu if a click occurs outside of a dropdown wrapper.
 * @param {Event} event - The click event object.
 */
function handleClickOutside(event) {
  if (activeDropdown.value !== null && !event.target.closest('.dropdown-wrapper')) {
    activeDropdown.value = null;
  }
}

// Lifecycle hooks for event listener management
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>