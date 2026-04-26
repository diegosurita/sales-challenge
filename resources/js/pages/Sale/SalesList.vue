<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
import type { ColDef, GridOptions } from 'ag-grid-community';
import { AgGridVue } from 'ag-grid-vue3';
import { ref } from 'vue';
import SaleActions from '@/components/Sale/SaleActions.vue';
import ConfirmationModal from '@/components/Shared/ConfirmationModal.vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';
import Toast from '@/components/Shared/Toast.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

interface Sale {
    id: number;
    client_name: string;
    status: string;
    created_at: string;
}

const props = defineProps<{
    sales: Sale[];
    successMessage?: string;
}>();

const columnDefs: ColDef<Sale>[] = [
    { field: 'client_name', headerName: 'Client', sortable: true, filter: true, flex: 1 },
    { field: 'status', headerName: 'Status', sortable: true, filter: false, flex: 1 },
    {
        field: 'created_at',
        headerName: 'Created At',
        sortable: true,
        filter: 'agDateColumnFilter',
        valueFormatter: ({ value }: { value: string }) => {
            if (!value) {
                return '';
            }

            const d = new Date(value);
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            const yyyy = d.getFullYear();
            const HH = String(d.getHours()).padStart(2, '0');
            const ii = String(d.getMinutes()).padStart(2, '0');
            const ss = String(d.getSeconds()).padStart(2, '0');

            return `${mm}/${dd}/${yyyy} ${HH}:${ii}:${ss}`;
        },
        filterParams: {
            filterOptions: ['inRange'],
            defaultOption: 'inRange',
            inRangeInclusive: true,
            comparator: (filterDate: Date, cellValue: string) => {
                if (!cellValue) {
                    return 0;
                }

                const cellDate = new Date(cellValue);

                if (cellDate < filterDate) {
                    return -1;
                }

                if (cellDate > filterDate) {
                    return 1;
                }

                return 0;
            },
        },
        flex: 1,
    },
    { headerName: 'Actions', cellRenderer: SaleActions, width: 100, sortable: false, filter: false },
];

const rowData = props.sales;

const isModalOpen = ref(false);
const saleToDelete = ref<number | null>(null);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

const closeModal = () => {
    isModalOpen.value = false;
    saleToDelete.value = null;
};

const confirmDelete = async () => {
    if (!saleToDelete.value) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(`/sales/${saleToDelete.value}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            if (response.status === 404) {
                throw new Error('Sale not found.');
            } else {
                throw new Error('Failed to delete sale.');
            }
        }

        (window as any).navigation.reload();
    } catch (error: any) {
        toastMessage.value = error.message || 'Failed to delete sale.';
        toastType.value = 'error';
    } finally {
        closeModal();
    }
};

const gridOptions: GridOptions = {
    domLayout: 'autoHeight',
    context: {
        onDelete: (id: number) => {
            saleToDelete.value = id;
            isModalOpen.value = true;
        },
    },
};
</script>

<template>

    <Head title="Sales" />

    <InternalBaseLayout title="Sales">
        <div class="flex justify-end mb-4">
            <a href="/sales/create"
                class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 hover:cursor-pointer hover:bg-sky-700">
                New
            </a>
        </div>

        <Toast :message="toastMessage || successMessage" :type="toastType" />

        <AgGridVue :columnDefs="columnDefs" :rowData="rowData" :gridOptions="gridOptions" class="ag-theme-alpine" />

        <ConfirmationModal :is-open="isModalOpen" title="Delete Sale"
            message="Are you sure you want to delete this sale? This action cannot be undone." @close="closeModal"
            @confirm="confirmDelete" />
    </InternalBaseLayout>
</template>