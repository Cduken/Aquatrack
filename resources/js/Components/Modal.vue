<template>
    <Transition name="modal">
        <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" @click="close"></div>
                </div>

                <!-- Modal container -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                    <!-- Modal header -->
                    <div class="bg-blue-600 px-4 py-3 sm:px-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <slot name="title"></slot>
                        </div>
                        <button @click="close" class="text-white hover:text-gray-200 focus:outline-none">
                            <v-icon name="bi-x" scale="1.5" />
                        </button>
                    </div>

                    <!-- Modal content -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <slot></slot>
                    </div>

                    <!-- Modal footer (optional) -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" v-if="$slots.footer">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
defineProps({
    show: Boolean
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};
</script>

<style>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
