<template>
  <li class="relative">
    <button @click="toggleDropdown" class="relative hover:text-gray-300 cursor-pointer flex items-center gap-1 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
      <i class="fas fa-bell text-xl"></i>
      <span v-if="unreadNotificationsCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
        {{ unreadNotificationsCount }}
      </span>
    </button>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="isDropdownOpen"
        class="absolute right-0 mt-3 w-80 bg-white text-gray-800 shadow-xl rounded-lg border border-gray-200 z-50 overflow-hidden"
        @click.stop
      >
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
          <h3 class="text-lg font-semibold">Notifications</h3>
          <button
            v-if="notifications.length > 0 && unreadNotificationsCount > 0"
            @click="markAllAsRead"
            class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none"
          >
            Mark all as read
          </button>
        </div>

        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100">
          <li v-if="notifications.length === 0" class="p-4 text-center text-gray-500">
            No new notifications.
          </li>
          <li
            v-for="notification in notifications"
            :key="notification.id"
            :class="['p-4 flex items-start gap-3', { 'bg-blue-50 hover:bg-blue-100': !notification.read, 'hover:bg-gray-50': notification.read }]"
            @click="viewNotification(notification)"
          >
            <div class="flex-shrink-0">
              <i :class="notification.icon || 'fas fa-info-circle'" class="text-blue-500 text-lg"></i>
            </div>
            <div class="flex-grow">
              <p :class="['text-sm', { 'font-semibold': !notification.read }]">{{ notification.message }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ notification.time }}</p>
            </div>
            <div v-if="!notification.read" class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
          </li>
        </ul>

        <div v-if="notifications.length > 0" class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50">
          <a href="/notifications" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
            View All Notifications
          </a>
        </div>
      </div>
    </transition>
  </li>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

// UI State
const isDropdownOpen = ref(false);
const isLoading = ref(false);
const error = ref(null);

// Raw API response
const rawProjectData = ref([]);

// Fetch notifications from API
const fetchApiData = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    // Use Ziggy's route helper if available
    const response = await fetch(route('api.raw.notifications'));

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`HTTP error! Status: ${response.status} - ${errorText}`);
    }

    const data = await response.json();
    rawProjectData.value = data.projectsWithApplications || [];

    console.log('Fetched Notifications:', rawProjectData.value);
  } catch (err) {
    console.error('Fetch error:', err);
    error.value = 'Failed to load notifications.';
  } finally {
    isLoading.value = false;
  }
};

// Computed notifications from API data
const notifications = computed(() =>
  rawProjectData.value.map(project => ({
    id: project.project_id || project.id,
    message: `New update on your project: "${project.title}"`,
    time: new Date(project.project_updated_at || project.updated_at).toLocaleString(),
    read: false, // Default unread
    icon: 'fas fa-briefcase'
  }))
);

// Count of unread notifications
const unreadNotificationsCount = computed(() =>
  notifications.value.filter(n => !n.read).length
);

// Toggle dropdown visibility
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

// Mark all as read
const markAllAsRead = () => {
  notifications.value.forEach(notification => {
    notification.read = true;
  });
};

// View individual notification
const viewNotification = (notification) => {
  notification.read = true;
  console.log(`Viewing notification: ${notification.message}`);
  isDropdownOpen.value = false;
};

// Close dropdown on outside click
const handleClickOutside = (event) => {
  const componentElement = event.target.closest('li.relative');
  if (!componentElement || !componentElement.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchApiData(); // Start fetching on mount
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
