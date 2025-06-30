<template>
  <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
    
    <!-- Back Button -->
    <div class="h-16 flex items-center mb-6">
      <a :href="route('employer.project')" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>
        <span class="font-medium">Back</span>
      </a>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Notifications</h1>

    <!-- Loading -->
    <div v-if="isLoading" class="text-gray-500 text-lg">
      Loading notifications...
    </div>

    <!-- Error -->
    <div v-if="fetchError" class="bg-red-100 text-red-700 p-4 rounded mb-4">
      {{ fetchError }}
    </div>

    <!-- Notifications List -->
    <ul v-if="!isLoading && notifications.length" class="bg-white divide-y divide-gray-200">
      <li
        v-for="notification in notifications"
        :key="notification.id"
        @click="viewNotification(notification.id)"
        class="p-4 flex items-start gap-4 hover:bg-gray-50 transition-colors rounded cursor-pointer"
      >
        <div class="text-blue-600 text-xl">
          <i :class="notification.icon"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm sm:text-base" :class="{'font-semibold': !notification.read}">
            {{ notification.message }}
          </p>
          <p class="text-xs text-gray-500 mt-1">
            {{ notification.time }}
          </p>
        </div>
          <div v-if="!notification.read" class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
      </li>
      
    </ul>

    <!-- No Notifications -->
    <div v-else-if="!isLoading && !notifications.length" class="text-gray-500 text-base mt-6">
      No notifications found.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted ,computed} from 'vue';
import { fetchUserNotifications } from '../../services/NotificationsService';
import { Inertia } from '@inertiajs/inertia';

const rawProjectData = ref([]);
const isLoading = ref(false);
const fetchError = ref(null);
const project_id = ref(null);


const loadNotifications = async () => {
    isLoading.value = true;
    fetchError.value = null;

    const { data, error } = await fetchUserNotifications();

    if (error) {
        fetchError.value = error;
        rawProjectData.value = [];
    } else {
        rawProjectData.value = data;
    }

    isLoading.value = false;
};

/**
 * View notification details by navigating to the project page
 * @param {number} id - The project ID to view
 */

function viewNotification(id) {
    console.log('Viewing notification for project ID:', id);
    if (!id) {
        alert('Invalid project ID.');
        return;
    }
    Inertia.get(route('project.show', id), {}, {
        onError: (errors) => {
            alert('Could not load project. It may not exist or you do not have permission.');
        }
    });
}



/**
 * Compute notification objects for UI from raw project/application data
 */
const notifications = computed(() =>
    rawProjectData.value.map(project => ({
        id: project.project_id,
        message: `${project.applicant_email} applied to your ${project.title}`,
        time: new Date(project.project_updated_at || project.updated_at).toLocaleString(),
        read: project.application_status !== 'pending',
        icon: 'fas fa-briefcase'
    }))
);

onMounted(() => {
    loadNotifications();
});
</script>