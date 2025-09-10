<template>
  <div class="min-h-screen flex flex-col">
    <div class="flex-grow grid grid-cols-1 lg:grid-cols-7 gap-4 lg:gap-8 p-4 sm:p-6 lg:p-0">

      <!-- Left Content -->
      <div class="flex flex-col lg:col-span-5">

        <!-- Back Button -->
        <div class="h-16 flex items-center ms-3 lg:ms-5 mb-4 lg:mb-0">
          <button @click="previewPage()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 cursor-pointer">
            <i class="fas fa-arrow-left mr-2"></i>
            <span class="font-medium">Back</span>
          </button>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
          {{ error }}
        </div>

        <!-- Project Content -->
        <div v-else-if="project" class="flex overflow-hidden justify-center py-4">
          <div class="bg-white rounded-xl shadow-lg p-3 sm:p-5 border border-gray-100 flex flex-col relative max-w-2xl custom-scrollbar transition-all duration-300 ease-in-out">

            <!-- User Info -->
            <div class="flex items-center mb-5 sm:mb-6">
              <img
                :src="project.user.profile.profile_picture ? project.user.profile.profile_picture : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
                alt="profile"
                class="h-13 w-13 rounded-full object-center object-cover mt-2"
              >
              <div class="ms-1 flex flex-col gap-0 sm:ms-2">
                <p class="text-sm font-semibold text-gray-800 name">{{ user.username }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Posted {{ formatTimeAgo(project.created_at) }}</p>
              </div>
            </div>

            <!-- Project Title -->
            <div class="flex flex-row items-center gap-2 mb-8">
              <i class="fa-1x fas fa-thumbtack text-red-500 mt-1"></i>
              <p class="text-xl font-extrabold text-blue-900 leading-tight title">
                {{ project.title }}
              </p>
            </div>

            <!-- Description -->
            <div class="mb-4">
              <div class="flex flex-row items-center gap-2 mb-2">
                <i class="fas fa-file-alt text-[#30d2e4d5]"></i>
                <label class="label text-xs">Description</label>
              </div>
              <p class="text-gray-600 text-sm mt-2">
                {{ project?.description || 'No description available.' }}
              </p>
            </div>

            <!-- Details -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800 text-sm mt-2 mb-4">

              <!-- Budget -->
              <div class="flex items-center gap-3 md:col-span-2">
                <i class="fa-2x fas fa-coins text-yellow-500 mt-1"></i>
                <div class="flex flex-col gap-0">
                  <strong class="block label text-xs">Budget</strong>
                  <span class="text-xs">{{ formatCurrency(project?.budget) }}</span>
                </div>
              </div>

              <!-- Start Date -->
              <div class="flex items-center gap-3">
                <i class="fa-2x far fa-calendar-alt text-green-500 mt-1"></i>
                <div class="flex flex-col gap-0">
                  <label class="block label text-xs">Start Date</label>
                  <span class="text-xs">{{ formatDate(project?.start_date) }}</span>
                </div>
              </div>

              <!-- Deadline -->
              <div class="flex items-center gap-3">
                <i class="fa-2x far fa-calendar-check text-red-500 mt-1"></i>
                <div class="flex flex-col gap-0">
                  <label class="block label text-xs">Deadline</label>
                  <span class="text-xs">{{ formatDate(project?.deadline) }}</span>
                </div>
              </div>

            </section>

            <!-- Tags -->
            <div class="flex flex-col gap-1 mb-2 mt-4 sm:mb-6">

              <!-- Label Row -->
              <div class="flex flex-row items-center gap-2 mb-1">
                <i class="fas fa-laptop-code text-green-500"></i>
                <label class="label text-xs">Requirements</label>
              </div>

              <!-- Categories and Skills -->
              <div class="flex flex-wrap gap-2 rounded">
                <span
                  v-for="category in formatSkills(project.category)"
                  :key="category"
                  class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs sm:text-sm font-medium rounded-full shadow-xs"
                >
                  {{ category }}
                </span>
                <span
                  v-for="skills in formatSkills(project.skills)"
                  :key="skills"
                  class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs sm:text-sm font-medium rounded-full shadow-xs"
                >
                  {{ skills }}
                </span>
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
      <div class="border border-gray-300 bg-white shadow-sm overflow-y-auto max-h-[calc(100vh-6rem)] lg:max-h-screen lg:col-span-2">
        <div class="p-4 border-b border-gray-200 ">
          <label class="text-lg font-semibold flex flex-row items-center gap-3 ">
            <i class="fas fa-user-tie text-gray-500 "></i>
            <span class="!text-blue-600 label">Application List</span>
          </label>
        </div>

        <ul v-if="userApplication && userApplication.length" class="divide-y divide-gray-200">
          <li
            v-for="(application, index) in userApplication"
            :key="application.id"
            class="p-4 hover:bg-gray-50 transition-colors duration-200 ease-in-out cursor-pointer"
          >
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
              <p v-if="application.created_at" class="text-xs text-gray-400 flex-shrink-0">
                Applied {{ formatTimeAgo(application.created_at) }}
              </p>
            </div>

            <p class="text-base font-bold text-gray-700 mb-3 flex items-center gap-2 truncate">
              <img
                :src="application.user.profile.profile_picture ? application.user.profile.profile_picture : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
                alt="profile"
                class="h-9 w-9 rounded-full object-center object-cover"
              >
              <div class="flex flex-col">
                <p class="name">{{ application.user.username || 'Untitled Application' }}</p>
                <div class="cursor-pointer font-normal text-xs flex items-center gap-1 text-gray-500">
                  Profile
                  <Link :href="route('userProfile.show', application.user.id)" class="text-blue-500 underline">visit here</Link>
                </div>
              </div>
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
              <div class="flex items-center text-gray-700 col-span-full md:col-span-1">
                <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <a :href="`/storage/${application.resume}`" target="_blank" class="text-blue-600">
                  View Resume
                </a>
              </div>

              <div class="flex items-center text-gray-700 col-span-full md:col-span-1">
                <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <a :href="application.link_portfolio" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium flex items-center transition-colors duration-200 min-w-0 flex-grow">
                  Visit Portfolio
                </a>
              </div>
            </div>

          </li>
          
        </ul>

        <div v-else class="p-6 text-center text-gray-500 text-base">
          <p>No applications submitted yet.</p>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { formatTimeAgo, formatDate } from '@/utils/datetimeUtils'
import { formatSkills } from '@/utils/stringUtils'
import { formatCurrency } from '@/utils/numberUtils'

const { project, error, userApplication, user } = usePage().props

function previewPage() {
  window.history.back()
}
</script>

<style scoped>
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
