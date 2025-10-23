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
                    <i class="fa-regular fa-envelope text-green-500"></i>
                    <span class="text-1xl !font-normal !text-gray-800 label"
                        >All Messages</span
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
                <a
                    v-for="msg in formattedMessages"
                    :key="msg.id"
                    :class="[
                        'p-2.5 mb-1 border border-gray-200 rounded flex items-center gap-2.5 cursor-pointer',
                        msg.read
                            ? 'bg-blue-100 font-semibold'
                            : 'hover:bg-gray-50',
                    ]"
                    :href="route('user.allMessages.index', msg.id)"
                >
                    <!-- Avatar -->
                    <img
                        :src="msg.avatar"
                        class="w-10 h-10 rounded-full object-cover"
                        alt="User Avatar"
                    />

                    <!-- msg content -->
                    <div class="flex-grow">
                        <p class="text-sm font-semibold text-blue-500 name">
                            {{ msg.sender }}
                        </p>
                        <p class="text-xs text-gray-600 truncate">
                            {{ msg.text }}
                        </p>
                    </div>

                    <!-- Time -->
                    <div class="text-xs text-gray-400 whitespace-nowrap mt-4">
                        {{ msg.time }}
                    </div>
                    <div
                        v-if="msg.read"
                        class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"
                    ></div>
                </a>
            </ul>
        </div>
    </transition>
</template>

<script setup>
import { computed } from "vue";
import { formatTimeAgo } from "@/utils/datetimeUtils";

// Props
const props = defineProps({
    show: Boolean,
    data: { type: Array, default: () => [] },
});

const emit = defineEmits(["hide"]);

const defaultAvatar =
    "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png";

const formattedMessages = computed(() => {
    return props.data.map((msg) => ({
        id: msg.sender_id,
        sender: msg.sender?.username || "Unknown",
        text: msg?.message_content || "No content",
        time: formatTimeAgo(msg?.updated_at) || "",
        read: msg?.status === "sent",
        avatar: msg.sender?.profile?.profile_picture || defaultAvatar,
    }));
});

const closeModal = () => {
    emit("hide");
};
</script>
