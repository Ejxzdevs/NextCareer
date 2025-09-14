<template>
  <MainLayout>
    <div class=" max-h-screen overflow-y-auto p-4 sm:p-6 lg:pb-32 lg:pt-8">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-8 text-center title">
        All Applicants
      </h1>

      <!-- Filters Section -->
      <div
        class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8 max-w-6xl mx-auto"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Filter by category -->
             <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <div class="relative">
                <select
                  v-model="selectedCategory"
                  class="w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white"
                >
                  <option value="" selected>All</option>
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
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <i class="fas fa-chevron-down text-gray-400"></i>
                </div>
              </div>
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
              v-model="selectedStatus"
              id="status-filter"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 
                     focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                     sm:text-sm rounded-md shadow-sm"
            >
              <option value="" selected>All Statuses</option>
              <option value="pending">Pending</option>
              <option value="viewed">Viewed</option>
              <option value="reviewed">Reviewed</option>
              <option value="scheduled">Scheduled</option>
              <option value="hired">Hired</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
  
          <!-- Search Applicants -->
          <div class="md:col-span-2 lg:col-span-1">
            <label
              for="search-applicant"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Search title
            </label>
            <input
              v-model="searchTitle"
              type="text"
              id="search-applicant"
              placeholder="Search by name or keyword..."
              class="mt-1 block w-full px-3 py-2 border border-gray-300 
                     rounded-md shadow-sm focus:outline-none focus:ring-blue-500 
                     focus:border-blue-500 sm:text-sm"
            />
          </div>
        </div>
      </div>

      <!-- Applicants List -->
      <div
        class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 max-w-6xl mx-auto"
      >
        <h2 class="label mb-6">
          Applicant List
        </h2>

        <!-- Applications -->
        <div
          v-if="applications.data && applications.data.length > 0"
          class="space-y-6"
        >
          <div
            v-for="application in filteredApplications"
            :key="application.id"
            class="border-b border-gray-200 pb-6 last:border-b-0"
          >
            <div
              class="flex flex-col sm:flex-row justify-between items-start sm:items-center"
            >
              <!-- Applicant Info -->
              <div
                class="flex items-start gap-4 flex-1 min-w-0 mb-4 sm:mb-0"
              >
                <div class="flex-shrink-0">
                  <img
                    :src="
                          application.project?.user?.profile?.profile_picture
                        ? application.project.user.profile.profile_picture
                        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                    "
                    class="h-16 w-16 rounded-full object-cover border-2 border-gray-300"
                    alt="profile photo"
                  />
                </div>
                <div class="flex-1 min-w-0">
                  <Link
                    :href="route('userProfile.show', application.project.user.id)"
                    class="block name font-semibold text-blue-700 hover:underline Poppins truncate"
                  >
                    {{ capitalizeFirstLetter(application.project.user.username) }}
                  </Link>
                  <p class="text-sm text-gray-700 mt-1">
                    You have applied for:
                    <span class="font-medium text-blue-900">
                      {{ application.project.title }}
                    </span>
                  </p>
                  <p class="text-xs text-gray-500">
                    Applied: {{ formatDate(application.created_at) }}
                  </p>
                   <p class="text-xs text-gray-500">
                    Status: <span class="label text-gray-800">{{ capitalizeFirstLetter(application.application_status) }}</span>
                  </p>
                  
                  <p
                    @click="openDetailsModal(application.project)"
                    class="text-blue-600 cursor-pointer hover:underline text-xs hover:text-blue-800 transition"
                    role="button"
                    tabindex="0"
                    @keyup.enter="openDetailsModal(application.project)"
                    >
                    View Project Details
                  </p>
                </div>
              </div>

              <!-- Actions -->
              <div
                class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center sm:gap-4 gap-2 w-full sm:w-auto"
              >
                <!-- Resume -->
                <a
                  :href="`/storage/${application.resume}`"
                  target="_blank"
                  class="inline-flex items-center text-sm font-medium text-blue-600 hover:underline 
                         px-3 py-2 border border-blue-200 rounded-md bg-blue-50 
                         hover:bg-blue-100 transition-colors"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 mr-1"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 
                         012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 
                         1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                  Resume
                </a>

                 <!-- Portfolio Button -->
                <a
                  v-if="application.link_portfolio"
                  :href="application.link_portfolio"
                  target="_blank"
                  class="inline-flex items-center text-sm font-medium text-white bg-green-600 
                         hover:bg-green-700 px-3 py-2 rounded-md shadow transition-colors"
                >
                  View Portfolio
                </a>

                 <!-- Project Details Modal -->
                <ProjectDetailsModal
                :show="showDetailsModal"
                :project="selectedProject"
                :hideBtnApply="hideBtn"
                @close="closeDetailsModal"
                />
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div
            class="flex justify-center mt-8"
            v-if="applications.links && applications.links.length > 0"
          >
            <nav
              class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
              aria-label="Pagination"
            >
              <template
                v-for="(link, key) in applications.links"
                :key="key"
              >
                <div
                  v-if="link.url === null"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 
                         bg-white text-sm font-medium text-gray-500 cursor-not-allowed"
                  v-html="link.label"
                ></div>
                <Link
                  v-else
                  :href="link.url"
                  v-html="link.label"
                  :class="{
                    'bg-blue-50 text-blue-600': link.active,
                    'bg-white text-gray-700 hover:bg-gray-50': !link.active
                  }"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                ></Link>
              </template>
            </nav>
          </div>
        </div>

        <!-- No Applicants -->
        <div v-else class="text-center text-gray-600 py-8">
          No applicants found.
        </div>
      </div>
    </div>
  </MainLayout>
</template>
<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { ref ,computed} from 'vue';
import ProjectDetailsModal from "@/components/modals/ShowProjectDetails.vue";
import { formatDate } from '@/utils/datetimeUtils';
import { capitalizeFirstLetter } from '@/utils/stringUtils';

const { applications } = usePage().props;
const selectedCategory = ref('')
const selectedStatus = ref('')
const searchTitle = ref('')
const selectedProject = ref(null);
const showDetailsModal = ref(false);
const hideBtn = ref(null)

const filteredApplications = computed(() => {
  return applications.data.filter((app) => {
      const matchTitle = app.project.title
      ?.toLowerCase()
      .includes(searchTitle.value.toLowerCase());
      const matchCategory = selectedCategory.value === '' 
      || app.project.category === selectedCategory.value
      const matchStatus = selectedStatus.value === '' 
      || app.application_status === selectedStatus.value
    return matchTitle && matchCategory && matchStatus
  })
})



function openDetailsModal(project) {
  selectedProject.value = project;
  hideBtn.value = true;
  showDetailsModal.value = true;
}

function closeDetailsModal() {
  selectedProject.value = null;
  showDetailsModal.value = false;
}
</script>
