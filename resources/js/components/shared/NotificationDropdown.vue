<template>
    <li class="relative" ref="dropdownRef">
        <button
            @click="toggleDropdown"
            class="relative hover:text-gray-300 cursor-pointer flex items-center gap-1 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            <i class="fas fa-bell text-base"></i>
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
                class="absolute right-0 mt-3 w-80 bg-white text-gray-800 shadow-xl rounded-lg border border-gray-200 z-50 overflow-x-hidden"
                @click.stop
            >
                <div
                    class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50"
                >
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-bell text-blue-500"></i>
                        <h3 class="text-1xl font-semibold label">Messages</h3>
                    </div>
                    <button
                        @click="callMarkAllAsRead"
                        class="cursor-pointer text-sm text-blue-600 hover:text-blue-800 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isLoading || pendingNotificationsCount === 0"
                    >
                        Mark all as read
                    </button>
                </div>

                <ul
                    class="max-h-80 overflow-y-auto divide-y divide-gray-100 list-none p-0 m-0"
                >
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
                        @click="
                            viewNotification(
                                notification.id,
                                notification.application_id
                            )
                        "
                        :class="[
                            'p-2.5 mb-1 border border-gray-200 rounded flex items-center gap-2.5 cursor-pointer',
                            notification.is_read
                                ? 'hover:bg-gray-50'
                                : 'bg-blue-100 font-semibold',
                        ]"
                    >
                        <div class="flex-shrink-0">
                            <img
                                :src="
                                    notification.profile_picture
                                        ? notification.profile_picture
                                        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                                "
                                alt="profile"
                                class="h-10 w-10 rounded-full object-center object-cover"
                            />
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm">
                                <span
                                    class="text-blue-500 font-semibold me-1 name"
                                >
                                    {{ notification.username }}
                                </span>
                                <span class="me-1 !font-normal">
                                    {{
                                        role === "employer"
                                            ? "applied to your"
                                            : notification.application_status
                                    }}
                                </span>
                                <span class="font-medium">
                                    {{ notification.project_title }}
                                </span>
                            </p>

                            <p
                                class="text-xs font-normal text-gray-600 ml-auto"
                            >
                                {{ notification.time }}
                            </p>
                        </div>
                        <div
                            v-if="!notification.is_read"
                            class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"
                        ></div>
                    </li>
                </ul>

                <div
                    v-if="notifications.length > 0 && !isLoading && !fetchError"
                    class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50"
                >
                    <button
                        @click="openSlidingNotif()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-normal cursor-pointer"
                    >
                        View All Notifications
                    </button>
                </div>
                <showAllNotifModal
                    :show="showModalAllNotif"
                    @hide="closeSlidingNotif"
                    :data="ProjectData"
                />
            </div>
        </transition>
    </li>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useDropdown } from "@/composables/useToggleVisibility";
import {
    fetchUserNotifications,
    markAllAsRead,
} from "@/services/NotificationsService";
import { Inertia } from "@inertiajs/inertia";
import showAllNotifModal from "@/components/Modal/Shared/AllNotification.vue";
import { formatDateTime } from "@/utils/datetimeUtils";

// Dropdown state and handlers from the custom useDropdown composable
const { isDropdownOpen, toggleDropdown, closeDropdown, dropdownRef } =
    useDropdown();
const ProjectData = ref([]);
const isLoading = ref(false);
const fetchError = ref(null);
const page = usePage();
const role = page.props.user?.role;

// load notifications
const loadNotifications = async () => {
    isLoading.value = true;
    fetchError.value = null;

    const { data, error } = await fetchUserNotifications();

    if (error) {
        fetchError.value = error;
        ProjectData.value = [];
    } else {
        ProjectData.value = data;
        console.log(ProjectData.value);
    }

    isLoading.value = false;
};

// Mark all notifications as read
const callMarkAllAsRead = async () => {
    fetchError.value = null;

    const { success, error } = await markAllAsRead();
    if (error) {
        fetchError.value = error;
    }
    if (success) {
        await loadNotifications();
    }
};

// Transform raw project data into a list of notification objects for the UI
const notifications = computed(() => {
    if (role === "employer") {
        return ProjectData.value.map((project) => ({
            id: project.project_id,
            application_id: project.application_id,
            username: project.applicant_username,
            project_title: project.title,
            time: formatDateTime(
                project.project_updated_at || project.updated_at
            ),
            is_read: project.application_status !== "pending",
            profile_picture: project.profile_picture,
        }));
    } else {
        return ProjectData.value
            .filter(
                (project) =>
                    project.application_status !== "pending" &&
                    project.application_status !== "cancelled"
            )
            .map((project) => ({
                id: project.project_id,
                application_id: project.application_id,
                username: project.username,
                project_title: project.title,
                application_status: project.application_status,
                time: formatDateTime(project.updated_at || project.updated_at),
                is_read: project.is_read,
                profile_picture: project.profile_picture,
            }));
    }
});

// count pending status
const pendingNotificationsCount = computed(() => {
    if (role === "employer") {
        return notifications.value.filter(
            (notification) => !notification.is_read
        ).length;
    } else {
        return notifications.value.filter(
            (notification) => notification.is_read === false
        ).length;
    }
});

/**
 * Handle notification click to view project details
 * @param {number} id - Project ID
 * @param {number} application_id - Application ID
 */
function viewNotification(id, application_id) {
    if (!id || !application_id) {
        alert("Invalid project or application ID.");
        return;
    }
    Inertia.visit(route("project.show", { id, application_id }), {
        onError: () => {
            alert(
                "Could not load project. It may not exist or you do not have permission."
            );
        },
        onFinish: () => {
            closeDropdown();
        },
    });
}

// show all notif
const showModalAllNotif = ref(false);
const openSlidingNotif = () => {
    showModalAllNotif.value = true;
};

const closeSlidingNotif = () => {
    showModalAllNotif.value = false;
};

onMounted(() => {
    loadNotifications();
});
</script>
