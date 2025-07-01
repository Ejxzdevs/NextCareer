<template>
  <!-- Modal container -->
  <div v-if="show" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full p-6 rounded-sm shadow-xl animate-fade-in-scale relative">
      
      <!-- Close button -->
      <button @click="closeModal" class="absolute top-1 right-3 text-red-400 hover:text-red-700 text-2xl cursor-pointer">
        <i class="fas fa-times"></i>
      </button>

      <!-- Modal Title -->
      <h2 class="text-lg font-bold my-3">
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
          <!-- Styled label as file input -->
          <label
            for="resumeUpload"
            class="flex items-center justify-between w-full px-4 py-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition"
          >
            <span class="text-gray-600 truncate">
              {{ selectedFileName || 'Choose a file (PDF, DOC, DOCX)' }}
            </span>
            <i class="fas fa-upload text-gray-500"></i>
          </label>
          <!-- Actual hidden input -->
          <input
            id="resumeUpload"
            type="file"
            accept=".pdf,.doc,.docx"
            @change="handleResumeUpload"
            required
            class="hidden"
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
          {{ form.processing ? 'Submitting...' : 'Submit Now' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  show: Boolean,
  selectedProject: Object,
});

const emit = defineEmits(['close', 'submitted']);

// Reactive form using Inertia
const form = useForm({
  project_id: props.selectedProject?.id || null,
  resume: null,
  message: '',
  link_portfolio: '',
});

// Displayed file name
const selectedFileName = ref('');

// Computed project title
const projectTitle = computed(() => props.selectedProject?.title || 'this Project');

// Watch for project changes
watch(() => props.selectedProject, (newVal) => {
  form.project_id = newVal?.id || null;
  form.clearErrors();
}, { immediate: true });

// Reset form when modal closes
watch(() => props.show, (newVal) => {
  if (newVal) {
    form.project_id = props.selectedProject?.id || null;
    form.clearErrors();
  } else {
    form.reset();
    form.clearErrors();
    form.project_id = null;
    selectedFileName.value = '';
  }
});

// Handle file selection
function handleResumeUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.resume = file;
    selectedFileName.value = file.name;
  }
}

// Close modal
function closeModal() {
  emit('close');
}

// Submit application
function submitApplication() {
  if (!form.project_id) {
    form.setError('project_id', 'The project ID is missing. Please try again.');
    return;
  }

  form.post(route('freelance.application'), {
    onSuccess: () => {
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
