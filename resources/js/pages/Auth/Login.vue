<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LoginForm from '../../components/login/LoginForm.vue';
</script>

<template>
    <Head title="Login" />

    <div
        class="flex min-h-screen items-center justify-center px-4 py-8 text-slate-900"
    >
        <main
            class="w-full max-w-4xl rounded-2xl bg-white/95 p-6 backdrop-blur sm:p-8"
        >
            <div class="mx-auto flex w-full max-w-3xl flex-col items-center gap-6">
                <header class="w-full text-center">
                    <p class="text-xs font-semibold tracking-[0.14em] text-sky-700">
                        SALES CHALLENGE
                    </p>
                    <h1 class="mt-2 text-2xl font-bold text-slate-900 sm:text-3xl">
                        Welcome
                    </h1>
                </header>

                <LoginForm class="w-full" />
            </div>
        </main>
    </div>
</template>