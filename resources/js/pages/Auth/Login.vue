<template>
    <div
        class="h-screen overflow-y-auto bg-gradient-to-br from-blue-50 to-indigo-50 px-4 py-6"
    >
        <!-- Back to Landing Page -->
        <div class="mb-8">
            <backBtn />
        </div>

        <!-- Login Card -->
        <div
            class="w-full max-w-sm mx-auto bg-white rounded-xl shadow-lg h-fit px-8 py-3"
        >
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
                <p class="text-sm text-gray-600">Login your account</p>
            </div>

            <!-- Success Message -->
            <div
                v-if="$page.props.flash.success"
                class="bg-green-50 border-l-4 border-green-500 p-3 mb-4 rounded-md"
            >
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <p class="ml-2 text-sm text-green-700">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
            </div>

            <!-- Error Message -->
            <div
                v-if="$page.props.flash.error"
                class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 rounded-md"
            >
                <div class="flex items-start">
                    <i
                        class="fas fa-exclamation-circle text-red-500 mt-0.5"
                    ></i>
                    <p class="ml-2 text-sm text-red-700">
                        {{ $page.props.flash.error }}
                    </p>
                </div>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Email Field -->
                <input
                    v-model="form.id"
                    type="hidden"
                    id="google_id"
                    autocomplete="username"
                    placeholder="Google ID"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                />
                <div>
                    <label
                        for="email"
                        class="block text-xs font-medium text-gray-700 mb-1"
                    >
                        Email
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                        >
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            required
                            autocomplete="email"
                            placeholder="Email:"
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                        />
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label
                        for="password"
                        class="block text-xs font-medium text-gray-700 mb-1"
                    >
                        Password
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                        >
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <input
                            v-model="form.password"
                            type="password"
                            id="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password:"
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-white"
                        />
                    </div>
                </div>

                <!-- Remember Me + Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-700"
                            >Remember me</span
                        >
                    </label>

                    <a
                        href="#"
                        class="text-sm text-blue-600 hover:text-blue-500 hover:underline"
                    >
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="cursor-pointer w-full flex justify-center py-2 px-4 rounded-lg text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Sign in
                </button>

                <!-- or divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-500">OR</span>
                    </div>
                </div>

                <!-- Google Sign In -->
                <a
                    :href="route('auth.google', { type: 'login' })"
                    class="w-full flex items-center justify-center py-2 px-4 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 shadow-sm transition duration-150 ease-in-out"
                >
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
            </form>

            <!-- Sign Up -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don’t have an account?
                    <Link
                        :href="route('register')"
                        class="font-medium text-blue-600 hover:text-blue-500 hover:underline"
                    >
                        Sign up
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import backBtn from "@/components/UI/BackLink.vue";

const logoFilename = "N.png";
const logoUrl = `/storage/logo/${logoFilename}`;

const params = new URLSearchParams(window.location.search);
const googleEmail = params.get("email");
const googleId = params.get("google_id");

const form = useForm({
    id: googleId,
    email: googleEmail,
    password: "",
});

function submit() {
    form.post(route("users.authenticate"));
}
</script>
