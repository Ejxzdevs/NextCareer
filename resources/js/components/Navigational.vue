<template>
  <div @click.self="isDropdownOpen = false">
    <nav class="bg-[#334EAC] p-4">
      <!-- Desktop Menu -->
      <div class="flex justify-between items-center">
        <div class="hidden md:flex text-white text-lg font-semibold">Next Career</div>
        <ul class="hidden md:flex space-x-6 text-white items-center">
          <li><a href="/jobs" class="hover:text-gray-300">
            <i class="fas fa-bell"></i>
          </a></li>
          <li><a href="/companies" class="hover:text-gray-300">
            <i class="fas fa-envelope"></i>
          </a></li>
          <li class="relative">
            <button @click="toggleDropdown" class="hover:text-gray-300 cursor-pointer flex items-center gap-1">
              <i class="fas fa-user"></i>
            </button>
            <ul
              v-if="isDropdownOpen"
              class="absolute right-0 mt-2 w-40 bg-white text-black shadow-md rounded-md z-50"
            >
              <li>
                <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
              </li>
              <li>
                <button @click="logout" class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer">Sign out</button>
              </li>
            </ul>
          </li>
          <li class="cursor-pointer" >|</li>
          <li><a :href="route('employer.dashboard')" class="hover:text-gray-300">Home</a></li>
          <li class="cursor-pointer" >|</li>
          <li v-if="userRole === 'freelance'" ><a class="hover:text-gray-300 mr-[10px]">Browse Project</a></li>
          <li v-if="userRole === 'employer'"><a :href="route('employer.project')" class="hover:text-gray-300 mr-[10px]">Hire a Freelancer</a></li>
        </ul>
      </div>

      <!-- Mobile Menu -->
      <div class="md:hidden flex flex-row justify-between">
        <div class="text-white text-lg font-semibold">Next Career</div>
        <div class="flex flex-row">
        <ul class="flex flex-row gap-4 me-3 text-white items-center">
          <li><a href="/jobs" class="hover:text-gray-300">
            <i class="fas fa-bell"></i>
          </a></li>
          <li><a href="/companies" class="hover:text-gray-300">
            <i class="fas fa-envelope"></i>
          </a></li>
          <li class="relative">
            <button @click="toggleDropdown" class="hover:text-gray-300 cursor-pointer flex items-center gap-1">
              <i class="fas fa-user"></i>
            </button>
            <ul
              v-if="isDropdownOpen"
              class="absolute right-0 mt-2 w-40 bg-white text-black shadow-md rounded-md z-50"
            >
              <li>
                <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
              </li>
              <li>
                <button @click="logout" class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer">Sign out</button>
              </li>
            </ul>
          </li>
        </ul>
        
        <button @click="toggleMenu" class="text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        </div>
      </div>

      <!-- Mobile Dropdown -->
      <ul v-if="isMenuOpen" class="md:hidden text-white bg-[#7789C7] p-4 space-y-2">
        <li><a :href="route('employer.dashboard')" class="hover:text-gray-300">Home</a></li>
        <li v-if="userRole === 'freelance'" ><a class="hover:text-gray-300">Browse Project</a></li>
        <li v-if="userRole === 'employer'"><a :href="route('employer.project')" class="hover:text-gray-300">Hire a Freelancer</a></li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'

const page = usePage()
// Reactive variables
const isMenuOpen = ref(false)
const isDropdownOpen = ref(false)
const userRole = ref('guest')

const checkRole = page.props.user?.role
if (checkRole === 'freelance') {
  userRole.value = 'freelance'
} else if (checkRole === 'employer') {
  userRole.value = 'employer'
} else {
  userRole.value = 'guest'
}


const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const logout = () => {
  Inertia.post(route('logout'))
}
</script>
