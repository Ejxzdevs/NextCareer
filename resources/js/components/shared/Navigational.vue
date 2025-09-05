<template>
  <div @click.self="isMenuOpen = false"> <nav class="bg-[#334EAC] p-4">
      <div class="flex justify-between items-center">
        <div class="hidden md:flex text-white text-lg font-semibold">
          <Link :href="route('landing.page')">Next Career</Link>
        </div>
        <ul class="hidden md:flex item-center space-x-3 text-white items-center font-medium text-sm">
          <div class="flex flex-row gap-2">
          <NotificationDropdown/>
          <MessageDropdown/>
          <UserDropdown />
          </div>
          <li>
            <Link 
              v-if="userRole === 'employer'" :href="route('employer.dashboard')" 
              class="hover:text-gray-300 nav-link">
                Dashboard
            </Link>
          </li>
          <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300 nav-link">
                {{ userRole === 'freelance' ? 'Find Jobs' : 'Job Posts' }}
            </Link>
          </li>
           <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.application')" 
              class="hover:text-gray-300 nav-link">
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
          <ul class="flex flex-row gap-1 me-3 text-white items-center">
            <NotificationDropdown/>
            <MessageDropdown/>
            <UserDropdown />
          </ul>
          <button @click="toggleMenu" class="text-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>

      <ul v-if="isMenuOpen" class="md:hidden text-white bg-[#5b72c6] p-4 space-y-2">
        <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="hover:text-gray-300 nav-link">
                Dashboard
            </Link>
        </li>
        <li>
            <Link 
              :href="userRole === 'freelance' ? route('freelance.browse') : route('employer.project')" 
              class="nav-link">
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
import NotificationDropdown from '@/Components/Shared/NotifDropdown.vue';
import MessageDropdown from '@/Components/Shared/MessageDropdown.vue';

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
