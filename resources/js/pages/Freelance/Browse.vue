<template>
    <MainLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Projects</h1>
            <p class="text-gray-600 mb-8">Explore available projects and find your next opportunity.</p>
            
            <div v-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="project in projects" :key="project.id" 
                    class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-building text-gray-500 text-xl"></i>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="far fa-bookmark text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span>{{ formatTimeAgo(project.created_at) }}</span>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ project.title }}</h2>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                            Contract
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                            Remote
                        </span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <span class="text-sm">{{ project.user.email }}</span>
                        </div>
                        <div class="font-semibold text-gray-900">
                            {{ formatCurrency(project.budget) }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            <span v-for="skill in formatSkillsArray(project.skills)" :key="skill"
                                class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded">
                                {{ skill }}
                            </span>
                        </div>
                    </div>

                    <button class="w-full bg-black text-white font-semibold py-3 px-4 rounded-lg hover:bg-gray-800 
                        transition-colors duration-200">
                        Apply now
                    </button>
                </div>
            </div>

            <div v-else class="text-center bg-white rounded-xl shadow-lg p-10 mt-10">
                <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                <p class="text-xl font-semibold text-gray-800 mb-2">No projects found</p>
                <p class="text-gray-600">Check back later for new opportunities.</p>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    projects: {
        type: Array,
        default: () => []
    }
});

function formatTimeAgo(date) {
    if (!date) return '';
    const now = new Date();
    const past = new Date(date);
    const diffTime = Math.abs(now - past);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return '1 day ago';
    if (diffDays < 30) return `${diffDays} days ago`;
    
    const diffMonths = Math.floor(diffDays / 30);
    if (diffMonths === 1) return '1 month ago';
    if (diffMonths < 12) return `${diffMonths} months ago`;
    
    const diffYears = Math.floor(diffDays / 365);
    if (diffYears === 1) return '1 year ago';
    return `${diffYears} years ago`;
}

function formatCurrency(amount) {
    if (amount === null || typeof amount === 'undefined' || isNaN(amount)) {
        return 'N/A';
    }
    return `$${Number(amount).toLocaleString()}/hr`;
}

function formatSkillsArray(skills) {
    if (!skills) return [];
    return skills.split(',')
        .map(skill => skill.trim())
        .filter(skill => skill)
        .map(skill => skill.toUpperCase());
}

onMounted(() => {
    console.log('Browse component mounted with projects:', props.projects);
});
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

