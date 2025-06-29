<template>
    <div class="flex items-center gap-1 sm:gap-2 md:gap-3">
        <div class="flex items-center group">
            <!-- Responsive logo image -->
            <img src="/images/MainLogo.png" alt="AquaTrack Logo"
                class="h-10 w-10 sm:h-16 sm:w-16 md:h-20 md:w-20 lg:h-24 lg:w-24 object-contain rounded-full" />

            <!-- Text container with responsive breakpoints -->
            <span v-if="$slots.default"
                class="ml-1 sm:ml-2 text-sm xs:text-base sm:text-lg md:text-xl lg:text-2xl font-bold text-white">
                <slot></slot>
            </span>

            <!-- Fallback text with responsive breakpoints -->
            <span v-else
                class="ml-1 sm:ml-2 text-sm xs:text-base sm:text-lg md:text-xl lg:text-2xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">
                AquaTrack
            </span>
        </div>
    </div>
</template>
