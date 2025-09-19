<template>
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="h-16 flex items-center mb-6">
            <a
                :href="route('employer.project')"
                class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>

        <!-- Page Title -->
        <h1
            class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3"
        >
            <i class="fas fa-bell text-blue-600"></i>
            Notifications
        </h1>

        <!-- Loading -->
        <div
            v-if="isLoading"
            class="flex items-center gap-2 text-gray-500 text-lg"
        >
            <i class="fas fa-spinner fa-spin"></i> Loading notifications...
        </div>

        <!-- Error Message -->
        <div
            v-if="fetchError"
            class="bg-red-50 text-red-700 p-4 rounded-lg mb-4 border border-red-200"
        >
            {{ fetchError }}
        </div>

        <!-- Notifications List -->
        <ul
            v-if="!isLoading && notifications.length"
            class="bg-white rounded-xl shadow divide-y divide-gray-200 overflow-hidden"
        >
            <li
                v-for="notification in notifications"
                :key="notification.id"
                @click="
                    viewNotification(
                        notification.id,
                        notification.application_id
                    )
                "
                :class="[
                    'flex items-start gap-4 p-4 cursor-pointer transition hover:bg-gray-50',
                    !notification.read && 'bg-blue-50',
                ]"
            >
                <!-- Icon -->
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 text-xl flex-shrink-0"
                >
                    <i :class="notification.icon"></i>
                </div>

                <!-- Content -->
                <div class="flex-1">
                    <p class="text-sm sm:text-base text-gray-700">
                        <span class="font-semibold text-blue-600">{{
                            notification.username
                        }}</span>
                        <span class="font-normal"> applied to your </span>
                        <span class="font-semibold text-gray-900">{{
                            notification.project_title
                        }}</span>
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        {{ notification.time }}
                    </p>
                </div>

                <!-- Badge for unread -->
                <div
                    v-if="!notification.read"
                    class="px-3 py-1 text-xs sm:text-sm font-semibold bg-blue-600 text-white rounded-full"
                >
                    New
                </div>
            </li>
        </ul>

        <!-- No Notifications -->
        <div
            v-else-if="!isLoading && !notifications.length"
            class="flex flex-col items-center mt-16 text-gray-500"
        >
            <i class="fas fa-inbox text-6xl mb-3 text-gray-300"></i>
            <p class="text-lg sm:text-xl font-medium">No notifications found</p>
            <p class="text-sm sm:text-base">You're all caught up 🎉</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { fetchEmployerNotifications } from "@/services/NotificationsService";
import { Inertia } from "@inertiajs/inertia";

const rawProjectData = ref([]);
const isLoading = ref(false);
const fetchError = ref(null);

// Load notifications
const loadNotifications = async () => {
    isLoading.value = true;
    fetchError.value = null;
    const { data, error } = await fetchEmployerNotifications();
    if (error) {
        fetchError.value = error;
        rawProjectData.value = [];
    } else {
        rawProjectData.value = data;
    }
    isLoading.value = false;
};

// Map raw project data into notification objects
const notifications = computed(() =>
    rawProjectData.value.map((project) => ({
        id: project.project_id,
        application_id: project.application_id,
        username: project.applicant_username,
        project_title: project.title,
        time: new Date(
            project.project_updated_at || project.updated_at
        ).toLocaleString(),
        read: project.application_status !== "pending",
        icon: "fas fa-briefcase",
    }))
);

/**
 * View notification details by navigating to the project page
 * @param {number} id - The project ID to view
 */

function viewNotification(id, application_id) {
    if (!id || !application_id) {
        alert("Invalid project or application ID.");
        return;
    }
    Inertia.get(
        route("project.show", { id: id, application_id: application_id }),
        {},
        {
            onError: (errors) => {
                alert(
                    "Could not load project. It may not exist or you do not have permission."
                );
            },
        }
    );
}

onMounted(() => {
    loadNotifications();
});
</script>
