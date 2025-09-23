<template>
    <MainLayout>
        <div class="h-screen overflow-y-auto px-20">
            <button
                v-show="authId !== userProfile.user_id"
                onclick="history.back()"
                aria-label="Go back"
                class="flex items-center gap-2 px-4 py-2 my-3 cursor-pointer rounded-lg shadow-sm text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition-colors duration-200"
            >
                <i class="fas fa-long-arrow-alt-left"></i>
                <span class="font-medium text-sm">Back</span>
            </button>

            <div
                class="h-auto rounded-sm border-l border-r border-gray-300 pb-30 pt-15 bg-white"
            >
                <!-- Profile Card -->
                <div
                    class="max-w-6xl mx-auto bg-[#234C6A] rounded-lg shadow-sm border border-gray-200 p-6 mb-8"
                >
                    <div
                        class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-2"
                    >
                        <!-- Profile picture container -->
                        <div class="relative h-32 w-32">
                            <!-- View mode -->
                            <div v-if="!isEditing" class="w-full h-full p-3">
                                <img
                                    :src="userProfile.profile_picture"
                                    alt="Profile Picture"
                                    class="w-full h-full rounded-lg object-cover border-1 border-gray-300"
                                />
                            </div>

                            <!-- Editing mode -->
                            <div v-else class="w-full h-full p-3">
                                <label
                                    for="profile-upload"
                                    class="cursor-pointer w-full h-full flex items-center justify-center rounded-lg border-1 border-gray-300 overflow-hidden group relative"
                                >
                                    <!-- Preview if available -->
                                    <img
                                        v-if="
                                            previewImage ||
                                            userProfile.profile_picture
                                        "
                                        :src="
                                            previewImage ||
                                            userProfile.profile_picture
                                        "
                                        alt="Profile Preview"
                                        class="w-full h-full rounded-lg object-cover transition-transform duration-200 group-hover:scale-105"
                                    />

                                    <!-- Default icon -->
                                    <i
                                        v-else
                                        class="fas fa-camera text-blue-500 text-2xl group-hover:text-blue-600 transition-colors"
                                    ></i>

                                    <!-- Overlay on hover -->
                                    <div
                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <i
                                            class="fas fa-camera text-white text-xl"
                                        ></i>
                                    </div>
                                </label>

                                <!-- File input -->
                                <input
                                    type="file"
                                    id="profile-upload"
                                    class="hidden"
                                    accept="image/*"
                                    @change="handleFileChange"
                                />
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-grow text-center sm:text-left sm:pt-4">
                            <div v-if="!isEditing">
                                <h2
                                    class="text-3xl font-extrabold text-white name"
                                >
                                    {{ userProfile.username }}
                                </h2>
                                <p class="text-md !text-white mt-1 label">
                                    {{ userProfile.occupation }}
                                </p>
                                <p class="text-xs !text-gray-300 mt-2 label">
                                    📍{{ userProfile.address }}
                                </p>
                            </div>

                            <div v-else>
                                <h2
                                    class="text-3xl font-extrabold text-gray-800 mb-2"
                                >
                                    {{ userProfile.email }}
                                </h2>
                                <input
                                    v-model="form.occupation"
                                    class="label text-sm !text-gray-300 border rounded p-2 w-full mb-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Occupation"
                                />
                                <input
                                    v-model="form.address"
                                    class="label text-sm !text-gray-300 border rounded p-2 w-full mb-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Address"
                                />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex-shrink-0 flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0"
                        >
                            <MessageModal
                                v-show="authId !== userProfile.user_id"
                                :username="userProfile.username"
                                :id="userProfile.user_id"
                                :profile_picture="userProfile.profile_picture"
                            />
                            <button
                                v-show="authId === userProfile.user_id"
                                v-if="!isEditing"
                                @click="editMode"
                                class="flex items-center gap-2 cursor-pointer px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                            >
                                <i class="fas fa-edit"></i>
                                <span>Edit Profile</span>
                            </button>
                            <template v-else>
                                <!-- Save Button -->
                                <button
                                    @click="saveProfile"
                                    class="ms-10 flex items-center gap-2 cursor-pointer px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Save</span>
                                </button>

                                <!-- Cancel Button -->
                                <button
                                    @click="cancelEdit"
                                    class="flex items-center gap-2 cursor-pointer px-5 py-2 border border-gray-400 text-sm font-medium rounded-lg bg-red-500 hover:bg-red-400 transition-colors shadow-sm !text-white"
                                >
                                    <i class="fas fa-times"></i>
                                    <span>Cancel</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div
                    class="max-w-6xl mx-auto bg-[#FBFBFB] rounded-sm shadow-sm border border-gray-200 p-6 mb-8"
                >
                    <h2 class="text-1xl font-medium !text-gray-600 mb-2 label">
                        About
                    </h2>
                    <p
                        v-if="!isEditing"
                        class="text-gray-700 leading-relaxed text-sm"
                    >
                        {{ userProfile.about }}
                    </p>
                    <textarea
                        v-else
                        v-model="form.about"
                        class="label text-sm !text-gray-600 border rounded p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        rows="1"
                    ></textarea>
                </div>

                <!-- Skills Section -->
                <div
                    class="max-w-6xl mx-auto bg-[#FBFBFB] rounded-sm shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-1xl font-medium !text-gray-600 mb-2 label">
                        Skills
                    </h2>

                    <div v-if="!isEditing" class="flex flex-wrap gap-2">
                        <span
                            v-for="skill in userProfile.skills"
                            :key="skill"
                            class="px-4 py-1 border border-green-500 bg-green-200 text-green-700 text-sm rounded-full font-medium"
                        >
                            {{ skill }}
                        </span>
                    </div>

                    <div v-else class="space-y-3">
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="(skill, index) in userProfile.skills"
                                :key="index"
                                class="flex items-center gap-1 px-4 py-1 border border-green-500 bg-green-200 text-green-700 text-sm rounded-full font-medium"
                            >
                                {{ skill }}
                                <button
                                    @click="removeSkill(index)"
                                    class="text-red-500 hover:text-red-700 cursor-pointer"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <input
                                v-model="newSkill"
                                @keyup.enter="addSkill"
                                placeholder="Enter your skill"
                                class="border text-sm rounded p-2 flex-grow focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <button
                                @click="addSkill"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Closing tag added for the outermost div -->
    </MainLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import MessageModal from "@/components/Modal/Shared/MessageModal.vue";

