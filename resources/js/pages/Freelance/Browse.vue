<template>
    <MainLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Projects</h1>
            <p class="text-gray-600 mb-8">Explore available projects and find your next opportunity.</p>

            <div v-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <article
                    v-for="project in projects"
                    :key="project.id"
                    class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 border border-gray-100"
                >
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-building text-gray-500 text-xl"></i>
                        </div>
                        <button
                            @click="toggleBookmark(project.id)"
                            aria-label="Bookmark this project"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <i class="far fa-bookmark text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span>{{ formatTimeAgo(project.created_at) }}</span>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-3">
                        {{ project.title }}
                    </h2>

                    <p v-if="project.description" class="text-gray-600 text-sm my-5 line-clamp-3">
                        {{ project.description }}
                    </p>

                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="px-3 py-1 bg-green-200 text-black text-sm font-medium rounded-md">
                            Contract
                        </span>
                        <span class="px-3 py-1 bg-green-200 text-black text-sm font-medium rounded-md">
                            Remote
                        </span>
                    </div>

                    <div class="flex items-center justify-between my-4">
                        <div class="text-gray-600 text-sm">
                            <i class="fas fa-user mr-2"></i>
                            <span>{{ project.user?.email || 'Unknown' }}</span>
                        </div>
                        <div class="font-semibold text-gray-900">
                            {{ formatCurrency(project.budget) }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="skill in formatSkillsArray(project.skills)"
                                :key="skill"
                                class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded"
                            >
                                {{ skill }}
                            </span>
                        </div>
                    </div>

                    <button
                        class="w-full bg-black text-white font-semibold my-3 py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors duration-200"
                        aria-label="Apply for this project"
                    >
                        Propose Now
                    </button>
                </article>
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

const props = defineProps({
    projects: {
        type: Array,
        default: () => [],
    },
});

function formatTimeAgo(date) {
    if (!date) return '';
    const now = new Date();
    const past = new Date(date);
    const seconds = Math.floor((now - past) / 1000);

    const intervals = [
        { label: 'year', seconds: 31536000 },
        { label: 'month', seconds: 2592000 },
        { label: 'day', seconds: 86400 },
        { label: 'hour', seconds: 3600 },
        { label: 'minute', seconds: 60 },
        { label: 'second', seconds: 1 },
    ];

    for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count >= 1) {
            return `${count} ${interval.label}${count > 1 ? 's' : ''} ago`;
        }
    }

    return 'Just now';
}

function formatCurrency(amount) {
    if (amount === null || typeof amount === 'undefined' || isNaN(amount)) {
        return 'N/A';
    }
    return `$${Number(amount).toLocaleString()}/hr`;
}

function formatSkillsArray(skills) {
    if (!skills) return [];
    return skills
        .split(',')
        .map((skill) => skill.trim())
        .filter((skill) => skill)
        .map((skill) => skill.toUpperCase());
}

function toggleBookmark(projectId) {
    // Placeholder for bookmark logic
    console.log('Toggling bookmark for project:', projectId);
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
