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
            @click="openMessage(message.id)"
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
import { ref, computed } from "vue";
import { useDropdown } from "../../composables/useToggleVisibility";

// Dropdown state
const { isDropdownOpen, toggleDropdown, closeDropdown, dropdownRef } = useDropdown();

// Hardcoded messages
const messages = ref([
  {
    id: 1,
    sender: "John Doe",
    text: "Hey, are you available for a quick call?",
    time: "2m ago",
    read: false,
    avatar: "https://i.pravatar.cc/40?img=1"
  },
  {
    id: 2,
    sender: "Jane Smith",
    text: "I’ve sent the report. Check your email!",
    time: "15m ago",
    read: true,
    avatar: "https://i.pravatar.cc/40?img=2"
  },
  {
    id: 3,
    sender: "Mark Lee",
    text: "Let's meet tomorrow at 3PM.",
    time: "1h ago",
    read: false,
    avatar: "https://i.pravatar.cc/40?img=3"
  }
]);

// Computed unread count
const unreadCount = computed(() =>
  messages.value.filter(m => !m.read).length
);

// Mark all as read
function markAllAsRead() {
  messages.value = messages.value.map(m => ({ ...m, read: true }));
}

// Open a message
function openMessage(id) {
  const msg = messages.value.find(m => m.id === id);
  if (msg) {
    msg.read = true;
    alert(`Opening message from ${msg.sender}: "${msg.text}"`);
  }
  closeDropdown();
}
</script>
