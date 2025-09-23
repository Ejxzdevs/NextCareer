<template>
    <!-- Parent wrapper; clicking outside closes mobile menu -->
    <div @click.self="isMenuOpen = false">
        <nav class="bg-[#145EEE] p-4">
            <div class="flex justify-between items-center">
                <!-- Logo (desktop) -->
                <div class="hidden md:flex text-white text-lg font-semibold">
                    <Link :href="route('landing.page')">Next Career</Link>
                </div>

                <!-- Desktop navigation links -->
                <ul
                    class="hidden md:flex item-center space-x-3 text-white items-center font-medium text-sm"
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
                            class="hover:text-gray-300 nav-link"
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
                            class="hover:text-gray-300 nav-link"
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
                            class="hover:text-gray-300 nav-link"
                        >
                            Applications
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Mobile navigation header -->
            <div class="md:hidden flex flex-row justify-between">
                <!-- Logo (mobile) -->
                <div class="text-white text-lg font-semibold">
                    <Link :href="route('landing.page')">Next Career</Link>
                </div>

                <!-- Right section: dropdowns + menu toggle button -->
                <div class="flex flex-row">
                    <ul
                        class="flex flex-row gap-1 me-3 text-white items-center"
                    >
                        <UserNotificationDropdown />
                        <MessageDropdown />
                        <UserDropdown />
                    </ul>

                    <!-- Mobile menu toggle button (hamburger icon) -->
                    <button
                        @click="toggleMenu"
                        class="text-white cursor-pointer"
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
                v-if="isMenuOpen"
                class="md:hidden !text-white bg-[#5b72c6] p-4 space-y-2 text-sm label"
            >
                <li>
                    <Link
                        v-if="userRole === 'employer'"
                        :href="route('employer.dashboard')"
                        class="hover:text-gray-300 nav-link"
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
                        class="hover:text-gray-300 nav-link"
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
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

// Dropdown components
import UserDropdown from "@/Components/Shared/UserDropdown.vue";
import MessageDropdown from "@/Components/Shared/MessageDropdown.vue";
import UserNotificationDropdown from "@/Components/Shared/NotificationDropdown.vue";

const page = usePage();

// State for mobile menu toggle
const isMenuOpen = ref(false);

// Default role set as guest
const userRole = ref("guest");

// Determine user role from Inertia props
const updateUserRole = () => {
    const role = page.props.user?.role;
    userRole.value =
        role === "freelance"
            ? "freelance"
            : role === "employer"
            ? "employer"
            : "guest";
};

// Initialize role when component mounts
onMounted(() => {
    updateUserRole();
});

// Toggle mobile menu
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>
