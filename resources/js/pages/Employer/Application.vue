<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
      <!-- Page Header -->
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center">
        All Applicants
      </h1>

      <!-- Filters and Search Section -->
      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Filter by Job Post -->
          <div>
            <label for="job-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Job Post</label>
            <select id="job-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm">
              <option>All Job Posts</option>
              <option>Remote Senior Developer</option>
              <option>Remote UX Designer</option>
              <option>Remote Marketing Manager</option>
            </select>
          </div>

          <!-- Filter by Status -->
          <div>
            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="status-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm">
              <option>All Statuses</option>
              <option>New</option>
              <option>Reviewed</option>
              <option>Shortlisted</option>
              <option>Interview Scheduled</option>
              <option>Hired</option>
              <option>Rejected</option>
            </select>
          </div>

          <!-- Search by Name/Keyword -->
          <div class="md:col-span-2 lg:col-span-1">
            <label for="search-applicant" class="block text-sm font-medium text-gray-700 mb-1">Search Applicants</label>
            <input type="text" id="search-applicant" placeholder="Search by name or keyword..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
          </div>
        </div>
      </div>

      <!-- Applicants List -->
      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Applicant List</h2>

        <div class="space-y-6">
          <!-- Applicant Card 1 -->
          <div class="border-b border-gray-200 pb-6 last:border-b-0">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
              <div class="flex-grow">
                <h3 class="text-xl font-bold text-gray-900">John Doe</h3>
                <p class="text-md text-gray-700 mt-1">Applied for: <span class="font-semibold text-blue-600">Remote Senior Developer</span></p>
                <p class="text-sm text-gray-500 mt-1">Applied on: July 3, 2025</p>
                <p class="text-sm text-gray-600 mt-2">Skills: <span class="font-medium">JavaScript, React, Node.js, AWS</span></p>
              </div>
              <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="#" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md text-center">View Profile</a>
                <select class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                  <option>New</option>
                  <option selected>Reviewed</option>
                  <option>Shortlisted</option>
                  <option>Interview Scheduled</option>
                  <option>Hired</option>
                  <option>Rejected</option>
                </select>
                <button class="px-5 py-2 border border-gray-400 text-gray-700 text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-sm">Message</button>
              </div>
            </div>
          </div>

          <!-- Applicant Card 2 -->
          <div class="border-b border-gray-200 pb-6 last:border-b-0">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
              <div class="flex-grow">
                <h3 class="text-xl font-bold text-gray-900">Jane Smith</h3>
                <p class="text-md text-gray-700 mt-1">Applied for: <span class="font-semibold text-blue-600">Remote UX Designer</span></p>
                <p class="text-sm text-gray-500 mt-1">Applied on: July 2, 2025</p>
                <p class="text-sm text-gray-600 mt-2">Skills: <span class="font-medium">Figma, User Research, Prototyping</span></p>
              </div>
              <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="#" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md text-center">View Profile</a>
                <select class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                  <option>New</option>
                  <option>Reviewed</option>
                  <option selected>Shortlisted</option>
                  <option>Interview Scheduled</option>
                  <option>Hired</option>
                  <option>Rejected</option>
                </select>
                <button class="px-5 py-2 border border-gray-400 text-gray-700 text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-sm">Message</button>
              </div>
            </div>
          </div>

          <!-- Applicant Card 3 -->
          <div class="border-b border-gray-200 pb-6 last:border-b-0">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
              <div class="flex-grow">
                <h3 class="text-xl font-bold text-gray-900">Michael Brown</h3>
                <p class="text-md text-gray-700 mt-1">Applied for: <span class="font-semibold text-blue-600">Remote Marketing Manager</span></p>
                <p class="text-sm text-gray-500 mt-1">Applied on: July 1, 2025</p>
                <p class="text-sm text-gray-600 mt-2">Skills: <span class="font-medium">SEO, Content Marketing, Social Media</span></p>
              </div>
              <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="#" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md text-center">View Profile</a>
                <select class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                  <option>New</option>
                  <option>Reviewed</option>
                  <option>Shortlisted</option>
                  <option selected>Interview Scheduled</option>
                  <option>Hired</option>
                  <option>Rejected</option>
                </select>
                <button class="px-5 py-2 border border-gray-400 text-gray-700 text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-sm">Message</button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex justify-center mt-8">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              </a>
              <a href="#" aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600 hover:bg-blue-100">1</a>
              <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
              <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
              <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
              </a>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>

</script>

<style>
/* Ensure Tailwind CSS is properly imported/built into your project */
</style>