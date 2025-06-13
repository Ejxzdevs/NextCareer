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
            <!-- Logo or Brand -->
            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <form @submit.prevent="submit">
                    <!-- Error Message -->
                    <div v-if="$page.props.flash.error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ $page.props.flash.error }}</p>
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
                                autocomplete="current-password" 
                                class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out text-sm sm:text-base"
                                placeholder="Enter your password"
                                required
                            >
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                    >
                        Sign in
                    </button>

                    <div class="mt-4 text-center">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-500 hover:underline">Forgot password?</a>
                    </div>
                </form>

                <!-- Sign Up Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <Link :href="route('register')" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign up
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const form = useForm({
    email: null,
    password: null,
})

function submit() {
    form.post(route('users.authenticate'))
}
</script>

