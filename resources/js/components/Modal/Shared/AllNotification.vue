<template>
    <!-- Overlay -->
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 bg-black/30 z-40"
            @click="closeModal"
        ></div>
    </transition>

    <!-- Sliding Modal -->
    <transition
        enter-active-class="transition-transform duration-300 ease-out"
        enter-from-class="translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition-transform duration-300 ease-in"
        leave-from-class="translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="show"
            class="fixed top-0 right-0 h-full bg-white shadow-lg py-4 w-full sm:w-1/2 lg:w-1/4 z-50"
        >
            <div
                class="mb-4 flex flex-row justify-between items-center px-4 pe-4"
            >
                <h2 class="flex flex-row gap-1.5 items-center">
                    <i class="fa-regular fa-bell text-blue-500"></i>
                    <span class="text-1xl !font-normal !text-gray-800 label"
                        >All Notifications</span
                    >
                </h2>

                <button
                    class="cursor-pointer text-gray-400 hover:text-gray-700"
                    @click="closeModal"
                >
                    <i class="fa-solid fa-xmark text-1xl"></i>
                </button>
            </div>

            <ul
                class="space-y-2 overflow-auto h-screen border-1 border-gray-300"
            >
                <li
                    v-for="notif in notifications"
                    :key="notif.id"
                    :class="[
                        'p-2.5 mb-1 border border-gray-200 rounded flex items-center gap-2.5 cursor-pointer',
                        notif.read
                            ? 'hover:bg-gray-50'
                            : 'bg-blue-100 font-semibold',
                    ]"
                    @click="viewNotification(notif.id, notif.application_id)"
                >
                    <img
                        :src="
                            notif.profile_picture ||
                            'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                        "
                        class="h-10 w-10 rounded-full object-cover"
                    />
                    <div class="flex-1">
                        <p class="flex-row">
                            <span class="font-semibold text-blue-500 me-1">{{
                                notif.username
                            }}</span>
                            <span class="!font-normal me-1 name">
                                {{
                                    role === "employer"
                                        ? " applied to your"
                                        : notif.application_status
                                }}
                            </span>
                            <span class="font-medium">{{
                                notif.project_title
                            }}</span>
                        </p>
                        <p class="text-xs text-gray-500">{{ notif.time }}</p>
                    </div>
                    <div
                        v-if="!notif.read"
                        class="w-2 h-2 bg-blue-500 rounded-full"
                    ></div>
                </li>
            </ul>
        </div>
    </transition>
</template>

<script setup>
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const role = page.props.user?.role;

const props = defineProps({
    show: Boolean,
    data: { type: Array, default: () => [] },
});

const emit = defineEmits(["hide"]);
console.log(
    role === "employer"
        ? props.data[0].applicant_username
        : props.data[0].username
);
const notifications = computed(() =>
    props.data.map((project) => ({
        id: project.project_id,
        application_id: project.application_id,
        username:
            role === "employer" ? project.applicant_username : project.username,
        project_title: project.title,
        time: new Date(
            project.project_updated_at || project.updated_at
        ).toLocaleString(),
        application_status: project.application_status,
        read: project.application_status !== "pending",
        profile_picture: project.profile_picture,
    }))
);

function viewNotification(id, application_id) {
    if (!id || !application_id) {
        alert("Invalid project or application ID.");
        return;
    }
    Inertia.get(
        route("project.show", { id: id, application_id: application_id }),
        {},
        {
            onError: (errors) => {
                alert(
                    "Could not load project. It may not exist or you do not have permission."
                );
            },
        }
    );
}

const closeModal = () => {
    emit("hide");
};
</script>
