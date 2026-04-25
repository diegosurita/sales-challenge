<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
import type { ColDef, GridOptions } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import ClientActions from '@/components/client/ClientActions.vue';
import InternalBaseLayout from '@/components/shared/InternalBaseLayout.vue';
import Toast from '@/components/shared/Toast.vue';

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

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
    context: {
        onDelete: (id: number) => {
            // Placeholder for delete action
            console.log('Delete client', id);
            alert(`Delete client ${id}?`);
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

        <Toast :message="successMessage" type="success" />

        <AgGridVue
            :columnDefs="columnDefs"
            :rowData="rowData"
            :gridOptions="gridOptions"
            class="ag-theme-alpine"
        />
    </InternalBaseLayout>
</template>