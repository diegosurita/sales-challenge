<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';
import InternalBaseLayout from '@/components/shared/InternalBaseLayout.vue';

const page = usePage();

const form = reactive({
    name: '',
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
    <Head title="Create Client" />

    <InternalBaseLayout title="Create Client">
        <form action="/clients" method="post" class="max-w-md space-y-4" @submit.prevent="onSubmit">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    name="name"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="Client name"
                    @input="resetFieldError('name')"
                    required
                />
                <p v-if="page.props.errors?.name" class="text-red-500 text-sm mt-1">{{ page.props.errors.name }}</p>
            </div>

            <hr class="border-slate-200 my-4" />

            <div class="flex space-x-3">
                <button
                    type="button"
                    @click="router.visit('/clients')"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 hover:cursor-pointer"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 hover:bg-sky-700 hover:cursor-pointer"
                >
                    Save
                </button>
            </div>
        </form>
    </InternalBaseLayout>
</template>