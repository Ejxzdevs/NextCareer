<template>
  <div class="h-[100vh] w-[100vw] flex justify-center items-center px-2 ">
     <div class="absolute top-0 left-0 w-full">
          <Navigational />
        </div>
    <div
      class="border border-gray-300 rounded-lg p-5 shadow-md bg-white
      w-[clamp(350px,50vw,400px)]
      h-[auto]
    "
    >
      <h2 class="text-2xl font-semibold text-center mb-6">Register</h2>
      <form @submit.prevent="submit">
        <div v-if="$page.props.flash.success"  class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
          <span class="block sm:inline">  {{ $page.props.flash.success }}</span>
        </div>
        <div class="alert">
      
      </div>
        <div class="mb-4 relative">
          <label for="email" class="block text-sm font-medium mb-1">Email</label>
          <div class="flex items-center border border-gray-300 rounded-md">
            <i class="fas fa-envelope text-[#0b1215] pl-3"></i>
            <input
              v-model="form.email"
              type="email"
              name="email"
              id="email"
              autocomplete="username"
              class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2"
              placeholder="Enter Email:"
              required
            >
          </div>
        </div>

        <div class="mb-4 relative">
          <label for="password" class="block text-sm font-medium mb-1">Password</label>
          <div class="flex items-center border border-gray-300 rounded-md">
            <i class="fas fa-lock text-[#0b1215] pl-3"></i>
            <input
              v-model="form.password"
              type="password"
              name="password"
              id="password"
              autocomplete="new-password" 
              class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2"
              placeholder="Enter Password:"
              required
            >
          </div>
        </div>

        <div class="mb-4 relative">
          <label for="retype_password" class="block text-sm font-medium mb-1">Retype Password</label>
          <div class="flex items-center border border-gray-300 rounded-md">
            <i class="fas fa-lock text-[#0b1215] pl-3"></i>
            <input
              v-model="form.password_confirmation"
              type="password"
              id="retype_password"
              class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2"
              placeholder="Re-Enter Password:"
              required
              @focus="touchConfirmPassword"
              autocomplete="new-password"
            >
          </div>
          <p v-if="passwordMismatch" class="text-red-500 text-sm">
            Passwords do not match.
          </p>
        </div>

        <div class="mb-4">
          <label for="account_type" class="block text-sm font-medium mb-1">Select Account Type</label>
          <select
            v-model="form.account_type"
            id="account_type"
            name="account_type"
            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="employer">Employer</option>
            <option value="freelance">freelancer</option>
          </select>
        </div>
        <div class="flex justify-center">
          <button
            type="submit"
            class="w-full py-2 bg-[#334EAC] hover:bg-[#334dace6] text-white font-semibold rounded-md hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 ease-in-out"
            :disabled="passwordMismatch"
          >
            Submit
          </button>
        </div>
     
      </form>
    </div>
  </div>
</template>
<script setup>
import { computed , ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Navigational from '../../components/NavGuest.vue'; 

const form = useForm({
  email: null,
  password: null,
  password_confirmation: null,
  account_type: 'freelance',
});

const confirmPasswordTouched = ref(false);

const passwordMismatch = computed(() => {
  return confirmPasswordTouched.value && form.password !== form.password_confirmation;
});

function submit() {
  if (!passwordMismatch.value) {
    form.post(route('users.store'), {
      onSuccess: () => {
        form.reset();
      },
    });
  }
}

function touchConfirmPassword() {
  confirmPasswordTouched.value = true;
}
</script>