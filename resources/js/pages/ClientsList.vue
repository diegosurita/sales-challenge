<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
import type { ColDef, GridOptions } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import InternalBaseLayout from '@/components/shared/InternalBaseLayout.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

interface Client {
    name: string;
    email: string;
    phone: string;
    status: string;
}

const columnDefs: ColDef<Client>[] = [
    { field: 'name', headerName: 'Name', sortable: true, filter: true, flex: 1 },
    { field: 'email', headerName: 'Email', sortable: true, filter: true, flex: 1 },
    { field: 'phone', headerName: 'Phone', sortable: true, filter: true, flex: 1 },
    { field: 'status', headerName: 'Status', sortable: true, filter: true, flex: 1 },
];

const rowData: Client[] = [
    { name: 'John Doe', email: 'john@example.com', phone: '123-456-7890', status: 'Active' },
    { name: 'Jane Smith', email: 'jane@example.com', phone: '098-765-4321', status: 'Inactive' },
    { name: 'Bob Johnson', email: 'bob@example.com', phone: '555-123-4567', status: 'Active' },
];

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
};
</script>

<template>
    <Head title="Clients" />

    <InternalBaseLayout title="Clients">
        <div class="flex justify-end mb-4">
            <button class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                New
            </button>
        </div>

        <AgGridVue
            :columnDefs="columnDefs"
            :rowData="rowData"
            :gridOptions="gridOptions"
            class="ag-theme-alpine"
        />
    </InternalBaseLayout>
</template>