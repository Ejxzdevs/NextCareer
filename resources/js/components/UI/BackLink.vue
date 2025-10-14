<template>
    <div>
        <button
            @click="goBack"
            class="flex items-center gap-2 px-3 py-1 border border-gray-300 rounded-md shadow-sm text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200 text-sm sm:text-base cursor-pointer"
            aria-label="Go back"
        >
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </button>
    </div>
</template>

<script setup>
function goBack() {
    window.history.back();
}
</script>
