<template>
    <Dialog :open="isOpen" @close="onClose" class="relative z-50">
        <div class="fixed inset-0 bg-black/30" aria-hidden="true" />

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <DialogPanel
                class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
            >
                <DialogTitle class="text-lg font-medium text-gray-900">
                    {{ title }}
                </DialogTitle>

                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        {{ message }}
                    </p>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button
                        @click="onClose"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Cancel
                    </button>
                    <button
                        @click="onConfirm"
                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:cursor-pointer hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Confirm
                    </button>
                </div>
            </DialogPanel>
        </div>
    </Dialog>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';

defineProps<{
    isOpen: boolean;
    title: string;
    message: string;
}>();

const emit = defineEmits<{
    close: [];
    confirm: [];
}>();

const onClose = () => {
    emit('close');
};

const onConfirm = () => {
    emit('confirm');
};
</script>
