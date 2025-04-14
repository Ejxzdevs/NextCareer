<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.user
</script>

<template>
  <div>
    <h1>Welcome back, {{ user.email }}</h1>
    <p>User ID: {{ user.id }}</p>
    <p>Role: {{ user.role }}</p>
  </div>
</template>
