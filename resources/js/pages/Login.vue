<template>
    <div class="h-[100vh] w-[100vw] flex justify-center items-center px-2 bg-[#FEF4F1]">
        <div 
        class="border border-gray-300 rounded-lg p-5 shadow-md bg-white 
        w-[clamp(350px,50vw,400px)]
        h-[auto] 
        ">
        <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>
        <form >
            <div class="mb-4 relative">
                <label for="edit_number" class="block text-sm font-medium mb-2">Email</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <i class="fas fa-envelope text-[#0b1215] pl-3"></i>
                    <input type="text" name="email" class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2" placeholder="Enter Email:" required>
                    <input type="hidden" name="login" required>
                </div>
            </div>
            <div class="mb-4 relative">
                <label for="edit_address" class="block text-sm font-medium mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <i class="fas fa-lock text-[#0b1215] pl-3"></i>
                    <input type="password"  name="password" class="text-sm p-2 w-full border-none focus:outline-none focus:ring-1 focus:ring-blue-300 pl-2" placeholder="Enter Password:" autocomplete="on" required>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-full py-2 bg-[#0b1215] hover:bg-[#2b2b2c] text-white font-semibold rounded-md  hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 ease-in-out">Submit</button>
            </div>
        </form>
        </div>
    </div>
</template>