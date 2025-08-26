<template>
  <li class="relative" ref="dropdownRef">
    <!-- Message Button -->
    <button
      @click="toggleDropdown"
      class="relative hover:text-gray-300 cursor-pointer flex items-center gap-1 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
      <i class="fas fa-envelope text-xl"></i>
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
        class="absolute right-0 mt-3 w-96 bg-white text-gray-800 shadow-xl rounded-lg border border-gray-200 z-50 overflow-x-hidden"
        @click.stop
      >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
          <h3 class="text-lg font-semibold">Messages</h3>
          <button
            @click="markAllAsRead"
            class="cursor-pointer text-sm text-blue-600 hover:text-blue-800 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="unreadCount === 0"
          >
            Mark all as read
          </button>
        </div>

        <!-- Messages List -->
        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100 list-none p-0 m-0">
          <li
            v-if="messages.length === 0"
            class="p-4 text-center text-gray-500"
          >
            No messages yet.
          </li>
          <li
            v-for="message in messages"
            :key="message.id"
            :class="[
              'flex items-center gap-3 p-3 cursor-pointer',
              message.read ? 'hover:bg-gray-50' : 'bg-blue-100 font-semibold'
            ]"
          >
            <!-- Avatar -->
            <img
              :src="message.avatar"
              class="w-10 h-10 rounded-full object-cover"
              alt="User Avatar"
            />

            <!-- Message content -->
            <div class="flex-grow">
              <p class="text-sm font-semibold">{{ message.sender }}</p>
              <p class="text-xs text-gray-600 truncate">
                {{ message.text }}
              </p>
            </div>

            <!-- Time -->
            <div class="text-xs text-gray-400 whitespace-nowrap">
              {{ message.time }}
              <!-- for testing -->
               <MessageModal 
                  :email="message.sender" 
                  :id="message.id" 
                />
            </div>
          </li>
        </ul>

        <!-- Footer -->
        <div
          v-if="messages.length > 0"
          class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50"
        >
          <a
            href="#"
            class="text-sm text-blue-600 hover:text-blue-800 font-medium"
          >
            View All Messages
          </a>
        </div>
      </div>
    </transition>
  </li>
</template>

<script setup>
import { ref, computed , onMounted  } from "vue";
import { useDropdown } from "../../composables/useToggleVisibility";
import { formatTimeAgo } from '@/utils/datetimeUtils';
import axios from 'axios'
import MessageModal from '@/components/shared/MessageModal.vue'
// Dropdown state
const { isDropdownOpen, toggleDropdown, closeDropdown, dropdownRef } = useDropdown();

const data = ref([])

async function fetchNotifcationMessages() {
  try {
    const response = await axios.get(route('messages.notifications'))
    data.value = response.data
    console.log(response.data)

  } catch (error) {
    console.error(error)
  }
}


const messages = computed(() => {
  return data.value.map(msg => ({
    id: msg.received_messages[0].sender_id,
    sender: msg.received_messages[0].sender.email,
    text: msg.received_messages[0]?.message_content || 'No content',
    time: formatTimeAgo(msg.received_messages[0]?.created_at) || '',
    read: msg.received_messages[0]?.status || false,
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?...'
  }))
})

// Computed unread count
const unreadCount = computed(() =>
  data.value.filter(m => m.received_messages[0].status === 'sent').length
);

// Mark all as read
function markAllAsRead() {
  messages.value = messages.value.map(m => ({ ...m, read: true }));
}

onMounted(() => {
  fetchNotifcationMessages()
})

</script>
