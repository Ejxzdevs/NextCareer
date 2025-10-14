<template>
    <div>
        <Navigational />
        <div>
            <slot></slot>
        </div>
    </div>
</template>
<script setup>
import Navigational from "@/components/Layout/Navigational.vue";
</script>
