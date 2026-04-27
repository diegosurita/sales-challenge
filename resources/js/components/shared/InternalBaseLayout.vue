<script setup lang="ts">
import Sidebar from './Sidebar.vue';
import TopMenuBar from './TopMenuBar.vue';

defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-slate-100 text-slate-900">
        <div class="grid min-h-screen grid-cols-1 lg:grid-cols-[15.5rem_1fr]">
            <Sidebar />

            <div class="flex min-h-screen flex-col">
                <TopMenuBar :title="title" />

                <main class="mx-auto w-full flex-1 px-4 py-4">
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs sm:p-4">
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>