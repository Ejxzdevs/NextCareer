<template>
  <div class="min-h-screen flex flex-col">
    <div class="flex-grow grid grid-cols-1 lg:grid-cols-7 gap-4 lg:gap-8 p-4 sm:p-6 lg:p-0">
      
      <!-- Left Content -->
      <div class="flex flex-col lg:col-span-5">
        
        <!-- Back Button -->
        <div class="h-16 flex items-center ms-3 lg:ms-5 mb-4 lg:mb-0">
          <a :href="route('employer.project')" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            <span class="font-medium">Back</span>
          </a>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
          {{ error }}
        </div>

        <!-- Project Content -->
        <div v-else-if="project" class="flex flex-grow overflow-hidden justify-center py-4 sm:py-6 lg:py-8">
          <div class="bg-white rounded-xl shadow-lg p-5 sm:p-7 border border-gray-100 flex flex-col relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl mx-auto overflow-y-auto custom-scrollbar transition-all duration-300 ease-in-out">
            
            <!-- User Info -->
            <div class="flex items-center mb-5 sm:mb-6">
              <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl sm:text-2xl flex-shrink-0 shadow-sm">
                <i class="fas fa-user-circle"></i>
              </div>
              <div class="ms-3 sm:ms-4">
                <p class="text-md sm:text-lg font-semibold text-gray-800">{{ project.user?.name || 'Unknown User' }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Posted {{ formatTimeAgo(project.created_at) }}</p>
              </div>
            </div>

            <!-- Project Title -->
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">
              {{ project.title }}
            </h2>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mb-5 sm:mb-6">
              <span
                v-if="project.category"
                class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs sm:text-sm font-medium rounded-full shadow-xs"
              >
                {{ project.category.replace(/-/g, ' ').replace(/\b\w/g, (s) => s.toUpperCase()) }}
              </span>
              <span
                v-if="project.skills"
                class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs sm:text-sm font-medium rounded-full shadow-xs"
              >
                {{ formatSkills(project.skills) }}
              </span>
            </div>

            <!-- Description -->
            <p class="text-sm sm:text-base text-gray-600 mb-6 leading-relaxed flex-grow">
              {{ project.description }}
            </p>

            <!-- Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 text-sm sm:text-base text-gray-700 gap-4 mt-6 pt-5 border-t border-gray-100">
              <div class="flex flex-col">
                <strong class="text-gray-500 font-medium mb-1">Budget:</strong>
                <span class="font-bold text-gray-900 text-base sm:text-lg">{{ formatCurrency(project.budget) }}</span>
              </div>
              <div class="flex flex-col">
                <strong class="text-gray-500 font-medium mb-1">Start Date:</strong>
                <span class="font-bold text-gray-900 text-base sm:text-lg">{{ formatDate(project.start_date) }}</span>
              </div>
              <div class="col-span-full flex flex-col">
                <strong class="text-gray-500 font-medium mb-1">Deadline:</strong>
                <span class="font-bold text-gray-900 text-base sm:text-lg">{{ formatDate(project.deadline) }}</span>
              </div>
            </div>

          </div>
        </div>

        <!-- Loading State -->
        <div v-else class="flex justify-center items-center flex-grow">
          <div class="text-gray-500 text-lg sm:text-xl">Loading project...</div>
        </div>
      </div>

      <!-- Right Sidebar (Applications) -->
        <div class="border border-gray-300 bg-white shadow-sm overflow-y-auto max-h-[calc(100vh-6rem)] lg:max-h-full lg:col-span-2">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">Your Applications</h3>
    </div>

    <ul v-if="userApplication && userApplication.length" class="divide-y divide-gray-200">
        <li
            v-for="(application, index) in userApplication"
            :key="application.id"
            class="p-4 hover:bg-gray-50 transition-colors duration-200 ease-in-out cursor-pointer"
        >
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                <p class="text-sm font-medium text-gray-500 truncate mb-1 sm:mb-0">
                    Application ID: <span class="text-gray-900 font-semibold">{{ application.id || 'N/A' }}</span>
                </p>
                <p v-if="application.created_at" class="text-xs text-gray-400 flex-shrink-0">Applied {{ formatTimeAgo(application.created_at) }}</p>
            </div>

            <p class="text-base font-bold text-gray-800 mb-3 truncate">
                {{ application.job_title || 'Untitled Application' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center text-gray-700 col-span-full md:col-span-1">
                    <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.928-5.5L21 8m-2 4v4m-6 4H7a2 2 0 01-2-2v-4m6 4V8a2 2 0 00-2-2H5a2 2 0 00-2 2v4m14-4V8a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path></svg>
                    <span class="font-medium flex-shrink-0">Email:</span>
                    <span class="ml-1 truncate">{{ application.user?.email || 'N/A' }}</span>
                </div>

                <div class="flex items-center text-gray-700 col-span-full md:col-span-1">
                    <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <a :href="`/storage/${application.resume}`" target="_blank" class="text-blue-600">
    View Resume
</a>
                </div>

                <div class="flex items-center text-gray-700 col-span-full md:col-span-1">
                    <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <a :href="application.link_portfolio" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium flex items-center transition-colors duration-200 min-w-0 flex-grow">
                        Visit Portfolio
                    </a>
                </div>
            </div>
        </li>
    </ul>

    <div v-else class="p-6 text-center text-gray-500 text-base">
        <p>No applications submitted yet.</p>
        <p class="mt-2 text-sm">Start by applying to a project that fits your skills!</p>
    </div>
</div>

      
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { formatTimeAgo, formatDate } from '../../utils/datetimeUtils'
import { formatSkills } from '../../utils/stringUtils'
import { formatCurrency } from '../../utils/numberUtils'

const { project, error, userApplication } = usePage().props

onMounted(() => {
  
  console.log('User Application:', userApplication)
})
</script>

<style scoped>
/* Custom scrollbar for better aesthetics */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
