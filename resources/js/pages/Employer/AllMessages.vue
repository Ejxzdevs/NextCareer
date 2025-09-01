<template>
    <div class="">
        <div class="h-16 flex items-center">
                <a @click.prevent="goBack()" href="#" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left ms-5"></i>
        <span class="font-medium">Back</span>
      </a>
    </div>
      
         <div class="w-full h-screen px-6 py-2 overflow-y-hidden
">
    
        <div class="w-full lg:w-full h-[85%] flex flex-row gap-2 mx-auto bg-white shadow-lg rounded-lg p-2">
            <div class="hidden md:block w-[300px] border border-gray-300 rounded-lg px-2 overflow-y-auto ">
                <h1 class="sticky text-2xl font-semibold text-gray-600 top-0 bg-white mb-2">Chat</h1>
                <ul>  
                <li
                     v-if="userWithLastMessages.length === 0"
                    class="p-4 text-center text-gray-500"
                    >
                    No messages yet.
                </li>
                <li
                v-for="userInList in userWithLastMessages"
                :key="userInList.id"
                @click="selectConversations(userInList.id)"
                class="mt-1 border border-gray-300 rounded-sm p-1 flex items-center gap-2 cursor-pointer"
                :class="{ 'bg-blue-100': userInList.id === Number(user_id) }"
                >
                    <img
              :src="userInList.avatar"
              class="w-10 h-10 rounded-full object-cover"
              alt="User Avatar"
            />
                    <div>
                        <p class="font-semibold text-gray-700">{{ userInList.sender }}</p>
                        <p class="text-sm text-gray-600 truncate w-40">
                            {{  userInList.text }}
                        </p>
                    </div>
                </li>
                </ul>

            </div>

            <div class="flex-1 flex flex-col gap-2 border border-gray-300 rounded-lg p-4">
              <div v-if="conversations.length === 0" class="flex-1 flex flex-col items-center justify-center text-gray-400">
                <i class="fas fa-comments fa-3x mb-4"></i>
                <p class="text-lg">Select a conversation to start chatting</p>
              </div>
                <div class="flex-1 flex flex-col gap-4 overflow-x-hidden overflow-y-auto px-2">
                    <div 
                    v-for="message in conversations" 
                    :key="message.message_id"
                    class="flex"
                    :class="{ 'justify-end': message.sender_id === authId }"
                    >
                        <p class="p-3 rounded-lg text-sm"
                        :class="{ 'bg-blue-500 text-white rounded-br-none': message.sender_id === authId, 
                'bg-gray-200 text-gray-800 rounded-bl-none': message.sender_id !== authId }"
                        >{{message.message_content}}</p>
                    </div>
                </div>
                    <div v-if="conversations.length >= 1" class="h-12 flex gap-1 items-center justify-center">
                        <form @submit.prevent="sendMessage" class="h-full w-full flex space-x-2">
                            <input v-model="message_content" type="text" class="border border-gray-300 rounded-md  w-full h-full px-2" placeholder="Aa" />
                            <button class="bg-blue-500 text-white px-4 rounded">SEND</button>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
</template>
<script setup>
import { ref,computed,onMounted   } from "vue";
import { formatTimeAgo } from '@/utils/datetimeUtils';
import axios from 'axios'
import { usePage } from '@inertiajs/vue3';

const page = usePage()
// get authenticated user id
const authId = page.props.user.id
const url = page.url

// Example: "/messages/all?7="
console.log("Full URL:", url)

// Extract the number (7) using regex
const selected_id = url.match(/\d+/)?.[0]

console.log("User ID:",  selected_id)

const data = ref([])
const conversations = ref([])
const message_content = ref('')
const errors = ref({})
const user_id = ref(null)

async function fetchNotifcationMessages() {
  try {
    const response = await axios.get(route('messages.listWithLastMessage'));
    data.value = response.data
   
  } catch (error) {
    console.error(error)
  }
}

const userWithLastMessages = computed(() => {
  return data.value.map(msg => ({
    id: msg.sender_id,
    sender: msg.sender.email,
    text: msg?.message_content || 'No content',
    time: formatTimeAgo(msg?.created_at) || '',
    read: msg?.status === 'sent',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?...'
  }));
});


async function selectConversations($userId){
    try {
          const response = await axios.get(route('messages.conversation',$userId))
          conversations.value = response.data.messages
          user_id.value = $userId
    }catch (error) {
        console.error(error)
    }
}


async function sendMessage() {
  try {
    const response = await axios.post(route('messages.send'), {
      receiver_id: user_id.value,
      message_content: message_content.value
    })

    // Add new message instantly
    conversations.value.push(response.data.message)

    // Reset input
    message_content.value = ''
    errors.value = {}

    // scroll after new message renders
    scrollToBottom()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    }
  }
}


function goBack() {
  window.history.back();
}

onMounted(() => {
  fetchNotifcationMessages()

  if (selected_id) {
    user_id.value = selected_id
    selectConversations(selected_id)
  }
})
</script>