<template>
    <div
        class="h-screen overflow-y-auto flex flex-col"
        :class="
            userRole === 'freelance'
                ? 'lg:overflow-y-auto pb-10'
                : 'lg:overflow-hidden pb-8 lg:pb-0'
        "
    >
        <div
            :class="
                userRole === 'employer'
                    ? 'flex-grow grid grid-cols-1 lg:grid-cols-7 gap-2 lg:gap-4 p-4 sm:p-6 lg:p-0'
                    : 'flex-grow'
            "
        >
            <!-- Left Content -->
            <div class="flex flex-col lg:col-span-5">
                <!-- Back Button -->
                <div
                    class="h-16 items-center ms-3 lg:ms-5 mb-1 sm:mb-4 lg:mb-0 flex"
                >
                    <backBtn />
                </div>

                <!-- Error Message -->
                <div
                    v-if="error"
                    class="bg-red-100 text-red-700 p-4 rounded mb-4"
                >
                    {{ error }}
                </div>

                <!-- Project Content -->
                <div v-else-if="project" class="flex justify-center">
                    <div
                        class="bg-white rounded-md shadow-lg border border-gray-300 flex flex-col relative w-full max-w-4xl h-fit sm:h-auto custom-scrollbar transition-all duration-300 ease-in-out px-8 py-6 sm:px-12 sm:py-2"
                    >
                        <!-- User Info -->
                        <div class="flex items-center mb-5 sm:mb-6">
                            <img
                                :src="
                                    project.user.profile.profile_picture
                                        ? project.user.profile.profile_picture
                                        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                                "
                                alt="profile"
                                class="h-12 w-12 rounded-full object-center object-cover mt-2"
                            />
                            <div class="ms-1 flex flex-col gap-0 sm:ms-2 mt-3">
                                <p
                                    class="text-sm font-semibold text-gray-800 name"
                                >
                                    {{ project.user.username }}
                                </p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    <i
                                        class="fa-regular fa-clock text-gray-500"
                                    ></i>
                                    {{ formatTimeAgo(project.created_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Project Title -->
                        <div class="flex flex-row items-center gap-2 mb-2">
                            <i
                                class="fa-1x fas fa-thumbtack text-red-500 mt-1"
                            ></i>
                            <p
                                class="text-2xl font-extrabold text-blue-900 leading-tight title"
                            >
                                {{ project.title }}
                            </p>
                        </div>

                        <hr class="border-gray-200 mb-4" />

                        <!-- Description -->
                        <div class="mb-8">
                            <div class="flex flex-row items-center gap-2 mb-2">
                                <i class="fas fa-file-alt text-[#30d2e4d5]"></i>
                                <label class="label text-xs">Description</label>
                            </div>
                            <p class="!text-gray-600 text-sm mt-2 label ps-4">
                                {{
                                    project?.description ||
                                    "No description available."
                                }}
                            </p>
                        </div>

                        <!-- Details -->
                        <section
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800 text-sm mt-2 mb-8"
                        >
                            <!-- Budget -->
                            <div class="flex items-center gap-3 md:col-span-2">
                                <i
                                    class="text-3xl fas fa-coins text-yellow-500 mt-1"
                                ></i>
                                <div class="flex flex-col gap-0 mt-2">
                                    <strong class="block label text-xs"
                                        >Budget</strong
                                    >
                                    <span class="text-sm label !text-black">{{
                                        formatCurrency(project?.budget)
                                    }}</span>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="flex items-center gap-3">
                                <i
                                    class="text-3xl far fa-calendar-alt text-green-500 mt-1"
                                ></i>
                                <div class="flex flex-col gap-0 mt-2">
                                    <label class="block label text-xs"
                                        >Start Date</label
                                    >
                                    <span class="text-sm label !text-black">{{
                                        formatDate(project?.start_date)
                                    }}</span>
                                </div>
                            </div>

                            <!-- Deadline -->
                            <div class="flex items-center gap-3">
                                <i
                                    class="text-3xl far fa-calendar-check text-red-500 mt-1"
                                ></i>
                                <div class="flex flex-col gap-0 mt-2">
                                    <label class="block label text-xs"
                                        >Deadline</label
                                    >
                                    <span class="text-sm label !text-black">{{
                                        formatDate(project?.deadline)
                                    }}</span>
                                </div>
                            </div>
                        </section>

                        <hr class="border-gray-200 mb-2" />

                        <!-- Tags -->
                        <div class="flex flex-col gap-1 mb-2 mt-4 sm:mb-6">
                            <div class="flex flex-row items-center gap-2 mb-3">
                                <i
                                    class="fas fa-laptop-code text-green-500 text-2xl"
                                ></i>
                                <label class="label text-xs"
                                    >Requirements</label
                                >
                            </div>

                            <div class="flex flex-wrap gap-2 rounded">
                                <span
                                    v-for="category in formatSkills(
                                        project.category
                                    )"
                                    :key="category"
                                    class="px-3 py-1.5 border border-gray-200 shadow-sm bg-indigo-50 text-indigo-700 text-sm sm:text-sm font-medium rounded-full"
                                >
                                    {{ category }}
                                </span>
                                <span
                                    v-for="skills in formatSkills(
                                        project.skills
                                    )"
                                    :key="skills"
                                    class="px-3 py-1.5 border border-gray-200 shadow-sm bg-emerald-50 text-emerald-700 text-sm sm:text-sm font-medium rounded-full"
                                >
                                    {{ skills }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="flex justify-center items-center flex-grow">
                    <div class="text-gray-500 text-lg sm:text-xl">
                        Loading project...
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div
                v-show="userRole === 'employer'"
                class="border border-gray-300 bg-white shadow-sm overflow-y-auto max-h-full lg:max-h-screen col-span-1 lg:col-span-2"
            >
                <div class="p-4 border-b border-gray-200">
                    <label
                        class="text-lg font-semibold flex flex-row items-center gap-2"
                    >
                        <i class="fa-solid fa-users text-gray-700"></i>
                        <span class="!text-blue-600 label"
                            >Application List</span
                        >
                    </label>
                </div>

                <ul
                    v-if="userApplication && userApplication.length"
                    class="divide-y divide-gray-200"
                >
                    <li
                        v-for="(application, index) in userApplication"
                        :key="application.id"
                        class="p-4 hover:bg-gray-50 transition-colors duration-200 ease-in-out cursor-pointer"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2"
                        >
                            <p
                                v-if="application.created_at"
                                class="text-sm text-gray-400 flex-shrink-0"
                            >
                                <i
                                    class="fa-regular fa-clock text-gray-500"
                                ></i>
                                {{ formatTimeAgo(application.created_at) }}
                            </p>
                        </div>

                        <div
                            class="text-base font-bold text-gray-700 mb-3 flex items-center gap-2 truncate"
                        >
                            <img
                                :src="
                                    application.user.profile.profile_picture
                                        ? application.user.profile
                                              .profile_picture
                                        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                                "
                                alt="profile"
                                class="h-9 w-9 rounded-full object-center object-cover"
                            />
                            <div class="flex flex-col">
                                <p class="name">
                                    {{
                                        application.user.username ||
                                        "Untitled Application"
                                    }}
                                </p>
                                <div
                                    class="cursor-pointer font-normal text-sm flex items-center gap-1 text-gray-500"
                                >
                                    Profile
                                    <Link
                                        :href="
                                            route(
                                                'userProfile.show',
                                                application.user.id
                                            )
                                        "
                                        class="text-blue-500 underline"
                                        >visit here</Link
                                    >
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mt-1"
                        >
                            <a
                                :href="`/storage/${application.resume}`"
                                target="_blank"
                                class="w-full sm:w-auto text-center gap-2 cursor-pointer text-sm font-medium text-blue-600 hover:underline px-2 py-2 border border-blue-200 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors"
                            >
                                <i class="fas fa-file-download"></i>
                                Resume
                            </a>

                            <a
                                v-if="application.link_portfolio"
                                :href="application.link_portfolio"
                                target="_blank"
                                class="w-full sm:w-auto inline-flex gap-2 justify-center cursor-pointer items-center text-sm font-medium border border-green-200 text-green-600 bg-green-200 hover:bg-green-100 px-3 py-2 rounded-md shadow transition-colors"
                            >
                                <i class="fas fa-eye"></i>
                                View Portfolio
                            </a>
                        </div>
                    </li>
                </ul>

                <div v-else class="p-6 text-center text-gray-500 text-base">
                    <p>No applications submitted yet.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { formatTimeAgo, formatDate } from "@/utils/datetimeUtils";
import backBtn from "@/components/UI/BackLink.vue";
import { formatSkills } from "@/utils/stringUtils";
import { formatCurrency } from "@/utils/numberUtils";
import { getUserRole } from "@/utils/auth";

const userRole = getUserRole();

const { project, error, userApplication } = usePage().props;
</script>
