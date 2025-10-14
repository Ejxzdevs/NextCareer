<template>
    <div
        class="h-screen overflow-y-auto bg-gradient-to-br from-blue-50 to-indigo-50 px-4 py-6"
    >
        <!-- Back to Landing Page -->
        <div class="mb-8">
            <backBtn />
        </div>

        <div class="w-full max-w-sm mx-auto">
            <!-- Register Form Card -->
            <div class="bg-white rounded-xl shadow-lg h-fit px-8 py-3">
                <!-- Header -->
                <div class="flex flex-col items-center justify-center mb-8">
                    <Link :href="route('landing.page')" class="cursor-pointer"
                        ><img
                            :src="logoUrl"
                            alt="NextCareer Logo"
                            class="w-16 h-16"
                    /></Link>
                    <h1 class="text-2xl sm:text-3xl font-bold text-center">
                        <span class="text-[#0C162C]">Next</span
                        ><span class="text-[#5E17EB]">Career</span>
                    </h1>
                    <p class="text-sm text-gray-600">Create your account</p>
                </div>
                <form @submit.prevent="submit">
                    <!-- Google ID -->
                    <div class="mb-2 hidden">
                        <label
                            for="username"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >GOOGLE ID</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                            ></i>
                            <input
                                v-model="form.google_id"
                                type="hidden"
                                id="google_id"
                                autocomplete="google_id"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                                placeholder="google id"
                            />
                        </div>
                    </div>

                    <!-- Username Field -->
                    <div class="mb-2">
                        <label
                            for="username"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >Username</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                            ></i>
                            <input
                                v-model="form.username"
                                type="text"
                                id="username"
                                autocomplete="username"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                                placeholder="Username:"
                                required
                                :class="{
                                    'border-red-500': form.errors.username,
                                }"
                            />
                            <p
                                v-if="$page.props.errors.username"
                                class="text-red-600 text-xs"
                            >
                                {{ form.errors.username }}
                            </p>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-2">
                        <label
                            for="email"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >Email Address</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                            ></i>
                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                autocomplete="email"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                                placeholder="Email:"
                                required
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <p
                                v-if="form.errors.email"
                                class="text-red-600 text-xs mt-1"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div v-show="!googleId" class="mb-2">
                        <label
                            for="password"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >Password</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                            ></i>
                            <input
                                v-model="form.password"
                                type="password"
                                id="password"
                                autocomplete="new-password"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                                placeholder="Password:"
                                :required="passwordRequired"
                                @input="form.errors.password = null"
                            />
                            <p
                                v-if="form.errors.password"
                                class="text-red-600 text-xs mt-1"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div v-show="!googleId" class="mb-2">
                        <label
                            for="retype_password"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >Confirm Password</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                            ></i>
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                id="retype_password"
                                autocomplete="new-password"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                                placeholder="Confirm Password:"
                                :required="passwordRequired"
                            />
                        </div>
                        <p
                            v-if="passwordMismatch"
                            class="mt-1 text-xs text-red-600"
                        >
                            <i class="fas fa-exclamation-circle mr-1"></i
                            >Passwords do not match
                        </p>
                    </div>

                    <!-- Account Type Selection -->
                    <div class="mb-3">
                        <label
                            for="account_type"
                            class="block text-xs font-medium text-gray-700 mb-1"
                            >I want to join as</label
                        >
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Freelancer -->
                            <div
                                :class="[
                                    'relative rounded-lg border p-3 cursor-pointer transition-all duration-200',
                                    form.account_type === 'freelance'
                                        ? 'border-blue-500 bg-blue-50'
                                        : 'border-gray-300 hover:border-blue-300',
                                ]"
                                @click="form.account_type = 'freelance'"
                            >
                                <input
                                    type="radio"
                                    v-model="form.account_type"
                                    value="freelance"
                                    class="sr-only"
                                />
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-user-tie text-gray-600 text-sm"
                                    ></i>
                                    <div class="ml-2">
                                        <p
                                            class="text-xs font-medium text-gray-900"
                                        >
                                            Freelancer
                                        </p>
                                        <p class="text-[11px] text-gray-500">
                                            Find work
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Employer -->
                            <div
                                :class="[
                                    'relative rounded-lg border p-3 cursor-pointer transition-all duration-200',
                                    form.account_type === 'employer'
                                        ? 'border-blue-500 bg-blue-50'
                                        : 'border-gray-300 hover:border-blue-300',
                                ]"
                                @click="form.account_type = 'employer'"
                            >
                                <input
                                    type="radio"
                                    v-model="form.account_type"
                                    value="employer"
                                    class="sr-only"
                                />
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-building text-gray-600 text-sm"
                                    ></i>
                                    <div class="ml-2">
                                        <p
                                            class="text-xs font-medium text-gray-900"
                                        >
                                            Employer
                                        </p>
                                        <p class="text-[11px] text-gray-500">
                                            Hire talent
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="cursor-pointer w-full flex justify-center py-2 px-4 rounded-lg text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="passwordRequired && passwordMismatch"
                    >
                        Create Account
                    </button>
                </form>

                <!-- OR Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-500">OR</span>
                    </div>
                </div>

                <!-- Google Button -->
                <a
                    :href="route('auth.google', { type: 'register' })"
                    class="w-full flex items-center justify-center py-2 px-4 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 shadow-sm transition duration-150 ease-in-out"
                >
                    <!-- Google SVG Logo -->
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 533.5 544.3">
                        <path
                            fill="#4285F4"
                            d="M533.5 278.4c0-17.5-1.6-34.4-4.6-50.8H272v95.9h146.9c-6.3 34-25.4 62.8-54.2 82.1v68.3h87.5c51.3-47.2 81.3-116.7 81.3-195.5z"
                        />
                        <path
                            fill="#34A853"
                            d="M272 544.3c73.6 0 135.4-24.4 180.5-66.3l-87.5-68.3c-24.3 16.3-55.2 25.8-93 25.8-71.5 0-132-48.2-153.6-112.8H31.3v70.9C76.1 476.3 168 544.3 272 544.3z"
                        />
                        <path
                            fill="#FBBC05"
                            d="M118.4 323.5c-5.8-17.5-9.2-36.1-9.2-55.5s3.4-38 9.2-55.5V141.6H31.3C11.3 180.4 0 223.3 0 267.9s11.3 87.5 31.3 126.3l87.1-70.7z"
                        />
                        <path
                            fill="#EA4335"
                            d="M272 107.5c39.9 0 75.6 13.7 103.8 40.5l77.8-77.8C403.5 24.6 341.7 0 272 0 168 0 76.1 68 31.3 141.6l87.1 70.9c21.6-64.6 82.1-112.8 153.6-112.8z"
                        />
                    </svg>
                    Continue with Google
                </a>

                <!-- Sign In Link -->
                <div class="mt-4 text-center">
                    <p class="text-xs sm:text-sm text-gray-600">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-medium text-blue-600 hover:text-blue-500"
                            >Sign in</Link
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import backBtn from "@/components/UI/BackLink.vue";

// Get Google prefilled data from query string
const params = new URLSearchParams(window.location.search);
const googleEmail = params.get("email");
const googleId = params.get("google_id");
const logoFilename = "N.png";
const logoUrl = `/storage/logo/${logoFilename}`;

const form = useForm({
    google_id: googleId,
    username: null,
    email: googleEmail,
    password: null,
    password_confirmation: null,
    account_type: "freelance",
});

// Password is required only if googleId is not provided
const passwordRequired = computed(() => !form.google_id);

// Password mismatch check
const passwordMismatch = computed(() => {
    if (!passwordRequired.value) return false;
    return (
        form.password &&
        form.password_confirmation &&
        form.password !== form.password_confirmation
    );
});

// Submit form
function submit() {
    if (passwordRequired.value && passwordMismatch.value) {
        return;
    }

    form.post(route("users.store"), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error("Error occurred:", errors);
        },
    });
}
</script>
