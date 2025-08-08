<script setup>
import { ref, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';
import QRCode from 'qrcode';

const props = defineProps({
    show: Boolean,
    trackingCode: String,
    dateSubmitted: String
});

const emit = defineEmits(['close']);
const qrCodeCanvas = ref(null);

onMounted(() => {
    if (props.show && props.trackingCode) {
        QRCode.toCanvas(qrCodeCanvas.value, props.trackingCode,
            { width: 200, margin: 1 },
            (error) => {
                if (error) console.error(error);
            }
        );
    }
});

const downloadQRCode = () => {
    const link = document.createElement('a');
    link.download = `aquatrack-report-${props.trackingCode}.png`;
    link.href = qrCodeCanvas.value.toDataURL('image/png');
    link.click();
};

const formattedDate = new Date(props.dateSubmitted).toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
});
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mt-3 text-lg font-medium text-gray-900">Report Submitted Successfully!</h3>

                <div class="mt-4 text-sm text-gray-600 space-y-2">
                    <p><span class="font-medium">Tracking Code:</span> {{ trackingCode }}</p>
                    <p><span class="font-medium">Date Submitted:</span> {{ formattedDate }}</p>
                </div>

                <div class="mt-6 flex justify-center">
                    <canvas ref="qrCodeCanvas" class="border border-gray-200 p-2 rounded-lg"></canvas>
                </div>

                <div class="mt-6 bg-blue-50 p-3 rounded-md text-sm text-blue-700">
                    <p class="font-medium">Important:</p>
                    <p>Please save this QR code for tracking your report. You won't be able to retrieve it again.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button
                    @click="downloadQRCode"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <v-icon name="bi-download" class="mr-2" />
                    Download QR Code
                </button>
                <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    @click="$emit('close')"
                >
                    Close
                </button>
            </div>
        </div>
    </Modal>
</template>
