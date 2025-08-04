<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center">
        Welcome, <span class="text-blue-700">TEAM CONSTRUCTION</span>!
      </h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center justify-between transition-transform transform hover:scale-105 duration-300">
          <div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Active Job project</h2>
            <p class="text-5xl font-bold text-blue-600"> {{ projects?.length ?? 0 }}</p></div>
          <div class="flex-shrink-0">
            <svg class="h-16 w-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center justify-between transition-transform transform hover:scale-105 duration-300">
          <div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">New Applicants This Week</h2>
            <p class="text-5xl font-bold text-green-600">{{ totalWeeklyApplications ?? 0 }}</p> </div>
          <div class="flex-shrink-0">
            <svg class="h-16 w-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm-6-4a4 4 0 00-4-4H5a4 4 0 00-4 4v1h10v-1zm14 0a4 4 0 00-4-4h-3.465m4.935 9.001H21m-6.856-9.001h.01"></path></svg>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex flex-col justify-center items-center text-center">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">What's Next?</h2>
          <p class="text-gray-600 mb-6">Quickly jump to important tasks or create new opportunities.</p>
          <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
            <Link
              :href="route('employer.project')"
              class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105"
            >
              + Post a New Remote Job
            </Link>
            <Link
              :href="route('employer.application')"
              class="flex-1 px-6 py-3 border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-50 transition duration-300 ease-in-out flex items-center justify-center transform hover:scale-105"
            >
              View All Applicants
          </Link>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Recent Job Posts</h2>

          <div v-if="projects.length > 0" class="space-y-4">
            <div v-for="project in projects" :key="project.id" class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 rounded-md transition-colors">
              <div>
                <p class="text-lg font-medium text-gray-900">{{ project.title}}</p>
                <p class="text-sm text-gray-600">Status: <span class="font-semibold text-green-700">Active</span></p>
                <p class="text-xs text-gray-500 mt-1">projected: {{ formatDate(project.created_at) }}</p>
              </div>
              <div class="mt-2 sm:mt-0 flex space-x-2">
                <a @click="viewNotification(project.id)" class="text-blue-500 hover:underline text-sm cursor-pointer">View Applicants({{ project.applications.length }})</a>
              </div>
            </div>
          </div>
          <div v-else>
            <p>No post projects yet.</p>
          </div>

        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8 max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Applicants</h2>

        <div v-if="applications.length > 0" class="space-y-4">
          <div v-for="application in getFiveApplications" class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 rounded-md transition-colors">
            <div>
              <p class="text-lg font-medium text-gray-900">{{ application.user.email }}</p>
              <p class="text-sm text-gray-600">Applied for: <span class="font-medium">{{ application.project.title}}</span></p>
              <p class="text-xs text-gray-500 mt-1">Applied: {{ formatDate(application.created_at)}}</p>
            </div>
            <a href="#" class="mt-2 sm:mt-0 px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors shadow-sm">
              View Profile
            </a>
          </div>
        </div>
        <div v-else>
            <p>No post applications yet.</p>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import { formatDate } from '../../utils/datetimeUtils';
import { Inertia } from '@inertiajs/inertia';

const { projects , applications , totalWeeklyApplications } = usePage().props
const getFiveApplications = computed(() => applications.slice(0, 5));

/**
 * Handle notification click to view project details
 * @param {number} id - Project ID
 * @param {number} application_id - Application ID
 */
function viewNotification(id ) {
    if (!id) {
        alert('Invalid project or application ID.');
        return;
    }
    Inertia.get(
        route('project.show', { id: id }),
        {},
        {
            onError: (errors) => {
                alert('Could not load project. It may not exist or you do not have permission.');
            }
        }
    );

    closeDropdown();
}

onMounted(() => {
  console.log(JSON.stringify(projects[2]));
})

</script>