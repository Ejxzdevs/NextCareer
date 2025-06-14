<template>
  <li class="relative">
    <button @click="toggleDropdown" class="hover:text-gray-300 cursor-pointer flex items-center gap-1">
      <i class="fas fa-user"></i>
    </button>
    <ul
      v-if="isDropdownOpen"
      class="absolute right-0 mt-2 w-40 bg-white text-black shadow-md rounded-md z-50"
    >
      <li>
        <Link href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</Link>
      </li>
      <li>
        <Link
          as="button"
          :href="route('logout')"
          method="post"
          class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer"
        >
          Sign out
        </Link>
      </li>
    </ul>
  </li>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

// --- Logic to close dropdown when clicking outside ---
const handleClickOutside = (event) => {
  // Check if the click target is outside of the component's root <li> element
  // This assumes the <li> is the top-level element of this component
  const componentElement = event.target.closest('li.relative');
  if (!componentElement || !componentElement.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// --- Close dropdown on Inertia page navigation ---
router.on('finish', () => {
  isDropdownOpen.value = false;
});
</script>
