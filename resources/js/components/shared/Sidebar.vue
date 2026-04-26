<template>
    <aside class="border-b border-slate-800 bg-slate-900 text-slate-100 lg:border-r lg:border-b-0">
        <div class="px-6 py-5">
            <p class="text-xs font-semibold tracking-[0.16em] text-sky-300">
                SALES CHALLENGE
            </p>
        </div>

        <div class="mx-3 border-t border-slate-800/80"></div>

        <nav class="flex flex-col gap-1 px-3 py-5">
            <a
                href="/dashboard"
                :class="currentPath === '/dashboard' ? 'flex items-center rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-white' : 'flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white'"
            >
                <i class="pi pi-chart-line mr-2"></i>
                Dashboard
            </a>
            <a
                href="/clients"
                :class="currentPath === '/clients' ? 'flex items-center rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-white' : 'flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white'"
            >
                <i class="pi pi-users mr-2"></i>
                Clients
            </a>
        </nav>
    </aside>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const currentPath = computed(() => page.url)
</script>