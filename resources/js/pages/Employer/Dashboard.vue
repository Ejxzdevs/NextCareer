<template>
    <MainLayout>
        <div
            class="h-screen overflow-y-auto pt-4 pb-20 px-8 sm:p-6 lg:pt-8 lg:pb-30"
        >
            <!-- Welcome Header -->
            <h1
                class="text-2xl sm:text-3xl title text-gray-800 mb-8 text-center"
            >
                Welcome to your Dashboard
                <span class="text-blue-700">{{
                    formatNameFromEmail(user.email)
                }}</span
                >!
            </h1>

            <!-- Stats Section -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl mx-auto"
            >
                <!-- Active Job Project -->
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center justify-between transition-transform transform hover:scale-105 duration-300"
                >
                    <div>
                        <h2
                            class="text-xl font-semibold text-gray-700 mb-2 count-label"
                        >
                            Active Job Project
                        </h2>
                        <p class="text-5xl font-bold text-blue-600">
                            {{ projects?.length ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg
                            class="h-16 w-16 text-blue-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0
                   002 2h10a2 2 0 002-2V7a2 2 0
                   00-2-2h-2M9 5a2 2 0 002
                   2h2a2 2 0 002-2M9 5a2 2
                   0 012-2h2a2 2 0 012 2m-3
                   7h3m-3 4h3m-6-4h.01M9
                   16h.01"
                            />
                        </svg>
                    </div>
                </div>

                <!-- New Applicants This Week -->
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center justify-between transition-transform transform hover:scale-105 duration-300"
                >
                    <div>
                        <h2
                            class="text-xl font-semibold text-gray-700 mb-2 count-label"
                        >
                            New Applicants This Week
                        </h2>
                        <p class="text-5xl font-bold text-green-600">
                            {{ totalWeeklyApplications ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg
                            class="h-16 w-16 text-green-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15
                   21H3v-1a6 6 0 0112 0v1zm-6-4a4
                   4 0 00-4-4H5a4 4 0 00-4
                   4v1h10v-1zm14 0a4 4 0 00-4-4h-3.465m4.935
                   9.001H21m-6.856-9.001h.01"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- What's Next & Recent Posts -->
            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 max-w-6xl mx-auto"
            >
                <!-- What's Next -->
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex flex-col justify-center items-center text-center"
                >
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                        What's Next?
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Quickly jump to important tasks or create new
                        opportunities.
                    </p>
                    <div
                        class="flex flex-col sm:flex-row gap-4 w-full justify-center"
                    >
                        <Link
                            :href="route('employer.project')"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105"
                        >
                            + Post a New Remote Job
                        </Link>
                        <Link
                            :href="route('employer.application')"
                            class="flex-1 px-6 py-3 border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-50 transition duration-300 ease-in-out flex items-center justify-center transform hover:scale-105"
                        >
                            View All Applicants
                        </Link>
                    </div>
                </div>

                <!-- Recent Job Posts -->
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-200"
                >
                    <h2 class="mb-4 label">Recent Job Posts</h2>
                    <div v-if="projects.length > 0" class="space-y-4">
                        <div
                            v-for="project in projects"
                            :key="project.id"
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-200"
                        >
                            <div>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ project.title }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Status:
                                    <span class="font-semibold text-green-700"
                                        >Active</span
                                    >
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Posted: {{ formatDate(project.created_at) }}
                                </p>
                            </div>
                            <div class="mt-2 sm:mt-0 flex space-x-2">
                                <a
                                    @click="viewNotification(project.id)"
                                    class="text-blue-500 hover:underline text-sm cursor-pointer"
                                >
                                    View Applicants ({{
                                        project.applications.length
                                    }})
                                </a>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p>No post projects yet.</p>
                    </div>
                </div>
            </div>

            <!-- Recent Applicants -->
            <div v-if="applications.length > 0" class="max-w-6xl mx-auto mt-8">
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-200"
                >
                    <h2 class="label mb-4">Recent Applicants</h2>
                    <div class="space-y-4">
                        <div
                            v-for="application in getFiveApplications"
                            :key="application.id"
                            class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-200"
                        >
                            <div
                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center"
                            >
                                <!-- Profile Info -->
                                <div
                                    class="flex items-start gap-4 flex-1 min-w-0 mb-4 sm:mb-0"
                                >
                                    <div class="flex-shrink-0">
                                        <img
                                            :src="
                                                application.user.profile
                                                    .profile_picture
                                                    ? application.user.profile
                                                          .profile_picture
                                                    : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                                            "
                                            class="h-16 w-16 rounded-full object-cover border-2 border-gray-300"
                                            alt="profile photo"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <Link
                                            :href="
                                                route(
                                                    'userProfile.show',
                                                    application.user.id
                                                )
                                            "
                                            class="block name text-blue-700 hover:underline Poppins truncate"
                                        >
                                            {{
                                                capitalizeFirstLetter(
                                                    application.user.username
                                                )
                                            }}
                                        </Link>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Applied for:
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{
                                                    application.project.title
                                                }}</span
                                            >
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Applied:
                                            {{
                                                formatDate(
                                                    application.created_at
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div
                                    class="flex flex-col sm:flex-row gap-3 items-center"
                                >
                                    <select
                                        v-model="application.application_status"
                                        @change="updateStatus(application)"
                                        class="py-2 px-3 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center cursor-pointer"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="viewed">Viewed</option>
                                        <option value="reviewed">
                                            Reviewed
                                        </option>
                                        <option value="scheduled">
                                            Scheduled
                                        </option>
                                        <option value="hired">Hired</option>
                                        <option value="rejected">
                                            Rejected
                                        </option>
                                    </select>

                                    <div class="flex gap-3">
                                        <a
                                            :href="`/storage/${application.resume}`"
                                            target="_blank"
                                            class="inline-flex items-center text-sm font-medium text-blue-600 hover:underline px-3 py-2 border border-blue-200 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2
                             0 01-2-2V5a2 2 0 012-2h5.586a1
                             1 0 01.707.293l5.414
                             5.414a1 1 0 01.293.707V19a2
                             2 0 01-2 2z"
                                                />
                                            </svg>
                                            Resume
                                        </a>

                                        <MessageModal
                                            :username="
                                                application.user.username
                                            "
                                            :id="application.user.id"
                                            :profile_picture="
                                                application.user.profile
                                                    .profile_picture
                                            "
                                        />

                                        <Link
                                            :href="
                                                route(
                                                    'userProfile.show',
                                                    application.user.id
                                                )
                                            "
                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors shadow-sm text-center"
                                        >
                                            View Profile
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { formatDate } from "@/utils/datetimeUtils";
import { Inertia } from "@inertiajs/inertia";
import {
    formatNameFromEmail,
    capitalizeFirstLetter,
} from "@/utils/stringUtils";
import MessageModal from "@/components/Modal/Shared/MessageModal.vue";
import { updateStatusApi } from "@/services/ApplicationServices";

const { projects, applications, totalWeeklyApplications } = usePage().props;
const page = usePage();
const user = page.props.user;
const getFiveApplications = computed(() => applications.slice(0, 5));
const fetchError = ref(null);

/**
 * Handle notification click to view project details
 * @param {number} id - Project ID
 */
function viewNotification(id) {
    if (!id) {
        alert("Invalid project ID.");
        return;
    }
    Inertia.get(
        route("project.show", { id }),
        {},
        {
            onError: () => {
                alert(
                    "Could not load project. It may not exist or you do not have permission."
                );
            },
        }
    );
}

// Updates application status and reloads page on success
const updateStatus = async (application) => {
    const { success, error } = await updateStatusApi(
        application.application_status,
        application.id
    );

    fetchError.value = null;

    if (error) {
        fetchError.value = error;
    }

    if (success) {
        window.location.reload();
    }
};
</script>
