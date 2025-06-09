<template>
    <div class="min-h-screen bg-gray-50">
        <div class="absolute top-0 left-0 w-full">
            <Navigational />
        </div>
        
        <!-- Hero Section -->
        <div class="pt-24 px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Find Your Perfect Match</h1>
                <p class="text-xl text-gray-600">Connect with top freelancers and businesses worldwide</p>
            </div>

            <!-- Image Grid -->
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4 sm:px-6 lg:px-8 mb-16">
                <!-- Connect Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative">
                        <img :src="getImageUrl('/images/connect.png')" alt="Connect with Freelancers" class="w-full h-48 object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Connect</h3>
                        <p class="text-gray-600">Find and connect with talented freelancers from around the world</p>
                    </div>
                </div>

                <!-- Developer Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative">
                        <img :src="getImageUrl('/images/developer.png')" alt="Skilled Developers" class="w-full h-48 object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Developers</h3>
                        <p class="text-gray-600">Access a pool of skilled developers ready to bring your projects to life</p>
                    </div>
                </div>

                <!-- Employer Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative">
                        <img :src="getImageUrl('/images/employer.png')" alt="For Employers" class="w-full h-48 object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Employers</h3>
                        <p class="text-gray-600">Post projects and find the perfect talent for your business needs</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import Navigational from '../components/NavGuest.vue';

const getImageUrl = (path) => {
    // Using the root path since images are in public directory
    return path;
}
</script>

<style scoped>
.site-image {
    max-width: 100%;
    height: auto;
}
</style>



