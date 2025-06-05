<template>
    <div class="h-[100vh] w-[100vw] flex flex-col justify-center items-center px-2 ">
        <div class="absolute top-0 left-0 w-full">
          <Navigational />
        </div>
        <div 
        class="border border-gray-300 rounded-lg p-5 shadow-md bg-white 
        w-[clamp(350px,50vw,400px)]
        h-[auto] 
        ">
        <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>
        <form @submit.prevent="submit" >
            <div v-if="$page.props.flash.error"  class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">  {{ $page.props.flash.error }}</span>
            </div>
            <div class="mb-4 relative">
                <label for="edit_number" class="block text-sm font-medium mb-2">Email</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <i class="fas fa-envelope text-[#0b1215] pl-3"></i>
                    <input 
                        v-model="form.email"
                        type="email" 
                        name="email" 
                        autocomplete="username"
                        class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2" 
                        placeholder="Enter Email:" 
                        required>
                </div>
            </div>
            <div class="mb-4 relative">
                <label for="edit_address" class="block text-sm font-medium mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <i class="fas fa-lock text-[#0b1215] pl-3"></i>
                    <input
                        v-model="form.password" 
                        type="password"  
                        name="password"
                        autocomplete="current-password" 
                        class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2" 
                        placeholder="Enter Password:" 
                        required>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-full py-2 bg-[#334EAC] hover:bg-[#334dace6] text-white font-semibold rounded-md  hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 ease-in-out">Submit</button>
            </div>
           
        </form>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import Navigational from '../../components/NavGuest.vue'; // Still import it if you plan to use it

const form = useForm({
  email: null,
  password: null,
})

function submit() {
    form.post(route('users.authenticate'))
}
</script>

