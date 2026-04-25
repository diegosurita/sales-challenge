<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import DeleteIcon from '@/icons/DeleteIcon.vue';
import EditIcon from '@/icons/EditIcon.vue';

interface Props {
  params: any;
}

const props = defineProps<Props>();

const handleEdit = () => {
  router.visit(`/clients/${props.params.data.id}/edit`);
};

const handleDelete = () => {
  if (props.params.context?.onDelete) {
    props.params.context.onDelete(props.params.data.id);
  }
};
</script>

<template>
  <div class="flex space-x-1">
    <button
      @click="handleEdit"
      class="inline-flex items-center justify-center rounded p-1 text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
      title="Edit"
    >
      <EditIcon />
    </button>
    <button
      @click="handleDelete"
      class="inline-flex items-center justify-center rounded p-1 text-red-600 hover:bg-red-100 hover:text-red-900 focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
      title="Delete"
    >
      <DeleteIcon />
    </button>
  </div>
</template>