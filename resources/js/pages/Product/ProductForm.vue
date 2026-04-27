<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';

interface Props {
    product?: { id: number; name: string; price: number };
}

const props = defineProps<Props>();

const page = usePage();

const isEditMode = computed(() => !!props.product);

const form = reactive({
    name: props.product?.name || '',
    price: props.product?.price?.toString() || '',
});

const onSubmit = (event: SubmitEvent) => {
    const target = event.target as HTMLFormElement;

    if (!target.checkValidity()) {
        target.reportValidity();

        return;
    }

    target.submit();
};

const resetFieldError = (field: string) => {
    if (page.props.errors) {
        delete page.props.errors[field];
    }
};
</script>

<template>
    <Head :title="isEditMode ? 'Edit Product' : 'Create Product'" />

    <InternalBaseLayout :title="isEditMode ? 'Edit Product' : 'Create Product'">
        <form
            :action="
                isEditMode ? `/products/${props.product!.id}` : '/products'
            "
            method="post"
            class="max-w-md space-y-4"
            @submit.prevent="onSubmit"
        >
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-slate-700"
                >
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    name="name"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="Product name"
                    @input="resetFieldError('name')"
                    required
                />
                <p
                    v-if="page.props.errors?.name"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.name }}
                </p>
            </div>

            <div>
                <label
                    for="price"
                    class="block text-sm font-medium text-slate-700"
                >
                    Price
                </label>
                <input
                    id="price"
                    v-model="form.price"
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="0.00"
                    @input="resetFieldError('price')"
                    required
                />
                <p
                    v-if="page.props.errors?.price"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.price }}
                </p>
            </div>

            <input v-if="isEditMode" type="hidden" name="_method" value="PUT" />

            <hr class="my-4 border-slate-200" />

            <div class="flex space-x-3">
                <button
                    type="button"
                    @click="router.visit('/products')"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:cursor-pointer hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                >
                    Save
                </button>
            </div>
        </form>
    </InternalBaseLayout>
</template>
