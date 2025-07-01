<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Navbar from '@/Components/Header/Navigation.vue';

</script>

<template>
    <Navbar>


        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-lg shadow-lg border border-gray-300 p-8">
                <div class="text-center mb-6">

                    <ApplicationLogo class="h-20 w-20 fill-current text-blue-600 mx-auto" />

                </div>
                <slot />
            </div>
        </div>
    </Navbar>
</template>
