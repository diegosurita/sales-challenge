<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { AllCommunityModule, ModuleRegistry } from 'ag-grid-community';
import type { ColDef, GridOptions, ValueFormatterParams } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import { ref } from 'vue';
import { destroy } from '@/actions/Module/Service/Interface/Controllers/ServiceController';
import ServiceActions from '@/components/Service/ServiceActions.vue';
import ConfirmationModal from '@/components/Shared/ConfirmationModal.vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';
import Toast from '@/components/Shared/Toast.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

interface Service {
    id: number;
    name: string;
    price: number;
}

const props = defineProps<{
    services: Service[];
    successMessage?: string;
}>();

const columnDefs: ColDef<Service>[] = [
    { field: 'name', headerName: 'Name', sortable: true, filter: true, flex: 1 },
    {
        field: 'price',
        headerName: 'Price',
        sortable: true,
        filter: true,
        width: 150,
        valueFormatter: (params: ValueFormatterParams<Service, number>) => {
            const price = Number(params.value ?? 0);

            return `$${price.toFixed(2)}`;
        },
    },
    { headerName: 'Actions', cellRenderer: ServiceActions, width: 100, sortable: false, filter: false },
];

const rowData = props.services;

const isModalOpen = ref(false);
const serviceToDelete = ref<number | null>(null);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

const closeModal = () => {
    isModalOpen.value = false;
    serviceToDelete.value = null;
};

const confirmDelete = async () => {
    if (!serviceToDelete.value) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(destroy(serviceToDelete.value).url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            if (response.status === 404) {
                throw new Error('Service not found.');
            } else {
                throw new Error('Failed to delete service.');
            }
        }

        (window as any).navigation.reload();
    } catch (error: any) {
        toastMessage.value = error.message || 'Failed to delete service.';
        toastType.value = 'error';
    } finally {
        closeModal();
    }
};

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
    context: {
        onDelete: (id: number) => {
            serviceToDelete.value = id;
            isModalOpen.value = true;
        },
    },
};
</script>

<template>
    <Head title="Services" />

    <InternalBaseLayout title="Services">
        <div class="mb-4 flex justify-end">
            <a
                href="/services/create"
                class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:cursor-pointer hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
            >
                New
            </a>
        </div>

        <Toast :message="toastMessage || successMessage" :type="toastType" />

        <AgGridVue
            :columnDefs="columnDefs"
            :rowData="rowData"
            :gridOptions="gridOptions"
            class="ag-theme-alpine"
        />

        <ConfirmationModal
            :is-open="isModalOpen"
            title="Delete Service"
            message="Are you sure you want to delete this service? This action cannot be undone."
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </InternalBaseLayout>
</template>
