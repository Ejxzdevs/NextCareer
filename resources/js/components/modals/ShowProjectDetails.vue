<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center px-4 py-8"
    role="dialog"
    aria-modal="true"
  >
    <div
      class="bg-white max-w-2xl w-full sm:py-8 p-4 md:p-8 rounded-md shadow-2xl animate-fade-in-scale relative overflow-y-auto h-auto space-y-6"
    >
      <!-- Close Button -->
      <button
        @click="closeModal"
        class="absolute top-2 right-4 text-gray-500 hover:text-gray-700 cursor-pointer"
        aria-label="Close modal"
      >
        <i class="fas fa-times text-lg"></i>
      </button>

      <!-- Client Info -->
      <div
        v-if="user.role === 'freelance'"
        class="flex items-center gap-2 md:col-span-2"
      >
        <img
          :src="project.user.profile.profile_picture 
            ? project.user.profile.profile_picture 
            : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
          alt="profile"
          class="h-12 w-12 rounded-full object-center object-cover"
        />
        <div class="flex flex-col gap-0">
          <p class="block text-xs text-gray-500">Client</p>
          <Link
            :href="route('userProfile.show', project.user.id)"
            class="name text-sm text-gray-800 hover:underline hover:text-blue-500 cursor-pointer"
          >
            {{ formatNameFromEmail(project?.user?.username) || 'Unknown Client' }}
          </Link>
        </div>
      </div>

      <!-- Project Title -->
      <header class="flex flex-col gap-2">
        <div class="mb-2">
          <div class="flex flex-row items-center gap-2">
            <i class="fa-1x fas fa-thumbtack text-red-500"></i>
            <label class="text-sm label">Title</label>
          </div>
          <label class="text-2xl font-bold !text-blue-900 label">
            {{ project?.title || 'Project Details' }}
          </label>
        </div>
        <div>
          <div class="flex flex-row items-center gap-2">
            <i class="fas fa-file-alt text-[#30d2e4d5]"></i>
            <label class="label text-xs">Description</label>
          </div>
          <p class="text-gray-600 text-sm mt-2">
            {{ project?.description || 'No description available.' }}
          </p>
        </div>
      </header>

      <!-- Details Grid -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800 text-sm">
        <!-- Budget -->
        <div class="flex items-center gap-3 md:col-span-2">
          <i class="fa-2x fas fa-coins text-yellow-500 mt-1"></i>
          <div class="flex flex-col gap-0">
            <strong class="block label text-xs">Budget</strong>
            <span class="text-xs">{{ formatCurrency(project?.budget) }}</span>
          </div>
        </div>

        <!-- Start Date -->
        <div class="flex items-center gap-3">
          <i class="fa-2x far fa-calendar-alt text-green-500 mt-1"></i>
          <div class="flex flex-col gap-0">
            <label class="block label text-xs">Start Date</label>
            <span class="text-xs">{{ formatDate(project?.start_date) }}</span>
          </div>
        </div>

        <!-- Deadline -->
        <div class="flex items-center gap-3">
          <i class="fa-2x far fa-calendar-check text-red-500 mt-1"></i>
          <div class="flex flex-col gap-0">
            <label class="block label text-xs">Deadline</label>
            <span class="text-xs">{{ formatDate(project?.deadline) }}</span>
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section>
        <div class="flex flex-row gap-2 items-center mb-3">
          <i class="fas fa-laptop-code text-green-500"></i>
          <label class="text-xs label font-semibold text-gray-700">
            Required Skills:
          </label>
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

        <div v-if="user.role === 'freelance' && !hideBtnApply" class="flex justify-center">
        <button
          @click="applyForProject(project)"
          class="flex items-center gap-3 px-6 py-1 border-2 border-gray-700 rounded-full
                 text-gray-700 font-bold uppercase tracking-wide 
                 hover:bg-gray-100 hover:border-blue-600 hover:text-blue-600
                 transition duration-300 cursor-pointer"
        >
          Apply Now
          <i class="fas fa-hand-pointer text-xl"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { formatDate } from '@/utils/datetimeUtils';
import { formatSkills, formatNameFromEmail } from '@/utils/stringUtils';
import { formatCurrency } from '@/utils/numberUtils';
import { usePage } from '@inertiajs/vue3';

const { user } = usePage().props;

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  hideBtnApply: {
    type: Boolean,
    default: false
  },
  project: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['close', 'openApplyModal']);

// Emits a 'close' event to the parent to hide the modal.
function closeModal() {
  emit('close');
}

/**
 * Emits an 'openApplyModal' event to the parent, passing the current project.
 * This allows the parent to open the ApplyFormModal with the correct project.
 * @param {Object} project - The project object to apply for.
 */
function applyForProject(project) {
  emit('openApplyModal', project);
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
