<template>
    <MainLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Projects</h1>
            <p class="text-gray-600 mb-8">Explore available projects and find your next opportunity.</p>
            
            <div v-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="project in projects" :key="project.id" 
                    class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-building text-gray-500 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ project.user.email }}</p>
                            <p class="text-xs text-gray-500">Posted {{ formatDate(project.created_at) }}</p>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-2">{{ project.title }}</h2>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span v-if="project.category" 
                            class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            {{ formatCategory(project.category) }}
                        </span>
                        <span v-if="project.skills" 
                            class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            {{ formatSkills(project.skills) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ project.description }}
                    </p>

                    <div class="grid grid-cols-2 text-sm text-gray-700 gap-2 mb-4 pt-4 border-t border-gray-100">
                        <div><strong>Budget:</strong> <span class="font-semibold text-gray-900">{{ formatCurrency(project.budget) }}</span></div>
                        <div><strong>Deadline:</strong> <span class="font-semibold text-gray-900">{{ formatDate(project.deadline) }}</span></div>
                    </div>

                    <button class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 
                        transition-colors duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Apply Now
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

function formatDate(date) {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatCurrency(amount) {
    if (amount === null || typeof amount === 'undefined' || isNaN(amount)) {
        return 'N/A';
    }
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatCategory(category) {
    const categoryMap = {
        'web-dev': 'Web Development',
        'mobile-dev': 'Mobile Development',
        'software-dev': 'Software Development',
        'game-dev': 'Game Development',
        'data-science': 'Data Science',
        'ai-ml': 'AI / Machine Learning',
        'cybersecurity': 'Cybersecurity',
        'devops': 'DevOps',
        'cloud-computing': 'Cloud Computing',
        'blockchain': 'Blockchain',
        'design': 'UI/UX Design',
        'marketing': 'Marketing',
        'writing': 'Content Writing'
    };
    return categoryMap[category] || category.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

function formatSkills(skills) {
    if (!skills) return '';
    return skills.split(',').map(skill => skill.trim()).filter(skill => skill).join(', ').toUpperCase();
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

