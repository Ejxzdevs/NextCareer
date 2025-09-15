<template>
    <MainLayout>
        <div class="h-screen overflow-hidden">
            <div class="container-fluid h-full w-full">
                <div
                    class="grid grid-cols-1 md:grid-cols-[370px_1fr] gap-6 md:gap-8 lg:gap-12 h-full"
                >
                    <!-- Sidebar Filters -->
                    <aside
                        class="bg-white p-6 shadow-lg border border-gray-200 hidden sm:block"
                    >
                        <h2 class="text-xl text-gray-800 mb-6">Filters</h2>

                        <!-- Search -->
                        <div class="mb-5">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Search by title</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                >
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input
                                    v-model="searchTitle"
                                    type="text"
                                    placeholder="Type words"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                />
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-5">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Category</label
                            >
                            <div class="relative">
                                <select
                                    v-model="category"
                                    class="w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white"
                                >
                                    <option value="" selected>All</option>
                                    <option value="Web Development">
                                        Web Development
                                    </option>
                                    <option value="Mobile Development">
                                        Mobile Development
                                    </option>
                                    <option value="Software Development">
                                        Software Development
                                    </option>
                                    <option value="Game Development">
                                        Game Development
                                    </option>
                                    <option value="Data Science">
                                        Data Science
                                    </option>
                                    <option value="AI / Machine Learning">
                                        AI / Machine Learning
                                    </option>
                                    <option value="Cybersecurity">
                                        Cybersecurity
                                    </option>
                                    <option value="DevOps">DevOps</option>
                                    <option value="Cloud Computing">
                                        Cloud Computing
                                    </option>
                                    <option value="Blockchain">
                                        Blockchain
                                    </option>
                                    <option value="UI/UX Design">
                                        UI/UX Design
                                    </option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Content Writing">
                                        Content Writing
                                    </option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                                >
                                    <i
                                        class="fas fa-chevron-down text-gray-400"
                                    ></i>
                                </div>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="mb-5">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Budget ($)</label
                            >
                            <div class="grid grid-cols-2 gap-4">
                                <input
                                    v-model.number="minBudget"
                                    type="number"
                                    placeholder="Min"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                />
                                <input
                                    v-model.number="maxBudget"
                                    type="number"
                                    placeholder="Max"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                />
                            </div>
                        </div>
                    </aside>

                    <!-- Projects List -->
                    <section
                        class="flex space-y-6 overflow-y-auto pr-2 pt-5 pb-20 h-screen w-full px-4 sm:px-0"
                    >
                        <div
                            v-if="filteredProjects.length > 0"
                            class="space-y-6 pb-20"
                        >
                            <div
                                v-for="project in filteredProjects"
                                :key="project.id"
                                class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 p-5 flex flex-col sm:flex-row sm:items-start gap-4 sm:gap-6 lg:gap-8 max-w-4xl mx-auto w-full"
                            >
                                <!-- Left: User + Project Info -->
                                <div class="flex flex-col flex-grow w-full">
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="
                                                project.user.profile
                                                    .profile_picture ||
                                                'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                                            "
                                            alt="profile photo"
                                            class="h-12 w-12 rounded-full object-cover border border-gray-300 shadow-sm"
                                        />
                                        <div>
                                            <p
                                                class="text-gray-900 font-semibold text-sm"
                                            >
                                                {{ project.user.username }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 mt-1 flex items-center gap-1"
                                            >
                                                <i class="fas fa-clock"></i>
                                                {{
                                                    formatTimeAgo(
                                                        project.created_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 mt-4">
                                        <!-- Title + Skills -->
                                        <div class="flex flex-col gap-3">
                                            <h2
                                                class="text-lg sm:text-xl font-bold text-blue-800 line-clamp-1"
                                            >
                                                {{ project.title }}
                                            </h2>
                                            <p
                                                class="text-gray-500 text-sm sm:text-base line-clamp-3"
                                            >
                                                {{ project.description }}
                                            </p>
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    v-for="skill in formatSkills(
                                                        project.skills
                                                    )"
                                                    :key="skill"
                                                    class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-300"
                                                >
                                                    {{ skill }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right: Actions -->
                                        <div
                                            class="flex flex-wrap justify-center sm:justify-start gap-3 mt-4"
                                        >
                                            <button
                                                @click="openApplyModal(project)"
                                                class="rounded-md bg-[#FD0585] hover:bg-[#dc4e98cc] cursor-pointer py-2 px-5 text-white text-sm font-medium transition"
                                            >
                                                Quick Apply
                                            </button>
                                            <button
                                                @click="
                                                    openDetailsModal(project)
                                                "
                                                class="text-blue-600 cursor-pointer hover:underline text-sm hover:text-blue-800 transition"
                                            >
                                                See details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No Projects -->
                        <div
                            v-else
                            class="text-center bg-white rounded-sm shadow p-10 mx-auto"
                        >
                            <i
                                class="fas fa-folder-open text-6xl text-gray-300 mb-6"
                            ></i>
                            <p class="text-2xl font-bold text-gray-800 mb-3">
                                No projects available
                            </p>
                            <p class="text-gray-600 text-lg">
                                We’re constantly adding new opportunities.
                                Please check back soon!
                            </p>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Modals -->
            <ApplyModal
                :show="showApplyModal"
                :selectedProject="selectedProject"
                @close="closeApplyModal"
                @submitted="handleApplicationSubmitted"
            />

            <ProjectDetailsModal
                :show="showDetailsModal"
                :project="selectedProject"
                @close="closeDetailsModal"
                @openApplyModal="openApplyModal"
            />
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { formatTimeAgo } from "@/utils/datetimeUtils";
import ApplyModal from "@/components/modals/ApplyForm.vue";
import ProjectDetailsModal from "@/components/shared/ShowProjectDetails.vue";
import { formatSkills } from "@/utils/stringUtils";

const showApplyModal = ref(false);
const showDetailsModal = ref(false);
const selectedProject = ref(null);

const { projects: allProjects } = usePage().props;

// Filters
const searchTitle = ref("");
const category = ref("");
const minBudget = ref(null);
const maxBudget = ref(null);

// Filtered Projects
const filteredProjects = computed(() => {
    return allProjects.filter((project) => {
        const matchesTitle = project.title
            ?.toLowerCase()
            .includes(searchTitle.value?.toLowerCase() || "");
        const matchesCategory =
            category.value === "" || project.category === category.value;
        const matchesBudget = (() => {
            const budget = parseFloat(project.budget);
            if (isNaN(budget)) return false;

            const min =
                minBudget.value === null || isNaN(minBudget.value)
                    ? -Infinity
                    : parseFloat(minBudget.value);
            const max =
                maxBudget.value === null || isNaN(maxBudget.value)
                    ? Infinity
                    : parseFloat(maxBudget.value);

            return budget >= min && budget <= max;
        })();

        return matchesTitle && matchesCategory && matchesBudget;
    });
});

// Modal Functions
function openApplyModal(project) {
    selectedProject.value = project;
    showApplyModal.value = true;
    showDetailsModal.value = false;
}

function closeApplyModal() {
    showApplyModal.value = false;
    selectedProject.value = null;
}

function handleApplicationSubmitted() {
    alert("Your application has been submitted successfully!");
    window.location.reload();
}

function openDetailsModal(project) {
    selectedProject.value = project;
    showDetailsModal.value = true;
    showApplyModal.value = false;
}

function closeDetailsModal() {
    showDetailsModal.value = false;
    selectedProject.value = null;
}
</script>
