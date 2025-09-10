<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
  >
    <div
      class="bg-white max-w-md w-full p-4 md:p-8 rounded-md shadow-2xl animate-fade-in-scale relative overflow-y-auto max-h-[75vh] space-y-6"
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
              :src="project.user.profile.profile_picture ? project.user.profile.profile_picture : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
              alt="profile"
              class="h-12 w-12 rounded-full object-center object-cover"
            />
          <div class="flex flex-col gap-0">
            <p class="block text-xs text-gray-500 ">Client</p>
            <span class="name text-sm text-gray-800">{{ formatNameFromEmail(project?.user?.email) || 'Unknown Client' }}</span>
          </div>
        </div>
        
      <!-- Project Title -->
      <header>
        <h2 class="text-2xl font-bold text-blue-900 title mb-2">
          {{ project?.title || 'Project Details' }}
        </h2>
        <p class="text-gray-600 text-sm">
          {{ project?.description || 'No description available.' }}
        </p>
      </header>

      <!-- Details Grid -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6 px-3 text-gray-800 text-sm">
        <!-- Budget -->
        <div class="flex items-start gap-3 md:col-span-2">
          <i class="fa-2x fas fa-coins text-yellow-500 mt-1"></i>
          <div>
            <strong class="block label">Budget</strong>
            <span class="text-xs">{{ formatCurrency(project?.budget) }}</span>
          </div>
        </div>

        <!-- Start Date -->
        <div class="flex items-start gap-3">
          <i class="fa-2x far fa-calendar-alt text-green-500 mt-1"></i>
          <div>
            <strong class="block label">Start Date</strong>
            <span class="text-xs">{{ formatDate(project?.start_date) }}</span>
          </div>
        </div>

        <!-- Deadline -->
        <div class="flex items-start gap-3">
          <i class="fa-2x far fa-calendar-check text-red-500 mt-1"></i>
          <div>
            <strong class="block label">Deadline</strong>
            <span class="text-xs">{{ formatDate(project?.deadline) }}</span>
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">
          Required Skills:
        </h3>
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
      <div v-if="user.role === 'freelance'" class="flex justify-center">
        <button
          @click="applyForProject(project)"
          class="w-full cursor-pointer bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition"
        >
          Apply Now
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { formatDate } from '@/utils/datetimeUtils';
import { formatSkills , formatNameFromEmail } from '@/utils/stringUtils';
import { formatCurrency } from '@/utils/numberUtils';
import { usePage } from '@inertiajs/vue3';

const { user } = usePage().props;

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
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
