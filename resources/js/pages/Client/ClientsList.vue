<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
import type { ColDef, GridOptions } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import { ref } from 'vue';
import { destroy } from '@/actions/Module/Client/Interface/Controllers/ClientController';
import ClientActions from '@/components/Client/ClientActions.vue';
import ConfirmationModal from '@/components/Shared/ConfirmationModal.vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';
import Toast from '@/components/Shared/Toast.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

interface Client {
    id: number;
    name: string;
}

const props = defineProps<{
    clients: Client[];
    successMessage?: string;
}>();

const columnDefs: ColDef<Client>[] = [
    { field: 'name', headerName: 'Name', sortable: true, filter: true, flex: 1 },
    { headerName: 'Actions', cellRenderer: ClientActions, width: 100, sortable: false, filter: false },
];

const rowData = props.clients;

const isModalOpen = ref(false);
const clientToDelete = ref<number | null>(null);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

const closeModal = () => {
    isModalOpen.value = false;
    clientToDelete.value = null;
};

const confirmDelete = async () => {
    if (!clientToDelete.value) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(destroy(clientToDelete.value).url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            if (response.status === 404) {
                throw new Error('Client not found.');
            } else {
                throw new Error('Failed to delete client.');
            }
        }

        (window as any).navigation.reload();
    } catch (error: any) {
        toastMessage.value = error.message || 'Failed to delete client.';
        toastType.value = 'error';
    } finally {
        closeModal();
    }
};

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
    context: {
        onDelete: (id: number) => {
            clientToDelete.value = id;
            isModalOpen.value = true;
        },
    },
};
</script>

<template>
    <Head title="Clients" />

    <InternalBaseLayout title="Clients">
        <div class="flex justify-end mb-4">
            <a href="/clients/create" class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 hover:cursor-pointer hover:bg-sky-700">
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
            title="Delete Client"
            message="Are you sure you want to delete this client? This action cannot be undone."
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </InternalBaseLayout>
</template>