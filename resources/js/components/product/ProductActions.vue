<script setup lang="ts">
interface Props {
    params: any;
}

const props = defineProps<Props>();

const handleDelete = () => {
    if (props.params.context?.onDelete) {
        props.params.context.onDelete(props.params.data.id);
    }
};
</script>

<template>
    <div class="flex space-x-1">
        <a
            :href="`/products/${props.params.data.id}/edit`"
            class="inline-flex items-center justify-center rounded p-1 text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
            title="Edit"
        >
            <i class="pi pi-pencil"></i>
        </a>
        <button
            @click="handleDelete"
            class="inline-flex items-center justify-center rounded p-1 text-red-600 hover:cursor-pointer hover:bg-red-100 hover:text-red-900 focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
            title="Delete"
        >
            <i class="pi pi-trash"></i>
        </button>
    </div>
</template>