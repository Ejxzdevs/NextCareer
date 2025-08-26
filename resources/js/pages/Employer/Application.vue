<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center">
        All Applicants
      </h1>

      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-if="projects && projects.length > 0">
            <label for="job-filter" class="block text-sm font-medium text-gray-700 mb-1">
              Filter by Job Post
            </label>
            <select
              v-model="selectedProjectTitle"
              id="job-filter"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
            >
              <option value="">All Job Posts</option>
              <option
                v-for="project in projects"
                :key="project.id"
                :value="project.title"
              >
                {{ project.title }}
              </option>
            </select>
          </div>

          <div>
            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">
              Filter by Status
            </label>
            <select
              v-model="selectedStatus"
              id="status-filter"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
            >
              <option value="">All Statuses</option>
              <option value="viewed">Viewed</option>
            </select>
          </div>

          <div class="md:col-span-2 lg:col-span-1">
            <label for="search-applicant" class="block text-sm font-medium text-gray-700 mb-1">
              Search Applicants
            </label>
            <input
              v-model="searchName"
              type="text"
              id="search-applicant"
              placeholder="Search by name or keyword..."
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Applicant List</h2>

        <div v-if="applications.data && applications.data.length > 0" class="space-y-6">
          <div
            v-for="application in filteredApplications"
            :key="application.id"
            class="border-b border-gray-200 pb-6 last:border-b-0"
          >
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
              <div class="flex-grow">
                <h3 class="text-xl font-bold text-gray-900">{{ application.user.email }}</h3>
                <p class="text-md text-gray-700 mt-1">
                  Applied for:
                  <span class="font-semibold text-blue-600">{{ application.project.title }}</span>
                </p>
                <p class="text-sm text-gray-500 mt-1">Applied on: {{ formatDate(application.created_at) }}</p>
                <a :href="`/storage/${application.resume}`" target="_blank" class="text-blue-600">
                  View Resume
                </a>
              </div>
              <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center sm:gap-4 gap-2 w-full sm:w-auto">
                <a
                  href="#"
                  class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md text-center"
                >
                  View Profile
                </a>
                <select
                  class="text-center px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option>New</option>
                  <option :selected="application.application_status === 'Reviewed'">Reviewed</option>
                  <option :selected="application.application_status === 'Shortlisted'">Shortlisted</option>
                  <option :selected="application.application_status === 'Interview Scheduled'">Interview Scheduled</option>
                  <option :selected="application.application_status === 'Hired'">Hired</option>
                  <option :selected="application.application_status === 'Rejected'">Rejected</option>
                  <option :selected="application.application_status === 'viewed'">Viewed</option>
                </select>
                <MessageModal 
                  :email="application.user.email" 
                  :id="application.user.id" 
                />
              </div>
            </div>
          </div>

          <div class="flex justify-center mt-8" v-if="applications.links && applications.links.length > 0">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <template v-for="(link, key) in applications.links" :key="key">
                <div
                  v-if="link.url === null"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-not-allowed"
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
        <div v-else class="text-center text-gray-600 py-8">
          No applicants found.
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { formatDate } from '../../utils/datetimeUtils';
import MessageModal from '@/components/shared/MessageModal.vue'


const { applications, projects } = usePage().props;

const searchName = ref('');
const selectedProjectTitle = ref('');
const selectedStatus = ref('');

const filteredApplications = computed(() => {
  return applications.data.filter(app => {
    const matchesName = app.user?.email?.toLowerCase().includes(searchName.value.toLowerCase());
    const matchesProject =
      selectedProjectTitle.value === '' || app.project?.title === selectedProjectTitle.value;
    const matchesStatus =
      selectedStatus.value === '' || app.application_status === selectedStatus.value;
    return matchesName && matchesProject && matchesStatus;
  });
});

</script>
