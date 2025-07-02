<template>
  <div @click.self="isMenuOpen = false"> <nav class="bg-[#334EAC] p-4">
      <div class="flex justify-between items-center">
        <div class="hidden md:flex text-white text-lg font-semibold">
          <Link :href="route('landing.page')">Next Career</Link>
        </div>
        <ul class="hidden md:flex space-x-6 text-white items-center">
          <Notification />
          <li>
            <Link href="/companies" class="hover:text-gray-300">
              <i class="fas fa-envelope"></i>
            </Link>
          </li>
          <UserDropdown />
          <li class="cursor-pointer">|</li>
          <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300">
                Home
            </Link>
          </li>
          <li class="cursor-pointer">|</li>
           <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300">
                Applications
            </Link>
          </li>
        </ul>
      </div>

      <div class="md:hidden flex flex-row justify-between">
        <div class="text-white text-lg font-semibold">
          <Link :href="route('landing.page')">Next Career</Link>
        </div>
        <div class="flex flex-row">
          <ul class="flex flex-row gap-4 me-3 text-white items-center">
            <Notification />
            <li>
              <Link href="/companies" class="hover:text-gray-300">
                <i class="fas fa-envelope"></i>
              </Link>
            </li>
            <UserDropdown />
            </ul>

          <button @click="toggleMenu" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>

      <ul v-if="isMenuOpen" class="md:hidden text-white bg-[#7789C7] p-4 space-y-2">
        <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300">
                Home
            </Link>
        </li>
        <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300">
                Applications
            </Link>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import UserDropdown from '@/Components/Shared/UserDropdown.vue';
import Notification from '@/Components/Shared/NotifDropdown.vue';

const page = usePage();

const isMenuOpen = ref(false);
const userRole = ref('guest');

// Function to update userRole based on page props
const updateUserRole = () => {
  const checkRole = page.props.user?.role;
  if (checkRole === 'freelance') {
    userRole.value = 'freelance';
  } else if (checkRole === 'employer') {
    userRole.value = 'employer';
  } else {
    userRole.value = 'guest';
  }
};

updateUserRole();

watch(() => page.props.user, (newVal) => {
  updateUserRole();
}, { immediate: true });

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>
