<template>
    <footer
        class="bg-gray-900 text-gray-300 py-12 mt-16 border-t border-transparent bg-gradient-to-t from-gray-900 to-gray-800"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8"
        >
            <div>
                <h3 class="text-xl font-semibold text-white mb-4">
                    nextCareer
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Your trusted remote-work marketplace connecting talented
                    freelancers and innovative companies worldwide.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">For Freelancers</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Find Work</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Guides & Tips</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Community</a
                        >
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">For Employers</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Hire Talent</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Success Stories</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Pricing</a
                        >
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Connect</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >About Us</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Contact</a
                        >
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition"
                            >Privacy Policy</a
                        >
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="mt-12 border-t border-gray-800 pt-6 text-center text-sm text-gray-500"
        >
            © {{ currentYear }} nextCareer. All rights reserved.
        </div>
    </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear();
</script>
