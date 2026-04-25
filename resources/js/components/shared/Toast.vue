<template>
    <div v-if="isVisible && message" class="fixed bottom-4 right-4 z-50 flex items-center p-4 text-sm border rounded-lg"
        :class="toastClasses" role="alert">
        <ToastIcon :type="type" />
        <div>{{ message }}</div>
        <button @click="close"
            class="ml-auto -mx-1.5 -my-1.5 bg-transparent text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-transparent dark:hover:bg-gray-700"
            aria-label="Close">
            <span class="sr-only">Close</span>
            <CloseIcon />
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import CloseIcon from '@/icons/CloseIcon.vue';
import ToastIcon from '@/icons/ToastIcon.vue';

interface Props {
    message?: string;
    type?: 'success' | 'error';
    dismissTime?: number;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'success',
    dismissTime: 5000,
});

const isVisible = ref(false);

const close = () => {
    isVisible.value = false;
};

watch(() => props.message, (newMessage) => {
    if (newMessage) {
        isVisible.value = true;

        if (props.dismissTime > 0) {
            setTimeout(() => {
                isVisible.value = false;
            }, props.dismissTime);
        }
    }
}, { immediate: true });

const toastClasses = computed(() => {
    if (props.type === 'success') {
        return 'text-green-800 border-green-300 bg-green-50';
    } else {
        return 'text-red-800 border-red-300 bg-red-50';
    }
});
</script>