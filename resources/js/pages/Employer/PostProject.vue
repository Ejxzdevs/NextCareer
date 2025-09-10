<template>
  <MainLayout>
    <!-- Header -->
    <div class="mt-6 text-center">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 text-center title">
        Your Job Posts
      </h1>
      <p class="text-lg text-gray-600 mt-2">
        Manage and view the projects you've listed.
      </p>
    </div>

    <div class="w-full px-6 py-3">
      <div class="max-w-7xl mx-auto">
        <!-- Post Project Button -->
        <div class="flex sm:justify-end mb-6">
          <button
            @click="openModalForCreate"
            class="flex items-center gap-2 py-2 px-5 cursor-pointer bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out"
          >
            <i class="fas fa-plus"></i>
            Post Project
          </button>
        </div>

        <!-- FILTER -->
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Filter by Job Post -->
            <div v-if="projects && projects.length > 0">
              <label
                for="job-filter"
                class="block text-sm font-medium text-gray-700 mb-1"
              >
                Filter by Job Post
              </label>
              <select
                v-model="selectProjectCategory"
                id="job-filter"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
              >
                <option value="">All Job Posts</option>
                <option
                  v-for="project in projects"
                  :key="project.id"
                  :value="project.category"
                >
                  {{ project.category }}
                </option>
              </select>
            </div>

            <!-- Filter by Status -->
            <div>
              <label
                for="status-filter"
                class="block text-sm font-medium text-gray-700 mb-1"
              >
                Filter by Status
              </label>
              <select
                v-model="selectProjectStatus"
                id="status-filter"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
              >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <!-- Search Projects -->
            <div class="md:col-span-2 lg:col-span-1">
              <label
                for="search-project"
                class="block text-sm font-medium text-gray-700 mb-1"
              >
                Search Projects
              </label>
              <input
                type="text"
                v-model="searchProjectTitle"
                placeholder="Search title"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
            </div>
          </div>
        </div>

        <!-- Project List -->
        <div
          v-if="projects && projects.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <div
            v-for="(project, index) in filteredProjects"
            :key="project.id"
            class="bg-white rounded-2xl shadow-lg p-7 border border-gray-100 flex flex-col relative group hover:shadow-xl transition duration-300"
          >
            <!-- Dropdown -->
            <div class="absolute top-4 right-4 dropdown-wrapper">
              <button
                @click.stop="toggleDropdown(index)"
                class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer p-2 rounded-full hover:bg-gray-50"
              >
                <i class="fas fa-ellipsis-v"></i>
              </button>

              <div
                v-show="activeDropdown === index"
                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden"
              >
                <a
                  href="#"
                  @click.prevent="openModalForEdit(project)"
                  class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <i class="fas fa-edit text-blue-500"></i> Edit
                </a>
                <a
                  href="#"
                  @click.prevent="deleteProject(project.id)"
                  class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                >
                  <i class="fa-solid fa-trash text-red-500"></i> Delete
                </a>
              </div>
            </div>

            <!-- User Info -->
            <div class="flex items-center mb-4">
              <img
                :src="project.user.profile.profile_picture
                  ? project.user.profile.profile_picture
                  : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
                class="h-14 w-14 rounded-full object-cover border border-gray-200"
                alt="profile photo"
              />
              <div class="ml-3">
                <p class="font-medium text-gray-800">
                  {{ formatNameFromEmail(project.user.username) }}
                </p>
                <p class="text-xs text-gray-500">
                  Posted {{ formatTimeAgo(project.created_at) }}
                </p>
              </div>
            </div>

            <!-- Title -->
            <h2 class="text-lg font-bold text-gray-800 mb-3 line-clamp-1">
              {{ project.title }}
            </h2>

            <!-- Description -->
            <p class="text-sm text-gray-600 mb-5 line-clamp-3">
              {{ project.description }}
            </p>

         

            <!-- See More Button -->
            <button
              @click="openDetailsModal(project)"
              class="mt-auto w-full cursor-pointer py-2 px-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition duration-200"
            >
              See More
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-else
          class="text-center bg-white rounded-xl shadow-lg p-12 mt-10 text-gray-500"
        >
          <i class="fas fa-box-open text-6xl mb-4 text-gray-300"></i>
          <p class="text-xl font-semibold mb-2">No projects posted yet!</p>
          <p class="text-gray-600">
            It looks like you haven't listed any projects. Start by clicking the
            <b>Post Project</b> button.
          </p>
        </div>
      </div>
    </div>

    <!-- CREATE / EDIT PROJECT MODAL -->
    <div
      v-if="showEditCreateModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4"
      @click.self="closeEditCreateModal"
    >
      <div
        class="relative bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto p-8 rounded-xl shadow-2xl"
      >
        <!-- Close Button -->
        <button
          @click="closeEditCreateModal"
          class="absolute top-3 right-3 text-gray-400 hover:text-red-600 text-3xl font-bold"
        >
          &times;
        </button>

        <h3 class="text-2xl text-center font-bold text-gray-800 mb-6">
          {{ form.id ? 'Update Project' : 'Post a New Project' }}
        </h3>

        <form @submit.prevent="submitForm" class="space-y-5">
          <input type="hidden" v-model="form.id" />

          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Project Title
            </label>
            <input
              type="text"
              v-model="form.title"
              placeholder="e.g: Build an E-commerce Site"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              placeholder="Project details..."
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              required
            ></textarea>
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Category
            </label>
            <select
              v-model="form.category"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="" selected>Select a category</option>
              <option value="Web Development">Web Development</option>
              <option value="Mobile Development">Mobile Development</option>
              <option value="Software Development">Software Development</option>
              <option value="Game Development">Game Development</option>
              <option value="Data Science">Data Science</option>
              <option value="AI / Machine Learning">AI / Machine Learning</option>
              <option value="Cybersecurity">Cybersecurity</option>
              <option value="DevOps">DevOps</option>
              <option value="Cloud Computing">Cloud Computing</option>
              <option value="Blockchain">Blockchain</option>
              <option value="UI/UX Design">UI/UX Design</option>
              <option value="Marketing">Marketing</option>
              <option value="Content Writing">Content Writing</option>
            </select>
          </div>

          <!-- Skills -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Skills
            </label>
            <input
              type="text"
              v-model="form.skills"
              placeholder="e.g: PHP, HTML, CSS, JavaScript"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Budget -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Budget ($)
            </label>
            <input
              type="number"
              v-model="form.budget"
              placeholder="Enter amount"
              class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Start Date
              </label>
              <input
                type="date"
                v-model="form.start_date"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Deadline
              </label>
              <input
                type="date"
                v-model="form.deadline"
                class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
          </div>

          <!-- Submit -->
          <div class="pt-4">
            <button
              type="submit"
              class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition"
            >
              {{ form.id ? 'Update Project' : 'Post Project' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- DETAILS MODAL -->
    <ProjectDetailsModal
      :show="showDetailsModal"
      :project="selectedProject"
      @close="closeDetailsModal"
    />
  </MainLayout>
</template>
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { formatTimeAgo, formatDate } from '@/utils/datetimeUtils';
import { formatSkills, formatNameFromEmail } from '@/utils/stringUtils';
import { formatCurrency } from '@/utils/numberUtils';
import ProjectDetailsModal from '@/components/modals/ShowProjectDetails.vue';

const showEditCreateModal = ref(false);
const showDetailsModal = ref(false);
const activeDropdown = ref(null);
const selectedProject = ref(null);
const searchProjectTitle = ref('');
const selectProjectStatus = ref('');
const selectProjectCategory = ref('');

const { projects } = usePage().props;

const filteredProjects = computed(() => {
  return projects.filter((project) => {
    const matchTitle = project.title.toLowerCase().includes(searchProjectTitle.value.toLowerCase());
    const matchStatus = selectProjectStatus.value === '' || project.project_status === selectProjectStatus.value;
    const matchCategory = selectProjectCategory.value === '' || project.category === selectProjectCategory.value;
    return matchTitle && matchStatus && matchCategory;
  });
});

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

const openModalForCreate = () => {
  form.reset();
  showEditCreateModal.value = true;
};

const openModalForEdit = (project) => {
  form.id = project.id;
  form.title = project.title;
  form.description = project.description;
  form.category = project.category;
  form.skills = project.skills;
  form.budget = project.budget;
  form.start_date = project.start_date
    ? new Date(project.start_date).toISOString().split('T')[0]
    : '';
  form.deadline = project.deadline
    ? new Date(project.deadline).toISOString().split('T')[0]
    : '';
  showEditCreateModal.value = true;
  activeDropdown.value = null;
};

const closeEditCreateModal = () => {
  showEditCreateModal.value = false;
  form.reset();
};

const openDetailsModal = (project) => {
  selectedProject.value = project;
  showDetailsModal.value = true;
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
  selectedProject.value = null;
};

const submitForm = () => {
  if (form.id) {
    form.put(route('employer.update', form.id), {
      onSuccess: () => {
        form.reset();
        showEditCreateModal.value = false;
        window.location.reload();
      },
    });
  } else {
    form.post(route('employer.store'), {
      onSuccess: () => {
        form.reset();
        showEditCreateModal.value = false;
        window.location.reload();
      },
    });
  }
};

const deleteProject = (projectId) => {
  if (confirm('Are you sure you want to delete this project?')) {
    form.delete(route('employer.destroy', projectId), {
      onSuccess: () => {
        activeDropdown.value = null;
        window.location.reload();
      },
    });
  }
};

const toggleDropdown = (index) => {
  activeDropdown.value = activeDropdown.value === index ? null : index;
};

const handleClickOutside = (event) => {
  if (activeDropdown.value !== null && !event.target.closest('.dropdown-wrapper')) {
    activeDropdown.value = null;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
