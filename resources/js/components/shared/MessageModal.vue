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
        
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <button @click="closeMessageModal" class="text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div class="flex-grow text-center">
            <h2 class="text-lg font-semibold">{{ email }}</h2>
            <p class="text-xs text-gray-500">{{ id }}</p>
          </div>
          <div class="w-6"></div> </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <div 
            v-for="message in messages" 
            :key="message.id" 
            class="flex" 
            :class="{ 'justify-end': message.sender === 'me' }"
          >
            <div 
              class="p-3 rounded-xl max-w-xs"
              :class="{ 
                'bg-blue-500 text-white rounded-br-none': message.sender === 'me', 
                'bg-gray-200 text-gray-800 rounded-bl-none': message.sender !== 'me' 
              }"
            >
              {{ message.text }}
            </div>
          </div>
        </div>

        <div class="p-4 border-t border-gray-200 bg-white">
          <div class="flex items-center">
            <input 
              type="text" 
              v-model="newMessage"
              @keyup.enter="sendMessage"
              class="flex-1 rounded-full px-4 py-2 bg-gray-100 border border-gray-300 focus:outline-none" 
              placeholder="Type a message..."
            >
            <button 
              @click="sendMessage" 
              class="ml-2 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 focus:outline-none transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const { email, id } = defineProps({
  email: String,
  id: Number
});

// State variables
const messageModal = ref(false);
const newMessage = ref('')
const messages = ref([
  { id: 1, text: 'Hey there! How are you doing today?', sender: 'other' },
  { id: 2, text: "I'm doing great, thanks for asking! What about you?", sender: 'me' },
  { id: 3, text: 'Just finished my work for the day. Feeling pretty good.', sender: 'other' },
  { id: 4, text: 'That\'s awesome! Enjoy the rest of your evening.', sender: 'me' }
])

// Functions
function openMessageModal() {
  messageModal.value = true
}

function closeMessageModal() {
  messageModal.value = false
}

function sendMessage() {
  if (newMessage.value.trim() === '') return; // Don't send empty messages
  
  messages.value.push({
    id: messages.value.length + 1,
    text: newMessage.value,
    sender: 'me'
  });
  
  newMessage.value = ''; // Clear the input field
}
</script>