<template>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center">
            User Profile
        </h1>

        <!-- Profile Card -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="flex-shrink-0">
                    <img
                        :src="userProfile.photoUrl"
                        alt="Profile Picture"
                        class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-gray-200 shadow-md"
                    >
                </div>

                <div class="flex-grow text-center sm:text-left">
                    <div v-if="!isEditing">
                        <h2 class="text-3xl font-extrabold text-gray-800">{{ userProfile.email }}</h2>
                        <p class="text-xl text-gray-600 mt-1">{{ userProfile.occupation }}</p>
                        <p class="text-md text-gray-500 mt-2">
                            📍 {{ userProfile.address }}
                        </p>
                    </div>

                    <div v-else>
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">{{ userProfile.email }}</h2>
                        <input v-model="form.occupation" class="border rounded p-2 w-full mb-2" placeholder="occupation" />
                        <input v-model="form.address" class="border rounded p-2 w-full mb-2" placeholder="address" />
                    </div>
                </div>

                <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
                    <button v-if="!isEditing"
                        @click="editMode"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                        Edit Profile
                    </button>

                    <template v-else>
                        <button @click="saveProfile"
                            class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md">
                            Save
                        </button>
                        <button @click="cancelEdit"
                            class="px-5 py-2 border border-gray-400 text-gray-700 text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                            Cancel
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">About</h2>
            <p v-if="!isEditing" class="text-gray-700 leading-relaxed">{{ userProfile.about }}</p>
            <textarea v-else v-model="form.about" class="border rounded p-2 w-full" rows="4"></textarea>
        </div>

        <!-- Skills Section -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Skills</h2>

            <div v-if="!isEditing" class="flex flex-wrap gap-2">
                <span
                    v-for="skill in userProfile.skills"
                    :key="skill"
                    class="px-4 py-1 bg-gray-200 text-gray-700 text-sm rounded-full font-medium"
                >
                    {{ skill }}
                </span>
            </div>

            <div v-else class="space-y-3">
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="(skill, index) in userProfile.skills"
                        :key="index"
                        class="flex items-center gap-1 px-4 py-1 bg-gray-200 text-gray-700 text-sm rounded-full font-medium"
                    >
                        {{ skill }}
                        <button @click="removeSkill(index)" class="text-red-500 hover:text-red-700">×</button>
                    </span>
                </div>
                <div class="flex gap-2">
                    <input
                        v-model="newSkill"
                        @keyup.enter="addSkill"
                        placeholder="Add a skill and press Enter"
                        class="border rounded p-2 flex-grow"
                    />
                    <button @click="addSkill" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const { profile } = usePage().props;
const isEditing = ref(false);
const newSkill = ref('');

const userProfile = ref({
  email: profile.user?.email,
  about: profile.about,
  occupation: profile.occupation,
  address: profile.address,
  photoUrl: profile.profile_picture ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?...',
  skills: profile.skills
});

const form = useForm({
  about: null,
  occupation: null,
  address: null,
  photoUrl: null,
  skills: []
})

function editMode (){
     // Fill form with current values
  form.about = userProfile.value.about
  form.occupation = userProfile.value.occupation
  form.address = userProfile.value.address
  form.email = userProfile.value.email
  form.photoUrl = userProfile.value.photoUrl
  form.summary = userProfile.value.summary
  form.skills = userProfile.value.skills
  isEditing.value = true
}

function addSkill() {
    if (newSkill.value.trim() && !userProfile.value.skills.includes(newSkill.value.trim())) {
        userProfile.value.skills.push(newSkill.value.trim());
        newSkill.value = '';
        console.log("form value:", form.skills);
    }
}

function removeSkill(index) {
    userProfile.value.skills.splice(index, 1);
}

function saveProfile() {
    // Update userProfile with form values
    form.put(route('userProfile.update'), {
      onSuccess: () => {
        form.reset();
        window.location.reload();
      },
      onError: (errors) => {
        console.log('Form update error:', errors);
      },
    });
    isEditing.value = false;
}

function cancelEdit() {
    userProfile.value = { ...userProfile.value };
    isEditing.value = false;
}

</script>
