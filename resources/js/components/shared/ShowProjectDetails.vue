<template>
    <!-- Modal container -->
    <div
        v-if="show"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center px-4 py-6 sm:px-6"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="bg-white w-full max-w-lg sm:max-w-xl md:max-w-2xl lg:max-w-3xl max-h-[90vh] overflow-y-auto rounded-xl shadow-2xl animate-fade-in-scale relative p-4 sm:p-6 md:p-8 space-y-6"
        >
            <!-- Close Button -->
            <button
                @click="closeModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer"
                aria-label="Close modal"
            >
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Client Info -->
            <div
                v-if="user.role === 'freelance'"
                class="flex items-center gap-3"
            >
                <img
                    :src="
                        project.user.profile.profile_picture
                            ? project.user.profile.profile_picture
                            : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                    "
                    alt="profile"
                    class="h-12 w-12 rounded-full object-cover border border-gray-300"
                />
                <div class="flex flex-col">
                    <p class="text-xs text-gray-500">Client</p>
                    <Link
                        :href="route('userProfile.show', project.user.id)"
                        class="text-sm text-gray-800 hover:underline hover:text-blue-500"
                    >
                        {{
                            formatNameFromEmail(project?.user?.username) ||
                            "Unknown Client"
                        }}
                    </Link>
                </div>
            </div>

            <!-- Project Title -->
            <header class="flex flex-col gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-thumbtack text-red-500"></i>
                        <span class="text-sm font-medium">Title</span>
                    </div>
                    <h2
                        class="text-xl sm:text-2xl font-bold text-blue-900 mt-1"
                    >
                        {{ project?.title || "Project Details" }}
                    </h2>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-file-alt text-cyan-500"></i>
                        <span class="text-sm font-medium">Description</span>
                    </div>
                    <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                        {{
                            project?.description || "No description available."
                        }}
                    </p>
                </div>
            </header>

            <!-- Details Grid -->
            <section
                class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-800 text-sm"
            >
                <!-- Budget -->
                <div class="flex items-center gap-3 sm:col-span-2">
                    <i class="fas fa-coins text-yellow-500 text-xl"></i>
                    <div>
                        <p class="text-xs font-medium">Budget</p>
                        <p class="text-sm">
                            {{ formatCurrency(project?.budget) }}
                        </p>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="flex items-center gap-3">
                    <i class="far fa-calendar-alt text-green-500 text-xl"></i>
                    <div>
                        <p class="text-xs font-medium">Start Date</p>
                        <p class="text-sm">
                            {{ formatDate(project?.start_date) }}
                        </p>
                    </div>
                </div>

                <!-- Deadline -->
                <div class="flex items-center gap-3">
                    <i class="far fa-calendar-check text-red-500 text-xl"></i>
                    <div>
                        <p class="text-xs font-medium">Deadline</p>
                        <p class="text-sm">
                            {{ formatDate(project?.deadline) }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Skills Section -->
            <section>
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-laptop-code text-green-500"></i>
                    <span class="text-sm font-semibold text-gray-700">
                        Required Skills
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="category in formatSkills(project.category)"
                        :key="category"
                        class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                    >
                        {{ category }}
                    </span>
                    <span
                        v-for="skills in formatSkills(project?.skills)"
                        :key="skills"
                        class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
                    >
                        {{ skills }}
                    </span>
                </div>
            </section>

            <!-- Apply Button -->
            <div
                v-if="user.role === 'freelance' && !hideBtnApply"
                class="flex justify-center"
            >
                <button
                    @click="applyForProject(project)"
                    class="flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-pink-500 to-[#FD0585] hover:from-pink-600 hover:to-[#dc4e98] cursor-pointer py-2.5 px-6 text-white text-sm sm:text-base font-semibold shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                >
                    <i class="fas fa-paper-plane"></i>
                    Apply Now
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { formatDate } from "@/utils/datetimeUtils";
import { formatSkills, formatNameFromEmail } from "@/utils/stringUtils";
import { formatCurrency } from "@/utils/numberUtils";
import { usePage } from "@inertiajs/vue3";

const { user } = usePage().props;

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    hideBtnApply: {
        type: Boolean,
        default: false,
    },
    project: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close", "openApplyModal"]);

// Emits a 'close' event to the parent to hide the modal.
function closeModal() {
    emit("close");
}

/**
 * Emits an 'openApplyModal' event to the parent, passing the current project.
 * This allows the parent to open the ApplyFormModal with the correct project.
 * @param {Object} project - The project object to apply for.
 */
function applyForProject(project) {
    emit("openApplyModal", project);
}
</script>

<style scoped>
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in-scale {
    animation: fadeInScale 0.3s ease-out forwards;
}
</style>
