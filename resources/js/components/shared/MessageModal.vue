<template>
  <div>
    <button
      @click="openMessageModal"
      class="px-5 py-2 border border-gray-400 text-gray-700 text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-sm"
    >
      Message
    </button>

    <div
      v-if="messageModal"
      class="fixed inset-0 bg-black/60 z-50 md:flex md:justify-center md:items-center"
    >
      <div class="h-full w-full bg-white md:h-5/6 md:max-w-xl md:rounded-lg md:shadow-2xl flex flex-col">
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <button @click="closeMessageModal" class="text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div class="flex-grow text-center">
            <h2 class="text-lg font-semibold">{{ email }}</h2>
            <p class="text-xs text-gray-500">ID: {{ id }}</p>
          </div>
          <div class="w-6"></div>
        </div>

        <!-- Conversation -->
        <div ref="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4">
          <div 
            v-for="message in conversations" 
            :key="message.id" 
            class="flex" 
            :class="{ 'justify-end': message.sender_id === authId }"
          >
            <div 
              class="p-3 rounded-xl max-w-xs"
              :class="{ 
                'bg-blue-500 text-white rounded-br-none': message.sender_id === authId, 
                'bg-gray-200 text-gray-800 rounded-bl-none': message.sender_id !== authId 
              }"
            >
              {{ message.message_content }}
            </div>
          </div>
        </div>

        <!-- Input -->
        <div class="p-4 border-t border-gray-200 bg-white">
          <form @submit.prevent="sendMessage" class="flex w-full">
            <input 
              type="text" 
              v-model="message_content"
              class="flex-1 rounded-full px-4 py-2 bg-gray-100 border border-gray-300 focus:outline-none" 
              placeholder="Type a message..."
            >
            <button 
              type="submit"
              class="ml-2 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 focus:outline-none transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </button>
          </form>

          <!-- Validation error -->
          <p v-if="errors.message_content" class="text-red-500 text-sm mt-2">
            {{ errors.message_content[0] }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3';

const page = usePage()

// get authenticated user id
const authId = page.props.user.id

const props = defineProps({
  email: String,
  id: Number
})

// State
const messageModal = ref(false)
const conversations = ref([])
const message_content = ref('')
const errors = ref({})

// A reference to the chat container
const chatBox = ref(null)

// Scroll chat to bottom
async function scrollToBottom() {
  await nextTick() // wait for DOM updates
  if (chatBox.value) {
    chatBox.value.scrollTop = chatBox.value.scrollHeight
  }
}

// Fetch conversation messages
async function fetchMessages() {
  try {
    const response = await axios.get(route('messages.conversation', props.id))
    conversations.value = response.data.messages 

    // scroll after messages render
    scrollToBottom()
  } catch (error) {
    console.error(error)
  }
}

// Send message
async function sendMessage() {
  try {
    const response = await axios.post(route('messages.send'), {
      receiver_id: props.id,
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

// Modal open/close
function openMessageModal() {
  messageModal.value = true
  fetchMessages()
}

function closeMessageModal() {
  messageModal.value = false
}
</script>
