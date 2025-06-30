<template>
  <div v-if="show" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div
      class="bg-white max-w-2xl w-full p-6 rounded-xl shadow-xl animate-fade-in-scale relative overflow-y-auto max-h-[90vh]"
    >
      <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">
        &times;
      </button>
      <h2 class="text-2xl font-bold text-gray-900 mb-4">
        {{ project?.title || 'Project Details' }}
      </h2>
      <p class="text-gray-700 mb-4">
        {{ project?.description || 'No description available.' }}
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800 mb-4">
        <div class="flex items-center">
          <i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>
          <strong>Budget:</strong>
          <span class="ml-2">{{ formatCurrency(project?.budget) }}</span>
        </div>
        <div class="flex items-center">
          <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
          <strong>Deadline:</strong>
          <span class="ml-2">{{ formatDate(project?.deadline) }}</span>
        </div>
        <div class="flex items-center col-span-full">
          <i class="far fa-calendar-check text-gray-500 mr-2"></i>
          <strong>Start Date:</strong>
          <span class="ml-2">{{ formatDate(project?.start_date) }}</span>
        </div>
        <div class="flex items-center col-span-full">
          <i class="fas fa-user-circle text-gray-500 mr-2"></i>
          <strong>Client:</strong>
          <span class="ml-2">{{ project?.user?.email || 'Unknown Client' }}</span>
        </div>
      </div>

      <div class="mb-4">
        <h3 class="text-sm font-semibold mb-2">Required Skills:</h3>
        <div class="flex flex-wrap gap-2">
          <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
            {{ formatSkills(project?.skills) }}
          </span>
        </div>
      </div>

      <button
        @click="applyForProject(project)"
        class="w-full bg-[#334EAC] text-white py-3 rounded-lg font-semibold hover:bg-blue-700"
      >
        Apply Now
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { formatDate } from '../../utils/datetimeUtils';
import { formatSkills } from '../../utils/stringUtils';
import { formatCurrency } from '../../utils/numberUtils';

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