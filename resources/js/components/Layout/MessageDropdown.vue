<template>
    <li class="relative" ref="dropdownRef">
        <!-- Message Button -->
        <button
            @click="toggleDropdown"
            class="relative hover:text-gray-300 cursor-pointer flex items-center gap-1 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            <i class="fas fa-envelope text-base"></i>
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"
            >
                {{ unreadCount }}
            </span>
        </button>

        <!-- Dropdown -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isDropdownOpen"
                class="fixed inset-x-0 top-[60px] mx-auto sm:ms-0 w-[calc(100%-20%)] max-h-[70vh] bg-white text-gray-800 shadow-xl rounded-t-lg border-t border-gray-200 z-50 overflow-y-auto sm:absolute sm:right-0 sm:mt-3 sm:w-96 sm:rounded-lg sm:border sm:border-gray-200 sm:inset-auto sm:max-h-80"
                @click.stop
            >
                <!-- Header -->
                <div
                    class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50"
                >
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-envelope text-green-500"></i>
                        <h3 class="text-1xl font-semibold label">Messages</h3>
                    </div>
                    <button
                        @click="markAllAsRead"
                        class="cursor-pointer text-sm text-blue-600 hover:text-blue-800 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="unreadCount === 0"
                    >
                        Mark all as read
                    </button>
                </div>

                <!-- Messages List -->
                <ul
                    class="max-h-80 overflow-hidden divide-y divide-gray-100 list-none p-0 m-0"
                >
                    <li
                        v-if="userWithLastMessages.length === 0"
                        class="p-4 text-center text-gray-500"
                    >
                        No messages yet.
                    </li>
                    <a
                        v-for="message in userWithLastMessages"
                        :key="message.id"
                        :class="[
                            'p-2.5 mb-1 border border-gray-200 rounded flex items-center gap-2.5 cursor-pointer',
                            message.read
                                ? 'bg-blue-100 font-semibold'
                                : 'hover:bg-gray-50',
                        ]"
                        :href="route('user.allMessages.index', message.id)"
                    >
                        <!-- Avatar -->
                        <img
                            :src="message.avatar"
                            class="w-10 h-10 rounded-full object-cover"
                            alt="User Avatar"
                        />

                        <!-- Message content -->
                        <div class="flex-grow">
                            <p class="text-sm font-semibold text-blue-500 name">
                                {{ message.sender }}
                            </p>
                            <p class="text-xs text-gray-600 truncate w-48">
                                {{ message.text }}
                            </p>
                        </div>

                        <!-- Time -->
                        <div
                            class="text-xs text-gray-400 whitespace-nowrap mt-4"
                        >
                            {{ message.time }}
                        </div>
                        <div
                            v-if="message.read"
                            class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"
                        ></div>
                    </a>
                </ul>

                <!-- Footer -->
                <div
                    v-if="userWithLastMessages.length > 0"
                    class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50"
                >
                    <button
                        @click="openAllMessage()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium cursor-pointer"
                    >
                        View All Messages
                    </button>
                </div>
                <showAllMessageModal
                    :show="showModalAllMessage"
                    @hide="closeSlidingMessage"
                    :data="data"
                />
            </div>
        </transition>
    </li>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useDropdown } from "@/composables/useToggleVisibility";
import { formatTimeAgo } from "@/utils/datetimeUtils";
import { getInboxApi, markAllAsReadApi } from "@/services/MessageServices";
import showAllMessageModal from "@/components/Modal/Shared/AllMessage.vue";
import { getUserID } from "@/utils/auth";

// get authenticated user id
const authId = getUserID();

// Dropdown state
const { isDropdownOpen, toggleDropdown, dropdownRef } = useDropdown();
const data = ref([]);
const showModalAllMessage = ref(false);
const openAllMessage = () => {
    showModalAllMessage.value = true;
};
const closeSlidingMessage = () => {
    showModalAllMessage.value = false;
};
// Load inbox messages
const loadInbox = async () => {
    const { data: inboxData, error } = await getInboxApi();
    if (data) {
        data.value = inboxData;
    } else {
        console.error("Error loading inbox:", error);
    }
};

// Computed property to flatten messages with user info
const userWithLastMessages = computed(() => {
    return data.value.map((msg) => ({
        id: msg.sender_id,
        sender: msg.sender.username,
        text: msg?.message_content || "No content",
        time: formatTimeAgo(msg?.created_at) || "",
        read: msg?.status === "sent",
        avatar:
            msg.sender.profile.profile_picture ||
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?...",
    }));
});

// count unread messages
const unreadCount = computed(() => {
    return (data.value || []).filter((m) => m?.status === "sent").length;
});

// Mark all as read
const markAllAsRead = () => {
    markAllAsReadApi()
        .then(() => {
            loadInbox();
        })
        .catch((error) => {
            console.error("Error marking messages as read:", error);
        });
};

onMounted(() => {
    loadInbox();
    Echo.private(`chat.${authId}`).listen("MessageSent", (e) => {
        loadInbox();
    });
});
</script>
