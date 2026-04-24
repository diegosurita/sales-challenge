<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { onClickOutside, onKeyStroke } from '@vueuse/core';
import { ref } from 'vue';
import { logout as logoutAction } from '../../actions/Module/Auth/Interface/Controllers/AuthenticationController';

defineProps<{
    title: string;
}>();

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement>();

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const closeDropdown = () => {
    isOpen.value = false;
};

const handleLogout = () => {
    router.post(logoutAction.url());
};

onClickOutside(dropdownRef, closeDropdown);
onKeyStroke('Escape', closeDropdown);
</script>

<template>
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ title }}</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative" ref="dropdownRef">
                    <button @click="toggleDropdown"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-600 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                        aria-haspopup="menu" :aria-expanded="isOpen">
                        SC
                    </button>

                    <div v-show="isOpen"
                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical">
                        <button
                            class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none hover:cursor-pointer"
                            role="menuitem" @click="handleLogout">
                            Sign out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>