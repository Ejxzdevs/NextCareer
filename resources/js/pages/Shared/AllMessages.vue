<template>
    <div
        class="relative flex flex-col h-screen bg-gray-100 font-sans antialiased"
    >
        <backBtn class="ms-5 mt-4 my-3" />

        <div class="flex flex-row h-screen pb-15">
            <div
                class="flex h-full w-full overflow-hidden p-2 transition-all duration-300 sm:px-6 sm:pb-3"
            >
                <!-- Mobile Top Bar -->
                <div
                    v-if="user_id && isMobile"
                    class="absolute left-0 top-0 z-20 flex w-full items-center justify-between bg-white p-4 shadow-md md:hidden"
                >
                    <div class="flex items-center space-x-3">
                        <img
                            :src="selectedUser?.avatar"
                            alt="User Avatar"
                            class="h-10 w-10 rounded-full object-cover"
                        />
                        <span class="font-semibold text-gray-800">{{
                            selectedUser?.sender
                        }}</span>
                    </div>
                    <button
                        @click="closeConversation"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar (All Messages) -->
                <div
                    :class="{
                        'absolute z-10 h-full w-full md:relative md:block md:w-[320px]': true,
                        'hidden md:block': user_id && isMobile,
                        block: !user_id || !isMobile,
                    }"
                    class="shrink-0 rounded-xl bg-white shadow-lg transition-all duration-300 md:block md:w-[320px] md:shadow-md"
                >
                    <div class="flex h-full flex-col overflow-hidden">
                        <h2
                            class="sticky top-0 z-10 flex items-center gap-2 border-b border-gray-200 bg-white px-4 py-3 text-xl font-bold text-gray-700"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-blue-500"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path
                                    d="M4 4h16c1.103 0 2 .897 2 2v12c0 1.103-.897 2-2 2H4c-1.103 0-2-.897-2-2V6c0-1.103.897-2 2-2zm0 2v12h16L4 6zM8 10h8v2H8z"
                                />
                            </svg>
                            All Messages
                        </h2>
                        <div class="flex-1 overflow-y-auto p-4 scrollbar-thin">
                            <div
                                v-if="inboxError"
                                class="rounded-lg bg-red-100 p-3 text-sm text-red-600"
                            >
                                {{ inboxError }}
                            </div>
                            <div
                                v-else-if="userWithLastMessages.length === 0"
                                class="flex h-full items-center justify-center p-4 text-center text-gray-500"
                            >
                                <p>No messages yet.</p>
                            </div>
                            <ul v-else class="space-y-2">
                                <li
                                    v-for="userInList in userWithLastMessages"
                                    :key="userInList.id"
                                    @click="selectConversations(userInList.id)"
                                    class="flex cursor-pointer items-center space-x-3 rounded-lg p-3 transition-colors duration-200"
                                    :class="{
                                        'bg-blue-50 shadow-md':
                                            userInList.id === Number(user_id),
                                        'hover:bg-gray-50':
                                            userInList.id !== Number(user_id),
                                    }"
                                >
                                    <img
                                        :src="userInList.avatar"
                                        class="h-12 w-12 shrink-0 rounded-full object-cover ring-2 ring-white"
                                        alt="User Avatar"
                                    />
                                    <div class="flex-1 overflow-hidden">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <p
                                                class="truncate font-semibold text-gray-800"
                                            >
                                                {{ userInList.sender }}
                                            </p>
                                            <span
                                                class="ml-2 shrink-0 text-xs text-gray-500"
                                                >{{ userInList.time }}</span
                                            >
                                        </div>
                                        <p
                                            class="truncate text-sm text-gray-600"
                                        >
                                            {{ userInList.text }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Conversation Panel -->
                <div
                    :class="{
                        'hidden h-full flex-1 md:flex': !user_id && isMobile,
                        'flex h-full w-full': user_id || !isMobile,
                    }"
                    class="flex flex-1 flex-col overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 md:ml-4 md:shadow-md"
                >
                    <div class="flex flex-1 flex-col overflow-hidden">
                        <!-- Empty state -->
                        <div
                            v-if="conversations.length === 0 && !user_id"
                            class="flex flex-1 flex-col items-center justify-center p-4 text-center text-gray-400"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mb-4 h-16 w-16"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path
                                    d="M20 2H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h3v3.707l5.293-5.293H20c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm0 14H7.414L5 18.414V4h15.997L20 16z"
                                />
                            </svg>
                            <p class="text-lg">
                                Select a conversation to start chatting.
                            </p>
                        </div>

                        <!-- Messages -->
                        <div
                            v-else
                            id="chat-container"
                            class="flex-1 overflow-y-auto overflow-x-hidden p-4 scrollbar-thin"
                        >
                            <div
                                v-if="conversationError"
                                class="rounded-lg bg-red-100 p-3 text-sm text-red-600"
                            >
                                {{ conversationError }}
                            </div>
                            <div
                                v-else
                                v-for="message in conversations"
                                :key="message.message_id"
                                class="my-2 flex"
                                :class="{
                                    'justify-end': message.sender_id === authId,
                                }"
                            >
                                <div
                                    class="max-w-[75%] rounded-2xl px-4 py-2 text-sm"
                                    :class="{
                                        'bg-blue-500 text-white':
                                            message.sender_id === authId,
                                        'rounded-bl-none':
                                            message.sender_id === authId,
                                        'bg-gray-200 text-gray-800':
                                            message.sender_id !== authId,
                                        'rounded-br-none':
                                            message.sender_id !== authId,
                                    }"
                                >
                                    {{ message.message_content }}
                                </div>
                            </div>
                        </div>

                        <!-- Input -->
                        <form
                            v-if="user_id"
                            @submit.prevent="sendMessage"
                            class="flex items-center gap-2 border-t border-gray-200 p-4"
                        >
                            <input
                                v-model="message_content"
                                type="text"
                                class="h-10 w-full rounded-full border border-gray-300 bg-gray-50 px-4 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="Aa"
                            />
                            <button
                                type="submit"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-500 text-white transition-transform duration-200 ease-in-out hover:scale-110 hover:bg-blue-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 rotate-90 transform"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l4.538-1.815a1 1 0 00.315-.929l.061-.258a1 1 0 011.442-.403l3.522 2.818a1 1 0 001.603-.999l-2.046-7.859a1 1 0 00-.783-.759z"
                                    />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    getInboxApi,
    sendMessageApi,
    getConversationApi,
} from "@/services/MessageServices";
import { formatTimeAgo } from "@/utils/datetimeUtils";
import backBtn from "@/components/UI/BackLink.vue";
import { getUserID } from "@/utils/auth";

