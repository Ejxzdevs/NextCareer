<template>
  <li class="relative" ref="dropdownRef">
    <button
      @click="toggleDropdown"
      class="relative hover:text-gray-300 cursor-pointer flex items-center gap-1 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
      <i class="fas fa-bell text-xl"></i>
      <span
        v-if="pendingNotificationsCount > 0"
        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"
      >
        {{ pendingNotificationsCount }}
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
        <div
          class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50"
        >
          <h3 class="text-lg font-semibold">Notifications</h3>
          <button
            @click="callMarkAllAsRead"
            class="cursor-pointer text-sm text-blue-600 hover:text-blue-800 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="isLoading || pendingNotificationsCount === 0"
          >
            Mark all as read
          </button>
        </div>

        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100 list-none p-0 m-0">
          <li v-if="isLoading" class="p-4 text-center text-gray-500">
            Loading notifications...
          </li>
          <li
            v-else-if="fetchError"
            class="p-4 text-center text-red-600 font-bold"
          >
            Error: {{ fetchError }}
          </li>
          <li
            v-else-if="notifications.length === 0"
            class="p-4 text-center text-gray-500"
          >
            No new notifications.
          </li>
          <li
            v-else
            v-for="notification in notifications"
            :key="notification.id"
            @click="viewNotification(notification.id)"
            :class="[
              'p-2.5 mb-1 border border-gray-200 rounded flex items-center gap-2.5 cursor-pointer',
              notification.read ? 'hover:bg-gray-50' : 'bg-blue-100 font-semibold'
            ]"
          >
            <div class="flex-shrink-0">
              <i
                :class="notification.icon || 'fas fa-info-circle'"
                class="text-blue-500 text-lg"
              ></i>
            </div>
            <div class="flex-grow">
              <p :class="['text-sm', { 'font-semibold': !notification.read }]">
                {{ notification.message }}
              </p>
              <p class="text-xs text-gray-600 ml-auto">{{ notification.time }}</p>
            </div>
            <div
              v-if="!notification.read"
              class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"
            ></div>
          </li>
        </ul>

        <div
          v-if="notifications.length > 0 && !isLoading && !fetchError"
          class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50"
        >
          <a
            href="/notifications"
            class="text-sm text-blue-600 hover:text-blue-800 font-medium"
          >
            View All Notifications
          </a>
        </div>
      </div>
    </transition>
  </li>
</template>
<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useDropdown } from '../../composables/useToggleVisibility';
import { fetchApiData, markAllAsRead } from '../../services/NotificationsService';
import { Inertia } from '@inertiajs/inertia';

/**
 * Initialize dropdown state and utility functions from custom composable
 */
const { isDropdownOpen, toggleDropdown, closeDropdown, dropdownRef } = useDropdown();

/**
 * Reactive references to hold notifications data, loading state, and errors
 */
const rawProjectData = ref([]);
const isLoading = ref(false);
const fetchError = ref(null);
const project_id = ref(null); // Not used in the provided snippet, kept as null

/**
 * Fetch notifications data from API and update reactive state accordingly
 */
const loadNotifications = async () => {
    isLoading.value = true;
    fetchError.value = null; // Clear any previous error
    rawProjectData.value = []; // Clear data before new fetch

    try {
        // fetchApiData MUST be implemented to return fully resolved 'data' or 'error'
        const { data, error } = await fetchApiData(); 

        if (error) {
            fetchError.value = error;
            // rawProjectData remains empty, as set above
        } else {
            rawProjectData.value = data; // Assign the actual, resolved data
        }
    } catch (e) {
        // Catch any unexpected errors that might occur during the fetch process itself
        console.error("Error in loadNotifications:", e);
        fetchError.value = new Error("An unexpected error occurred while fetching notifications.");
    } finally {
        isLoading.value = false;
    }
};

/**
 * Mark all notifications as read via API, then refresh notifications list
 */
const callMarkAllAsRead = async () => { 
    fetchError.value = null; // Clear any previous error

    try {
        // markAllAsRead MUST be implemented to return resolved 'success' or 'error'
        const { success, error } = await markAllAsRead(); 

        if (error) {
            fetchError.value = error;
        }

        if (success) {
            await loadNotifications(); // Reload notifications to reflect the changes
        }
    } catch (e) {
        // Catch any unexpected errors that might occur during the mark as read process
        console.error("Error in callMarkAllAsRead:", e);
        fetchError.value = new Error("An unexpected error occurred while marking notifications as read.");
    }
};

/**
 * Compute notification objects for UI from raw project/application data
 */
const notifications = computed(() => {
    // Add a safety check: Ensure rawProjectData.value is an array before mapping
    if (!Array.isArray(rawProjectData.value)) {
        // Log a warning if it's not an array, helpful for debugging
        console.warn("rawProjectData is not an array:", rawProjectData.value);
        return []; // Return an empty array to prevent errors in the template
    }
    return rawProjectData.value.map(project => ({
        id: project.id,
        message: `${project.applicant_email} applied to your ${project.title}`,
        time: new Date(project.project_updated_at || project.updated_at).toLocaleString(),
        read: project.application_status !== 'pending',
        icon: 'fas fa-briefcase'
    }));
});


/**
 * Handle notification click to view details, passing project ID
 * This function can be expanded to navigate to a specific page or perform other actions
 */
function viewNotification(id) {
    if (!id) {
        alert('Invalid project ID.');
        return;
    }
    // Assumes 'route' function is globally available (e.g., from Ziggy for Laravel routes)
    Inertia.get(route('project.show', id), {}, {
        onError: (errors) => {
            console.error("Inertia navigation error:", errors);
            alert('Could not load project. It may not exist or you do not have permission.');
        }
    });
    closeDropdown();
}


/**
 * Compute count of unread (pending) notifications for badge display
 */
const pendingNotificationsCount = computed(() =>
    notifications.value.filter(notification => !notification.read).length
);

// Initial data load when the component is mounted
onMounted(() => {
    loadNotifications();
});

// Optional: You could add a watcher if you want to reload notifications
// every time the dropdown is opened, for example, but `onMounted` is usually enough.
// watch(isDropdownOpen, (isOpen) => {
//   if (isOpen) {
//     loadNotifications(); // Reload notifications when dropdown opens
//   }
// });

</script>

