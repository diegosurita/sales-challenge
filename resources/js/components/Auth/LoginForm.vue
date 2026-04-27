<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const page = usePage();

const form = reactive({
    email: '',
    password: '',
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
        delete page.props.errors.failed;
    }
};
</script>

<template>
    <section
        class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6"
    >
        <form
            action="/auth/login"
            method="post"
            class="mx-auto flex w-full max-w-md flex-col items-center gap-4"
            @submit.prevent="onSubmit"
        >
            <div
                v-if="page.props.errors?.failed"
                class="mb-4 w-full rounded bg-red-50 p-2 text-sm text-red-500"
            >
                {{ page.props.errors.failed }}
            </div>
            <div class="w-full">
                <label
                    for="email"
                    class="mb-1 block text-sm font-medium text-slate-700"
                >
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="you@example.com"
                    required
                    @input="resetFieldError('email')"
                />
                <p
                    v-if="page.props.errors?.email"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.email }}
                </p>
            </div>

            <div class="w-full">
                <label
                    for="password"
                    class="mb-1 block text-sm font-medium text-slate-700"
                >
                    Password
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 transition outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="••••••••"
                    required
                    @input="resetFieldError('password')"
                />
                <p
                    v-if="page.props.errors?.password"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ page.props.errors.password }}
                </p>
            </div>

            <button
                type="submit"
                class="mt-1 inline-flex w-full items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:cursor-pointer hover:bg-sky-700 focus-visible:ring-2 focus-visible:ring-sky-300 focus-visible:outline-none"
            >
                Login
            </button>
        </form>
    </section>
</template>
