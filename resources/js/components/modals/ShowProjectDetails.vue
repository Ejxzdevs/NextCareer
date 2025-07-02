<template>
  <div v-if="show" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div
      class="bg-white max-w-md w-full p-4 md:p-6 rounded-md shadow-2xl animate-fade-in-scale relative overflow-y-auto max-h-[75vh] space-y-6"
    >
      <!-- Close Button -->
      <button @click="closeModal" class="absolute top-1 right-3 text-red-400 hover:text-red-700 text-2xl cursor-pointer">
        <i class="fas fa-times"></i>
      </button>

      <!-- Project Title -->
      <header>
        <h2 class="text-2xl font-bold text-gray-900 my-3">
          {{ project?.title || 'Project Details' }}
        </h2>
        <p class="text-gray-600 text-sm">
          {{ project?.description || 'No description available.' }}
        </p>
      </header>

      <!-- Details Grid -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800 text-sm">
        <div class="flex items-start gap-2">
          <i class="far fa-calendar-alt text-blue-500 mt-1"></i>
          <div>
            <strong class="block">Start Date</strong>
            <span>{{ formatDate(project?.start_date) }}</span>
          </div>
        </div>

        <div class="flex items-start gap-2">
          <i class="far fa-calendar-check text-blue-500 mt-1"></i>
          <div>
            <strong class="block">Deadline</strong>
            <span>{{ formatDate(project?.deadline) }}</span>
          </div>
        </div>

        <div class="flex items-start gap-2">
          <i class="fas fa-hand-holding-usd text-blue-500 mt-1"></i>
          <div>
            <strong class="block">Budget</strong>
            <span>{{ formatCurrency(project?.budget) }}</span>
          </div>
        </div>

        <div v-if="user.role == 'freelance'" class="flex items-start gap-2 md:col-span-2">
          <i class="fas fa-user-circle text-blue-500 mt-1"></i>
          <div>
            <strong class="block">Client</strong>
            <span>{{ project?.user?.email || 'Unknown Client' }}</span>
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Required Skills:</h3>
        <div class="flex flex-wrap gap-2">
          <span
                v-if="project.category"
                class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
              >
                {{ formatSkills(project?.category) }}
              </span>
          <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
            {{ formatSkills(project?.skills) }}
          </span>
        </div>
      </section>

      <!-- Apply Button -->
      <div v-if="user.role == 'freelance'" class="flex justify-center">
        <button
          @click="applyForProject(project)"
          class="w-full md:w-56 lg:w-64 bg-[#334EAC] text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition"
        >
          Apply Now
        </button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { defineProps, defineEmits } from 'vue';
import { formatDate } from '../../utils/datetimeUtils';
import { formatSkills } from '../../utils/stringUtils';
import { formatCurrency } from '../../utils/numberUtils';
import { usePage } from '@inertiajs/vue3'

const { user } = usePage().props

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

/**
 * Emits a 'close' event to the parent to hide the modal.
 */
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