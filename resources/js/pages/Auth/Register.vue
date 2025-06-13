<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex flex-col justify-center items-center px-4 py-8 sm:py-12">
        <!-- Back to Landing Page -->
        <div class="absolute top-4 left-4">
            <a :href="route('landing.page')" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Next Career</span>
            </a>
        </div>

        <div class="w-full max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create Account</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">Join our community of professionals</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <form @submit.prevent="submit">
                    <!-- Success Message -->
                    <div v-if="$page.props.flash.success" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                name="email"
                                id="email"
                                autocomplete="username"
                                class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm sm:text-base"
                                placeholder="Enter your email"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input
                                v-model="form.password"
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm sm:text-base"
                                placeholder="Create a password"
                                required
                            >
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6">
                        <label for="retype_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                id="retype_password"
                                autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm sm:text-base"
                                placeholder="Confirm your password"
                                required
                                @focus="touchConfirmPassword"
                            >
                        </div>
                        <p v-if="passwordMismatch" class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Passwords do not match
                        </p>
                    </div>

                    <!-- Account Type Selection -->
                    <div class="mb-6">
                        <label for="account_type" class="block text-sm font-medium text-gray-700 mb-2">I want to join as</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div 
                                :class="[
                                    'relative rounded-lg border p-4 cursor-pointer transition-all duration-200',
                                    form.account_type === 'freelance' 
                                        ? 'border-blue-500 bg-blue-50' 
                                        : 'border-gray-300 hover:border-blue-300'
                                ]"
                                @click="form.account_type = 'freelance'"
                            >
                                <input
                                    type="radio"
                                    v-model="form.account_type"
                                    value="freelance"
                                    class="sr-only"
                                >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-tie text-lg text-gray-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Freelancer</p>
                                        <p class="text-xs text-gray-500">Find work</p>
                                    </div>
                                </div>
                            </div>
                            <div 
                                :class="[
                                    'relative rounded-lg border p-4 cursor-pointer transition-all duration-200',
                                    form.account_type === 'employer' 
                                        ? 'border-blue-500 bg-blue-50' 
                                        : 'border-gray-300 hover:border-blue-300'
                                ]"
                                @click="form.account_type = 'employer'"
                            >
                                <input
                                    type="radio"
                                    v-model="form.account_type"
                                    value="employer"
                                    class="sr-only"
                                >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-building text-lg text-gray-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Employer</p>
                                        <p class="text-xs text-gray-500">Hire talent</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="passwordMismatch"
                    >
                        Create Account
                    </button>
                </form>

                <!-- Sign In Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <Link :href="route('login')" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: null,
    password: null,
    password_confirmation: null,
    account_type: 'freelance',
});

const confirmPasswordTouched = ref(false);

const passwordMismatch = computed(() => {
    return confirmPasswordTouched.value && form.password !== form.password_confirmation;
});

function submit() {
    if (!passwordMismatch.value) {
        form.post(route('users.store'), {
            onSuccess: () => {
                form.reset();
            },
        });
    }
}

function touchConfirmPassword() {
    confirmPasswordTouched.value = true;
}
</script>