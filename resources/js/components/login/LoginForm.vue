<script setup lang="ts">
import { reactive } from 'vue';

const form = reactive({
    email: '',
    password: '',
});

const onSubmit = () => {
    void form;
};
</script>

<template>
    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
        <form
            class="mx-auto flex w-full max-w-md flex-col items-center gap-4"
            @submit.prevent="onSubmit"
        >
            <div class="w-full">
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="you@example.com"
                />
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
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                    placeholder="••••••••"
                />
            </div>

            <button
                type="submit"
                class="mt-1 inline-flex w-full items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-300 hover:cursor-pointer"
            >
                Login
            </button>
        </form>
    </section>
</template>