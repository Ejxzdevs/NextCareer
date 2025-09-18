<template>
    <div @click.self="isDropdownOpen = false">
        <nav class="bg-[#145EEE] p-4">
            <!-- Desktop Menu -->
            <div class="flex justify-between items-center font-semibold">
                <div class="hidden md:flex text-white text-lg">
                    <a :href="route('landing.page')">Next Career</a>
                </div>
                <ul class="hidden md:flex space-x-6 text-white items-center">
                    <li>
                        <Link class="text-white" :href="route('login')"
                            >Login
                        </Link>
                    </li>
                    <li>
                        <Link
                            class="bg-white text-black px-4 py-2 rounded border border-black hover:bg-gray-200 transition duration-200"
                            :href="route('register')"
                        >
                            Register
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden flex flex-row justify-between">
                <div class="text-white text-lg font-semibold">Next Career</div>
                <button @click="toggleMenu" class="text-white">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>

            <!-- Mobile Dropdown -->
            <ul
                v-if="isMenuOpen"
                class="md:hidden text-white bg-[#7789C7] flex flex-col p-4 space-y-2"
            >
                <Link class="text-gray-300 pointer" :href="route('login')"
                    >Login</Link
                >
                <Link class="text-gray-300 pointer" :href="route('register')"
                    >Register</Link
                >
            </ul>
        </nav>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
// Reactive variables
const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};
</script>
