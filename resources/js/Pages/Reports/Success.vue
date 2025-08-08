<!-- resources/js/Pages/Reports/Success.vue -->
<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ReportSuccessModal from '@/Components/Modals/ReportSuccessModal.vue';

const props = defineProps({
    trackingCode: String,
    dateSubmitted: String
});

const showModal = ref(true);

const closeModal = () => {
    showModal.value = false;
    window.location.href = '/';
};
</script>

<template>

    <Head title="Report Submitted" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <ReportSuccessModal :show="showModal" :tracking-code="trackingCode" :date-submitted="dateSubmitted"
            @close="closeModal" />
    </div>
</template>
