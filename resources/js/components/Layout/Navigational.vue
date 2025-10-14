<template>
    <!-- Parent wrapper; clicking outside closes mobile menu -->
    <div @click.self="isMenuOpen = false">
        <nav
            class="bg-white shadow-md px-4 py-2 !text-gray-600 border border-gray-300"
        >
            <div class="flex justify-between items-center">
                <!-- Logo (desktop) -->
                <div class="hidden md:flex text-gray-600 text-lg font-semibold">
                    <Link
                        :href="
                            userRole === 'freelance' || userRole === 'employer'
                                ? '#'
                                : route('landing.page')
                        "
                    >
                        <img
                            class="w-auto h-10 object-contain mt-2"
                            :src="logoUrl"
                            alt="NextCareen Logo"
                    /></Link>
                </div>

                <!-- Desktop navigation links -->
                <ul
                    v-show="userRole === 'freelance' || userRole === 'employer'"
                    class="hidden md:flex item-center space-x-3 text-gray-600 items-center font-medium text-sm"
                ></ul>

                <!-- Guest Routes -->
                <ul
                    v-show="userRole === 'guest'"
                    class="hidden md:flex item-center space-x-3 text-gray-600 items-center font-medium text-sm"
                >
                    <li>
                        <Link class="text-gray-600" :href="route('login')"
                            >Login
                        </Link>
                    </li>
                    <li>
                        <Link
                            class="cursor-pointer text-white bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded border border-gray-300 shadow-md transition duration-200"
                            :href="route('register')"
                        >
                            Register
                        </Link>
                    </li>
                </ul>
                <!-- Authenticated User Route -->
                <ul
                    v-show="userRole === 'employer' || userRole === 'freelance'"
                    class="hidden md:flex item-center space-x-3 text-gray-600 items-center font-medium text-sm"
                >
                    <div class="flex flex-row gap-2">
                        <UserNotificationDropdown />
                        <MessageDropdown />
                        <UserDropdown />
                    </div>

                    <!-- Employer-only dashboard link -->
                    <li>
                        <Link
                            v-if="userRole === 'employer'"
                            :href="route('employer.dashboard')"
                            class="!text-gray-600 nav-link"
                        >
                            Dashboard
                        </Link>
                    </li>

                    <!-- Find Jobs / Job Posts depending on role -->
                    <li>
                        <Link
                            :href="
                                userRole === 'freelance'
                                    ? route('freelance.browse')
                                    : route('employer.project')
                            "
                            class="!text-gray-600 nav-link"
                        >
                            {{
                                userRole === "freelance"
                                    ? "Find Jobs"
                                    : "Job Posts"
                            }}
                        </Link>
                    </li>

                    <!-- Applications (role-based) -->
                    <li>
                        <Link
                            :href="
                                userRole === 'freelance'
                                    ? route('freelance.application')
                                    : route('employer.application')
                            "
                            class="!text-gray-600 nav-link"
                        >
                            Applications
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Mobile navigation header -->
            <div class="md:hidden flex flex-row justify-between">
                <!-- Logo (mobile) -->
                <div class="text-gray-600 text-lg font-semibold">
                    <Link
                        :href="
                            userRole === 'freelance' || userRole === 'employer'
                                ? '#'
                                : route('landing.page')
                        "
                    >
                        <img
                            class="w-auto h-10 object-contain mt-2"
                            :src="logoUrl"
                            alt="NextCareen Logo"
                    /></Link>
                </div>

                <!-- Right section: dropdowns + menu toggle button -->
                <div class="flex flex-row">
                    <ul
                        v-show="
                            userRole === 'employer' || userRole === 'freelance'
                        "
                        class="flex flex-row gap-1 me-3 text-gray-600 items-center"
                    >
                        <UserNotificationDropdown />
                        <MessageDropdown />
                        <UserDropdown />
                    </ul>

                    <!-- Mobile menu toggle button (hamburger icon) -->
                    <button
                        @click="toggleMenu"
                        class="text-gray-600 cursor-pointer"
                    >
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
            </div>

            <!-- Mobile dropdown menu (visible when toggled) -->
            <ul
                v-show="userRole === 'employer' || userRole === 'freelance'"
                v-if="isMenuOpen"
                class="md:hidden rounded-sm mt-2 inset-0 bg-gray-600 backdrop-blur-lg p-4 space-y-2 text-sm label"
            >
                <li>
                    <Link
                        v-if="userRole === 'employer'"
                        :href="route('employer.dashboard')"
                        class="nav-link"
                    >
                        Dashboard
                    </Link>
                </li>
                <li>
                    <Link
                        :href="
                            userRole === 'freelance'
                                ? route('freelance.browse')
                                : route('employer.project')
                        "
                        class="nav-link"
                    >
                        {{
                            userRole === "freelance" ? "Find Jobs" : "Job Posts"
                        }}
                    </Link>
                </li>
                <li>
                    <Link
                        :href="
                            userRole === 'freelance'
                                ? route('freelance.application')
                                : route('employer.application')
                        "
                        class="nav-link"
                    >
                        Applications
                    </Link>
                </li>
            </ul>
            <ul
                v-show="userRole === 'guest'"
                v-if="isMenuOpen"
                class="md:hidden rounded-sm mt-2 inset-0 bg-gray-600 backdrop-blur-lg p-4 space-y-2 text-sm label"
            >
                <li>
                    <Link :href="route('login')" class="nav-link"> Login </Link>
                </li>
                <li>
                    <Link :href="route('register')" class="nav-link">
                        Register
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref } from "vue";

// Dropdown components
import UserDropdown from "@/Components/Layout/UserDropdown.vue";
import MessageDropdown from "@/Components/Layout/MessageDropdown.vue";
import UserNotificationDropdown from "@/Components/Layout/NotificationDropdown.vue";
import { getUserRole } from "@/utils/auth";

const logoFilename = "N.png";
const logoUrl = `/storage/logo/${logoFilename}`;

// State for mobile menu toggle
const isMenuOpen = ref();
const userRole = getUserRole() || "guest";

// Toggle mobile menu
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>
