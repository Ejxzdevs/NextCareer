<template>
  <li class="relative">
    <button @click="toggleDropdown" ref="dropdownRef" class="hover:text-gray-300 cursor-pointer flex items-center gap-1">
      <i class="fas fa-user"></i>
    </button>
    <ul
      v-if="isDropdownOpen"
      class="absolute right-0 mt-2 w-40 bg-white text-black shadow-md rounded-md z-50"
    >
      <li>
        <Link :href="route('employer.profile')" class="block px-4 py-2 hover:bg-gray-100">Profile</Link>
      </li>
      <li>
        <Link
          as="button"
          :href="route('logout')"
          method="post"
          class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer"
          @click="closeDropdown"
        >
          Sign out
        </Link>
      </li>
    </ul>
  </li>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useDropdown } from '../../composables/useToggleVisibility'; // Adjust path as needed

const { isDropdownOpen, toggleDropdown, closeDropdown, dropdownRef } = useDropdown();

router.on('finish', () => {
  closeDropdown();
});
</script>
