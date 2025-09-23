<template>
    <div>
        <button
            @click="openMessageModal"
            class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg bg-white transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer"
        >
            <i class="fa-solid fa-envelope"></i> Message
        </button>

        <Transition name="modal-fade">
            <div
                v-if="messageModal"
                class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center px-5"
            >
                <div
                    class="h-5/6 w-full bg-white md:h-5/6 md:max-w-lg md:rounded-2xl md:shadow-2xl flex flex-col"
                >
                    <!-- Header -->
                    <div
                        class="p-4 border-b border-gray-200 flex items-center gap-3 bg-white rounded-t-2xl"
                    >
                        <img
                            :src="
                                profile_picture
                                    ? profile_picture
                                    : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'
                            "
                            alt="profile"
                            class="h-12 w-12 rounded-full object-center object-cover"
                        />
                        <div>
                            <h2
                                class="text-sm font-semibold text-gray-900 name"
                            >
                                {{ username }}
                            </h2>
                            <div class="flex items-center gap-1">
                                <span
                                    class="w-2 h-2 bg-green-500 rounded-full"
                                ></span>
                                <p class="text-xs text-gray-500">Online</p>
                            </div>
                        </div>
                        <button
                            @click="closeMessageModal"
                            class="ml-auto text-gray-400 hover:text-gray-600"
                        >
                            <i
                                class="fas fa-xmark cursor-pointer me-2 mb-2"
                            ></i>
                        </button>
                    </div>

                    <!-- Conversation -->
                    <div
                        ref="chatBox"
                        class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
                    >
                        <div
                            v-for="message in conversations"
                            :key="message.id"
                            class="flex"
                            :class="{
                                'justify-end': message.sender_id === authId,
                                'justify-start': message.sender_id !== authId,
                            }"
                        >
                            <div
                                class="max-w-xs px-4 py-3 rounded-2xl shadow-sm text-sm"
                                :class="{
                                    'bg-blue-600 text-white':
                                        message.sender_id === authId,
                                    'bg-white text-gray-800 border border-gray-200':
                                        message.sender_id !== authId,
                                }"
                            >
                                {{ message.message_content }}
                            </div>
                        </div>
                    </div>

                    <!-- Input -->
                    <div
                        class="p-3 border-t border-gray-200 bg-white md:rounded-b-2xl"
                    >
                        <form
                            @submit.prevent="sendMessage"
                            class="flex items-center w-full gap-2"
                        >
                            <input
                                type="text"
                                v-model="message_content"
                                class="flex-1 rounded-full px-3 py-2 bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                placeholder="Write your message..."
                            />
                            <button
                                type="submit"
                                class="flex-shrink-0 bg-blue-500 text-white p-2 md:p-3 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 md:h-6 md:w-6 transform -rotate-45"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                </svg>
                            </button>
                        </form>
                        <p
                            v-if="errors.message_content"
                            class="text-red-500 text-xs mt-2"
                        >
                            {{ errors.message_content[0] }}
                        </p>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    sendMessageApi,
    getConversationApi,
} from "../../../services/MessageServices";

const page = usePage();

// get authenticated user id
const authId = page.props.user.id;

const props = defineProps({
    username: String,
    id: Number,
    profile_picture: String,
});

// State
const messageModal = ref(false);
const conversations = ref([]);
const message_content = ref("");
const errors = ref({});

// A reference to the chat container
const chatBox = ref(null);

// Scroll chat to bottom
async function scrollToBottom() {
    await nextTick();
    if (chatBox.value) {
        chatBox.value.scrollTop = chatBox.value.scrollHeight;
    }
}

// fetct conversation
async function fetchMessages() {
    const { data } = await getConversationApi(props.id);
    if (data) {
        conversations.value = data;
        await nextTick();
        scrollToBottom();
    } else {
        conversations.value = [];
    }
}

// send message
async function sendMessage() {
    const { data, error } = await sendMessageApi(
        props.id,
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

// Modal open/close
function openMessageModal() {
    messageModal.value = true;
    fetchMessages();
}

function closeMessageModal() {
    messageModal.value = false;
}
</script>
