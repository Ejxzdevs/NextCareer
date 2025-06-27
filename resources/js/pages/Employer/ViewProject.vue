<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>
        <div v-else-if="project" class="flex justify-center">
            <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 lg:p-10 border border-gray-100 flex flex-col relative group hover:shadow-xl transition-shadow duration-300 ease-in-out w-full max-w-4xl lg:max-w-5xl">
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl sm:text-3xl">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="ms-3 sm:ms-4">
                        <p class="text-lg sm:text-xl font-medium text-gray-800">{{ project.user?.name || 'Unknown User' }}</p>
                        <p class="text-sm sm:text-base text-gray-500">
                            Posted {{ formatTimeAgo(project.created_at) }}
                        </p>
                    </div>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                    {{ project.title }}
                </h2>

                <div class="flex flex-wrap gap-2 sm:gap-3 mb-6 sm:mb-8">
                    <span
                        v-if="project.category"
                        class="px-4 py-2 sm:px-5 sm:py-2 bg-blue-100 text-blue-800 text-sm sm:text-base font-medium rounded-full"
                    >
                        {{ project.category.replace(/-/g, ' ').replace(/\b\w/g, (s) => s.toUpperCase()) }}
                    </span>
                    <span
                        v-if="project.skills"
                        class="px-4 py-2 sm:px-5 sm:py-2 bg-green-100 text-green-800 text-sm sm:text-base font-medium rounded-full"
                    >
                        {{ formatSkills(project.skills) }}
                    </span>
                </div>

                <p class="text-base sm:text-lg lg:text-xl text-gray-600 mb-6 sm:mb-8 leading-relaxed">
                    {{ project.description }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 text-base sm:text-lg text-gray-700 gap-4 sm:gap-6 mb-6 sm:mb-8 mt-auto pt-6 sm:pt-8 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <strong class="mb-1 sm:mb-0">Budget: </strong>
                        <span class="font-semibold text-gray-900 text-lg sm:text-xl">{{
                            formatCurrency(project.budget)
                        }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <strong class="mb-1 sm:mb-0">Start Date: </strong>
                        <span class="font-semibold text-gray-900 text-lg sm:text-xl">{{
                            formatDate(project.start_date)
                        }}</span>
                    </div>
                    <div class="col-span-1 sm:col-span-2 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <strong class="mb-1 sm:mb-0">Deadline: </strong>
                        <span class="font-semibold text-gray-900 text-lg sm:text-xl">{{
                            formatDate(project.deadline)
                        }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-else class="flex justify-center items-center h-64 sm:h-96">
            <div class="text-gray-500 text-lg sm:text-xl">Loading project...</div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { formatTimeAgo, formatDate } from '../../utils/datetimeUtils';
import { formatSkills} from '../../utils/stringUtils';
import { formatCurrency } from '../../utils/numberUtils';

const props = defineProps({
    project: Object,
    error: String
})

const page = usePage()

// Computed properties for conditional rendering
const canEdit = computed(() => {
    return page.props.auth?.user?.account_type === 'employer' && 
           page.props.auth?.user?.id === props.project?.user_id
})

const canDelete = computed(() => {
    return page.props.auth?.user?.account_type === 'employer' && 
           page.props.auth?.user?.id === props.project?.user_id
})
</script>
