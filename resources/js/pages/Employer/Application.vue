<template>
    <MainLayout>
        <div
            class="max-h-screen overflow-y-auto p-4 sm:p-6 lg:pt-8 lg:pb-32 lg:"
        >
            <div class="mb-6 text-center">
                <h1
                    class="text-2xl sm:text-3xl font-extrabold text-gray-800 text-center title"
                >
                    Applications
                </h1>
                <p class="text-lg text-gray-600 mt-2">
                    Manage and view the applications.
                </p>
            </div>

            <!-- Filters Section -->
            <div
                class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8 max-w-7xl mx-auto"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <!-- Filter by Job Post -->
                    <div v-if="projects && projects.length > 0">
                        <label
                            for="job-filter"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Filter by Job Post
                        </label>
                        <select
                            v-model="selectedProjectTitle"
                            id="job-filter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                        >
                            <option value="">All Job Posts</option>
                            <option
                                v-for="project in projects"
                                :key="project.id"
                                :value="project.title"
                            >
                                {{ project.title }}
                            </option>
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div>
                        <label
                            for="status-filter"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Filter by Status
                        </label>
                        <select
                            v-model="selectedStatus"
                            id="status-filter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                        >
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="viewed">Viewed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="hired">Hired</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Search Applicants -->
                    <div class="md:col-span-2 lg:col-span-1">
                        <label
                            for="search-applicant"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Search Applicants
                        </label>
                        <input
                            v-model="searchName"
                            type="text"
                            id="search-applicant"
                            placeholder="Search by name or keyword..."
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                </div>
            </div>

            <!-- Applicants List -->
            <div
                class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 max-w-7xl mx-auto"
            >
                <h2 class="text-lg font-semibold mb-6">Applicant List</h2>

                <!-- Applications -->
                <div
                    v-if="applications.data && applications.data.length > 0"
                    class="space-y-6"
                >
                    <div
                        v-for="application in filteredApplications"
                        :key="application.id"
                        class="border-b border-gray-200 pb-6 last:border-b-0"
                    >
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center"
                        >
                            <!-- Applicant Info -->
                            <div
                                class="flex items-start gap-4 flex-1 min-w-0 mb-4 sm:mb-0"
                            >
                                <div class="flex-shrink-0">
                                    <img
                                        :src="
                                            application.user.profile
                                                .profile_picture ||
                                            'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
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
                                        class="block font-semibold text-blue-700 hover:underline truncate"
                                    >
                                        {{
                                            capitalizeFirstLetter(
                                                application.user.username
                                            )
                                        }}
                                    </Link>
                                    <p class="text-xs text-gray-700 mt-1">
                                        Applied for:
                                        <span class="font-medium text-blue-900">
                                            {{ application.project.title }}
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Applied:
                                        {{ formatDate(application.created_at) }}
                                    </p>

                                    <p
                                        @click="
                                            openDetailsModal(
                                                application.project
                                            )
                                        "
                                        class="text-blue-600 cursor-pointer hover:underline text-xs hover:text-blue-800 transition"
                                        role="button"
                                        tabindex="0"
                                        @keyup.enter="
                                            openDetailsModal(
                                                application.project
                                            )
                                        "
                                    >
                                        View Project Details
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div
                                class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center sm:gap-4 gap-2 w-full sm:w-auto"
                            >
                                <!-- Status Dropdown -->
                                <select
                                    v-model="application.application_status"
                                    @change="updateStatus(application)"
                                    class="text-center cursor-pointer py-2 border border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="viewed">Viewed</option>
                                    <option value="cancelled" disabled>
                                        Cancelled
                                    </option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="hired">Hired</option>
                                    <option value="rejected">Rejected</option>
                                </select>

                                <!-- Resume -->
                                <a
                                    :href="`/storage/${application.resume}`"
                                    target="_blank"
                                    class="inline-flex gap-2 cursor-pointer items-center text-sm font-medium text-blue-600 hover:underline px-3 py-2 border border-blue-200 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors"
                                >
                                    <i class="fas fa-file-download"></i>
                                    Resume
                                </a>

                                <!-- Portfolio Button -->
                                <a
                                    v-if="application.link_portfolio"
                                    :href="application.link_portfolio"
                                    target="_blank"
                                    class="inline-flex gap-2 cursor-pointer items-center text-sm font-medium border border-green-200 text-green-600 bg-green-200 hover:bg-green-100 px-3 py-2 rounded-md shadow transition-colors"
                                >
                                    <i class="fas fa-eye"></i>
                                    View Portfolio
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Project Details Modal -->
                    <ProjectDetailsModal
                        :show="showDetailsModal"
                        :project="selectedProject"
                        @close="closeDetailsModal"
                    />

                    <!-- Pagination -->
                    <div
                        v-if="
                            applications.links && applications.links.length > 0
                        "
                        class="flex justify-center mt-8"
                    >
                        <nav
                            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination"
                        >
                            <template
                                v-for="(link, key) in applications.links"
                                :key="key"
                            >
                                <div
                                    v-if="link.url === null"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-not-allowed"
                                    v-html="link.label"
                                ></div>
                                <Link
                                    v-else
                                    :href="link.url"
                                    v-html="link.label"
                                    :class="{
                                        'bg-blue-50 text-blue-600': link.active,
                                        'bg-white text-gray-700 hover:bg-gray-50':
                                            !link.active,
                                    }"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                                ></Link>
                            </template>
                        </nav>
                    </div>
                </div>

                <!-- No Applicants -->
                <div v-else class="text-center text-gray-600 py-8">
                    No applicants found.
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { formatDate } from "@/utils/datetimeUtils";
import { capitalizeFirstLetter } from "@/utils/stringUtils";
import ProjectDetailsModal from "@/components/Modal/Shared/ShowProjectDetails.vue";
import { updateStatusApi } from "@/services/ApplicationServices";

const { applications, projects } = usePage().props;

const searchName = ref("");
const selectedProjectTitle = ref("");
const selectedStatus = ref("");
const selectedProject = ref(null);
const showDetailsModal = ref(false);

const filteredApplications = computed(() =>
    applications.data.filter((app) => {
        const matchesName = app.user?.username
            ?.toLowerCase()
            .includes(searchName.value.toLowerCase());
        const matchesProject =
            selectedProjectTitle.value === "" ||
            app.project?.title === selectedProjectTitle.value;
        const matchesStatus =
            selectedStatus.value === "" ||
            app.application_status === selectedStatus.value;
        return matchesName && matchesProject && matchesStatus;
    })
);

const updateStatus = async (application) => {
    const { success, error } = await updateStatusApi(
        application.application_status,
        application.id
    );
    if (error) return;
    if (success) window.location.reload();
};

function openDetailsModal(project) {
    selectedProject.value = project;
    showDetailsModal.value = true;
}

function closeDetailsModal() {
    selectedProject.value = null;
    showDetailsModal.value = false;
}
</script>