// get user profile data from the server
const { profile } = usePage().props;
const page = usePage();

// get authenticated user id
const authId = page.props.user.id;

const isEditing = ref(false);
const newSkill = ref("");
const previewImage = ref(null);

// Initialize user profile data
const userProfile = ref({
    user_id: profile.user_id,
    username: profile.user.username,
    about: profile.about,
    occupation: profile.occupation,
    address: profile.address,
    profile_picture:
        profile.profile_picture ??
        "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png",
    skills: profile.skills ?? [],
});

// Form state
const form = useForm({
    _method: "put",
    about: null,
    occupation: null,
    address: null,
    profile_picture: null,
    skills: [],
});

// Handle profile picture change
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);

        form.profile_picture = file;
    }
};

// Save profile changes
function saveProfile() {
    form.post(route("userProfile.update"), {
        onSuccess: () => {
            form.reset();
            window.location.reload();
        },
        onError: (errors) => {
            console.log("Form update error:", errors);
        },
    });
    isEditing.value = false;
}

// Enter edit mode and pre-fill form fields with current profile data
function editMode() {
    form.about = userProfile.value.about;
    form.occupation = userProfile.value.occupation;
    form.address = userProfile.value.address;
    form.email = userProfile.value.email;
    form.profile_picture = null;
    form.skills = userProfile.value.skills;
    isEditing.value = true;
}

// Add a new skill to the skills array
function addSkill() {
    const skill = newSkill.value.trim();
    if (skill && !userProfile.value.skills.includes(skill)) {
        userProfile.value.skills.push(skill);
        newSkill.value = "";
    }
}

// Remove a skill from the skills array by index
function removeSkill(index) {
    userProfile.value.skills.splice(index, 1);
}

// Cancel editing and revert changes
function cancelEdit() {
    userProfile.value = { ...userProfile.value };
    isEditing.value = false;
}
</script>
