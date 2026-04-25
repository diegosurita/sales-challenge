<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
import type { ColDef, GridOptions } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import InternalBaseLayout from '@/components/shared/InternalBaseLayout.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

interface Client {
    id: number;
    name: string;
}

const props = defineProps<{
    clients: Client[];
}>();

const columnDefs: ColDef<Client>[] = [
    { field: 'name', headerName: 'Name', sortable: true, filter: true, flex: 1 },
];

const rowData = props.clients;

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
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

        <AgGridVue
            :columnDefs="columnDefs"
            :rowData="rowData"
            :gridOptions="gridOptions"
            class="ag-theme-alpine"
        />
    </InternalBaseLayout>
</template>