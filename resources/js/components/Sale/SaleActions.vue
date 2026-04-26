<script setup lang="ts">

interface Props {
  params: any;
}

const props = defineProps<Props>();
</script>

<template>
  <div class="flex space-x-1">
    <a
      :href="`/sales/${props.params.data.id}`"
      class="inline-flex items-center justify-center rounded p-1 text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
      title="View"
    >
      <i class="pi pi-eye"></i>
    </a>
  </div>
</template>