const page = usePage();
const authId = getUserID();
const url = page.url;
const selected_id = url.match(/\d+/)?.[0];

const data = ref([]);
const conversations = ref([]);
const message_content = ref("");
const errors = ref({});
const user_id = ref(null);
const inboxError = ref(null);
const conversationError = ref(null);
const isMobile = ref(false);

const selectedUser = computed(() => {
    return userWithLastMessages.value.find(
        (user) => user.id === Number(user_id.value)
    );
});

const userWithLastMessages = computed(() => {
    return data.value.map((msg) => ({
        id: msg.sender_id,
        sender: msg.sender.username,
        text: msg?.message_content || "No content",
        time: formatTimeAgo(msg?.created_at) || "",
        read: msg?.status === "sent",
        avatar: msg.sender.profile.profile_picture
            ? msg.sender.profile.profile_picture
            : "https://via.placeholder.com/150",
    }));
});

const loadInbox = async () => {
    inboxError.value = null;
    const { data: inboxResult, error } = await getInboxApi();
    if (error) {
        inboxError.value = error;
        data.value = [];
    } else {
        data.value = inboxResult;
    }
};

async function selectConversations(userId) {
    conversationError.value = null;
    const { data, error } = await getConversationApi(userId);
    if (data) {
        conversations.value = data;
        user_id.value = userId;
        await nextTick();
        scrollToBottom();
    } else {
        conversationError.value = error;
        conversations.value = [];
    }
}

async function sendMessage() {
    const { data, error } = await sendMessageApi(
        user_id.value,
        message_content.value
    );
    if (data) {
        conversations.value.push(data);
        message_content.value = "";
        errors.value = {};
        await nextTick();
        scrollToBottom();
    } else {
        if (typeof error === "object") {
            errors.value = error;
        } else {
            console.error(error);
        }
    }
}

function scrollToBottom() {
    const chatContainer = document.getElementById("chat-container");
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

function handleResize() {
    isMobile.value = window.innerWidth < 768;
}

function closeConversation() {
    user_id.value = null;
    conversations.value = [];
}

onMounted(() => {
    loadInbox();
    if (selected_id) {
        user_id.value = selected_id;
        selectConversations(selected_id);
    }

    handleResize();
    window.addEventListener("resize", handleResize);

    Echo.private(`chat.${authId}`).listen("MessageSent", (e) => {
        const userIdNum = Number(user_id.value);
        if (
            e.message.sender_id === userIdNum ||
            e.message.receiver_id === userIdNum
        ) {
            conversations.value.push(e.message);
            nextTick().then(() => {
                scrollToBottom();
            });
        }
        loadInbox();
    });
});
</script>
