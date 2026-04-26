<template>
  <CheckIcon v-if="type === 'success'" :colorClass="iconClasses" :width="props.width" :height="props.height" />
  <ErrorIcon v-else :colorClass="iconClasses" :width="props.width" :height="props.height" />
  <span class="sr-only">{{ iconSr }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import CheckIcon from '@/icons/CheckIcon.vue';
import ErrorIcon from '@/icons/ErrorIcon.vue';

interface Props {
  type: 'success' | 'error';
  width?: number | string;
  height?: number | string;
}

const props = withDefaults(defineProps<Props>(), {
  width: 24,
  height: 24,
});

const iconClasses = computed(() => {
  return props.type === 'success' ? 'text-green-800' : 'text-red-800';
});

const iconSr = computed(() => {
  return props.type === 'success' ? 'Check icon' : 'Error icon';
});
</script>