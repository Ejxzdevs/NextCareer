<template>
  <!-- Modal container -->
  <div v-if="show" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full p-6 rounded-xl shadow-xl animate-fade-in-scale relative">
      
      <!-- Close button -->
      <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">
        &times;
      </button>

      <!-- Modal Title -->
      <h2 class="text-xl font-bold text-center mb-4">
        Apply for <span class="text-[#334EAC]">{{ projectTitle }}</span>
      </h2>

      <!-- Application Form -->
      <form @submit.prevent="submitApplication" class="space-y-4">

        <!-- Hidden project ID input -->
        <input type="hidden" v-model="form.project_id" />

        <!-- Portfolio Link Field -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Portfolio Link</label>
          <input
            v-model="form.link_portfolio"
            type="url"
            required
            class="w-full p-3 border rounded-md"
            placeholder="https://yourportfolio.com"
          />
        </div>

        <!-- Resume Upload Field -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Upload Resume</label>
          <input
            type="file"
            @change="handleResumeUpload"
            accept=".pdf,.doc,.docx"
            required
            class="w-full p-3 border rounded-md"
          />
          <p v-if="form.errors.resume" class="text-red-500 text-sm mt-1">{{ form.errors.resume }}</p>
        </div>

        <!-- Message Textarea -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
          <textarea
            v-model="form.message"
            rows="4"
            class="w-full p-3 border rounded-md"
            placeholder="Why are you a good fit?"
          ></textarea>
          <p v-if="form.errors.message" class="text-red-500 text-sm mt-1">{{ form.errors.message }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-[#334EAC] text-white py-3 rounded-lg font-bold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ form.processing ? 'Submitting...' : 'Submit Application' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Props passed down from parent component
const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  selectedProject: {
    type: Object,
    default: null,
  },
});

// Emits events to parent
const emit = defineEmits(['close', 'submitted']);

// Reactive form object using Inertia's useForm helper
const form = useForm({
  project_id: props.selectedProject?.id || null,
  resume: null,
  message: '',
  link_portfolio: '',
});

// Dynamically compute the project title or show default text
const projectTitle = computed(() => props.selectedProject?.title || 'this Project');

// Watch for changes in selected project and update form accordingly
watch(() => props.selectedProject, (newVal) => {
  if (newVal && newVal.id) {
    form.project_id = newVal.id;
    form.clearErrors();
  } else {
    form.project_id = null;
  }
}, { immediate: true });

// Watch for modal visibility toggle
watch(() => props.show, (newVal) => {
  if (newVal) {
    form.project_id = props.selectedProject?.id || null;
    form.clearErrors();
  } else {
    console.log('ApplyFormModal: Modal is CLOSING.');
    form.reset();
    form.clearErrors();
    form.project_id = null;
  }
});

// Handle resume file selection
function handleResumeUpload(event) {
  form.resume = event.target.files[0];
}

// Emit close event to parent to hide the modal
function closeModal() {
  emit('close');
}

// Submit form via Inertia POST request
function submitApplication() {

  // Prevent submission if no project ID is available
  if (!form.project_id) {
    form.setError('project_id', 'The project ID is missing. Please try again.');
    return;
  }

  // Post data to backend route
  form.post(route('freelance.application'), {
    onSuccess: () => {
      console.log('Application submitted successfully!');
      closeModal();
      emit('submitted');
    },
    onError: (errors) => {
      console.error('Form submission failed:', errors);
    },
    preserveScroll: true, 
  });
}
</script>

<style scoped>
/* Animation for modal entrance */
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

/* Class to apply fade-in scale animation */
.animate-fade-in-scale {
  animation: fadeInScale 0.3s ease-out forwards;
}
</style>
