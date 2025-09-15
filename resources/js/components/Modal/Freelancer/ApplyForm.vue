<template>
    <!-- Modal container -->
    <div
        v-if="show"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 sm:p-6"
    >
        <div
            class="bg-white w-full sm:w-4/5 md:w-3/5 lg:w-[500px] h-auto px-4 sm:px-6 py-6 sm:py-10 rounded-sm shadow-xl animate-fade-in-scale relative"
        >
            <!-- Close button -->
            <button
                @click="closeModal"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-lg cursor-pointer"
            >
                <i class="fas fa-times"></i>
            </button>

            <!-- Modal Title -->
            <h1 class="text-center text-1xl sm:text-2xl font-bold mb-6">
                <span class="!text-blue-900">{{ projectTitle }}</span>
            </h1>

            <!-- Application Form -->
            <form @submit.prevent="submitApplication" class="space-y-4">
                <!-- Hidden project ID input -->
                <input type="hidden" v-model="form.project_id" />

                <!-- Portfolio Link Field -->
                <div>
                    <label
                        class="block font-medium label !text-gray-700 mb-1 text-sm sm:text-xs"
                        >Portfolio Link</label
                    >
                    <input
                        v-model="form.link_portfolio"
                        type="url"
                        required
                        class="w-full p-2 sm:p-3 border rounded-md focus:outline focus:outline-blue-500"
                        placeholder="https://yourportfolio.com"
                    />
                </div>

                <!-- Resume Upload Field -->
                <div>
                    <label
                        class="block font-medium label !text-gray-700 mb-1 text-sm sm:text-xs"
                        >Upload Resume</label
                    >
                    <label
                        for="resumeUpload"
                        class="flex items-center justify-between w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition"
                    >
                        <span class="text-gray-600 truncate">
                            {{
                                selectedFileName ||
                                "Choose a file (PDF, DOC, DOCX)"
                            }}
                        </span>
                        <i class="fas fa-upload text-gray-500"></i>
                    </label>
                    <input
                        id="resumeUpload"
                        type="file"
                        accept=".pdf,.doc,.docx"
                        @change="handleResumeUpload"
                        required
                        class="hidden"
                    />
                    <p
                        v-if="form.errors.resume"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.resume }}
                    </p>
                </div>

                <!-- Message Textarea -->
                <div>
                    <label
                        class="block label text-sm sm:text-xs font-medium !text-gray-700 mb-1"
                        >Message</label
                    >
                    <textarea
                        v-model="form.message"
                        rows="4"
                        class="w-full p-2 sm:p-3 border rounded-md focus:outline focus:outline-blue-500"
                        placeholder="Why are you a good fit?"
                    ></textarea>
                    <p
                        v-if="form.errors.message"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.message }}
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-500 text-white py-2 sm:py-3 rounded-lg font-bold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    {{ form.processing ? "Submitting..." : "Submit" }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    show: Boolean,
    selectedProject: Object,
});

const emit = defineEmits(["close", "submitted"]);

// Reactive form using Inertia
const form = useForm({
    project_id: props.selectedProject?.id || null,
    resume: null,
    message: "",
    link_portfolio: "",
});

// Displayed file name
const selectedFileName = ref("");

// Computed project title
const projectTitle = computed(
    () => props.selectedProject?.title || "this Project"
);

// Watch for project changes
watch(
    () => props.selectedProject,
    (newVal) => {
        form.project_id = newVal?.id || null;
        form.clearErrors();
    },
    { immediate: true }
);

// Reset form when modal closes
watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            form.project_id = props.selectedProject?.id || null;
            form.clearErrors();
        } else {
            form.reset();
            form.clearErrors();
            form.project_id = null;
            selectedFileName.value = "";
        }
    }
);

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
    emit("close");
}

// Submit application
function submitApplication() {
    if (!form.project_id) {
        form.setError(
            "project_id",
            "The project ID is missing. Please try again."
        );
        return;
    }

    form.post(route("freelance.application.store"), {
        onSuccess: () => {
            closeModal();
            emit("submitted");
        },
        onError: (errors) => {
            console.error("Form submission failed:", errors);
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
