<template>
    <section id="about" class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1500px] mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                <!-- Left Column - Text Content -->
                <div class="lg:w-1/2">
                    <h2
                        class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 sm:mb-8 text-left">
                        About AquaTrack</h2>
                    <div class="space-y-4 sm:space-y-6">
                        <p class="text-base sm:text-lg lg:text-xl text-gray-600 text-left">
                            AquaTrack is a comprehensive water management platform designed specifically for residential
                            customers. Our mission is to make water consumption tracking simple, transparent, and
                            efficient.
                        </p>
                        <p class="text-base sm:text-lg lg:text-xl text-gray-600 text-left">
                            With features like real-time usage monitoring, digital bill payments, and instant issue
                            reporting, we're revolutionizing how communities interact with their water services.
                        </p>
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3 mt-0.5">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-blue-500"></div>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-600">24/7 Real-time Water Usage
                                    Monitoring</p>
                            </div>
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3 mt-0.5">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-blue-500"></div>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-600">Instant Issue Reporting &
                                    Tracking</p>
                            </div>
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3 mt-0.5">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-blue-500"></div>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-600">Digital Bill Management</p>
                            </div>
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3 mt-0.5">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-blue-500"></div>
                                </div>
                                <p class="text-base sm:text-lg lg:text-xl text-gray-600">Community Announcements</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Placeholder -->
                <div class="lg:w-1/2 hidden lg:block">
                    <div class="rounded-xl h-full min-h-[500px] lg:min-h-[600px] flex items-center justify-center">
                        <img src="/images/MainLogo.png" alt="AquaTrack Logo"
                            class="max-w-full max-h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
// No need to import anything - icons are registered globally
</script>
