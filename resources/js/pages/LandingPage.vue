<template>
    <div class="h-screen overflow-y-scroll">
        <div class="absolute top-0 left-0 w-full z-10">
            <Navigational />
        </div>

        <!-- Hero Section -->
        <div class="pt-36 px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1
                    class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4 edu-sa-hand-medium"
                >
                    Find Your Perfect Match
                </h1>
                <p class="text-xl text-gray-500">
                    Connect with top freelancers and businesses worldwide
                </p>
            </div>

            <!-- Image Grid -->
            <div
                class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 px-4 sm:px-6 lg:px-8 mb-16"
            >
                <!-- Developer Card -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer"
                >
                    <div class="relative">
                        <img
                            :src="getImageUrl('/images/developer.png')"
                            alt="Skilled Developers"
                            class="w-full h-48 object-center object-cover"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                        ></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Freelancer
                        </h3>
                        <p class="text-gray-600">
                            Access a pool of skilled developers ready to bring
                            your projects to life
                        </p>
                    </div>
                </div>

                <!-- Employer Card -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer"
                >
                    <div class="relative">
                        <img
                            :src="getImageUrl('/images/employer.png')"
                            alt="For Employers"
                            class="w-full h-48 object-center object-cover"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                        ></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Employer
                        </h3>
                        <p class="text-gray-500">
                            Post projects and find the perfect talent for your
                            business needs
                        </p>
                    </div>
                </div>

                <!-- Connect Card -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer"
                >
                    <div class="relative">
                        <img
                            :src="getImageUrl('/images/connect.png')"
                            alt="Connect with Freelancers"
                            class="w-full h-48 object-center object-cover"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                        ></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Connect
                        </h3>
                        <p class="text-gray-500">
                            Find and connect with talented freelancers from
                            around the world
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Why Choose Us
                    </h2>
                    <p class="text-xl text-gray-500">
                        Everything you need to succeed in your freelance journey
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 text-center">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Easy to Use</h3>
                        <p class="text-gray-600">
                            Simple and intuitive platform designed for both
                            freelancers and employers
                        </p>
                    </div>
                    <div class="p-6 text-center">
                        <div
                            class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">
                            Secure Platform
                        </h3>
                        <p class="text-gray-600">
                            Your data and transactions are protected with
                            industry-leading security
                        </p>
                    </div>
                    <div class="p-6 text-center">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">
                            Global Network
                        </h3>
                        <p class="text-gray-600">
                            Connect with talented professionals from around the
                            world
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        What Our Users Say
                    </h2>
                    <p class="text-xl text-gray-500">
                        Success stories from our community
                    </p>
                </div>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <img
                                src="https://ui-avatars.com/api/?name=John+Doe&background=random"
                                alt="John Doe"
                                class="w-12 h-12 rounded-full"
                            />
                            <div class="ml-4">
                                <h4 class="font-semibold">John Doe</h4>
                                <p class="text-gray-500">Freelancer</p>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            "This platform has transformed my freelance career.
                            I've found amazing clients and projects that match
                            my skills perfectly."
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <img
                                src="https://ui-avatars.com/api/?name=Sarah+Smith&background=random"
                                alt="Sarah Smith"
                                class="w-12 h-12 rounded-full"
                            />
                            <div class="ml-4">
                                <h4 class="font-semibold">Sarah Smith</h4>
                                <p class="text-gray-500">Employer</p>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            "Finding talented developers has never been easier.
                            The quality of freelancers on this platform is
                            outstanding."
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <img
                                src="https://ui-avatars.com/api/?name=Mike+Johnson&background=random"
                                alt="Mike Johnson"
                                class="w-12 h-12 rounded-full"
                            />
                            <div class="ml-4">
                                <h4 class="font-semibold">Mike Johnson</h4>
                                <p class="text-gray-500">Freelancer</p>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            "The platform's user-friendly interface and secure
                            payment system make it my go-to place for finding
                            new projects."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">
                            10K+
                        </div>
                        <p class="text-gray-600">Active Freelancers</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">
                            5K+
                        </div>
                        <p class="text-gray-600">Active Employers</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">
                            15K+
                        </div>
                        <p class="text-gray-600">Projects Completed</p>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">
                            98%
                        </div>
                        <p class="text-gray-600">Satisfaction Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        How It Works
                    </h2>
                    <p class="text-xl text-gray-500">
                        Get started in three simple steps
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="relative">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4"
                            >
                                <span class="text-blue-600 font-bold text-xl"
                                    >1</span
                                >
                            </div>
                            <h3 class="text-xl font-semibold mb-2">
                                Create Your Profile
                            </h3>
                            <p class="text-gray-600">
                                Sign up and create your professional profile
                                highlighting your skills and experience
                            </p>
                        </div>
                        <div
                            class="hidden md:block absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2"
                        >
                            <svg
                                class="w-8 h-8 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4"
                            >
                                <span class="text-blue-600 font-bold text-xl"
                                    >2</span
                                >
                            </div>
                            <h3 class="text-xl font-semibold mb-2">
                                Browse Opportunities
                            </h3>
                            <p class="text-gray-600">
                                Explore projects or post your own to find the
                                perfect match
                            </p>
                        </div>
                        <div
                            class="hidden md:block absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2"
                        >
                            <svg
                                class="w-8 h-8 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="p-6 rounded-xl shadow-lg bg-white">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4"
                            >
                                <span class="text-blue-600 font-bold text-xl"
                                    >3</span
                                >
                            </div>
                            <h3 class="text-xl font-semibold mb-2">
                                Start Working
                            </h3>
                            <p class="text-gray-600">
                                Connect with clients or freelancers and begin
                                your collaboration
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20 bg-blue-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    Ready to Get Started?
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    Join our community of talented professionals and businesses
                    today
                </p>
                <div class="flex justify-center space-x-4">
                    <Link
                        :href="route('register')"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300"
                    >
                        Sign Up as Freelancer
                    </Link>
                    <Link
                        :href="route('register')"
                        class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300"
                    >
                        Hire Talent
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navigational from "@/components/Shared/Navigational.vue";

const getImageUrl = (path) => {
    return path;
};
</script>
