<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type {
    AgCartesianAxisOptions,
    AgChartOptions,
} from 'ag-charts-community';
import { AgCharts } from 'ag-charts-vue3';
import { computed } from 'vue';
import InternalBaseLayout from '@/components/Shared/InternalBaseLayout.vue';
import PieChart from '@/components/Shared/PieChart.vue';
const props = defineProps<{
    currentMonthRevenue: number;
    monthlyRevenue: Array<{ month: string; revenue: number }>;
    topClients: Array<{ name: string; total_sales_value: number }>;
    topProducts: Array<{ name: string; total_sold: number }>;
    topServices: Array<{ name: string; total_sold: number }>;
}>();

const formattedCurrentMonthRevenue = computed(() =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(props.currentMonthRevenue),
);

const revenueChartOptions = computed(
    () =>
        ({
            title: {
                text: 'Monthly Revenue – Last 12 Months',
            },
            data: props.monthlyRevenue,
            series: [
                {
                    type: 'line',
                    xKey: 'month',
                    yKey: 'revenue',
                    yName: 'Revenue',
                    marker: { enabled: true },
                },
            ],
            axes: [
                {
                    type: 'category',
                    position: 'bottom',
                } as AgCartesianAxisOptions,
                {
                    type: 'number',
                    position: 'left',
                    label: {
                        formatter: ({ value }: { value: number }) =>
                            new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                maximumFractionDigits: 0,
                            }).format(value),
                    },
                } as AgCartesianAxisOptions,
            ],
        }) as unknown as AgChartOptions,
);
</script>

<template>
    <Head title="Dashboard" />

    <InternalBaseLayout title="Dashboard">
        <div class="flex flex-col gap-6">
            <!-- KPI: Current Month Revenue -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-medium text-slate-500">
                    Revenue This Month
                </p>
                <p class="mt-1 text-3xl font-bold text-slate-900">
                    {{ formattedCurrentMonthRevenue }}
                </p>
            </div>

            <!-- Line Chart: 12-month Revenue -->
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <AgCharts :options="revenueChartOptions" class="h-72 w-full" />
            </div>

            <!-- Top Products -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <h2 class="mb-3 text-base font-semibold text-slate-800">
                        Top 5 Products
                    </h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-200 text-left text-slate-500"
                            >
                                <th class="pb-2 font-medium">#</th>
                                <th class="pb-2 font-medium">Product</th>
                                <th class="pb-2 text-right font-medium">
                                    Units Sold
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(product, index) in topProducts"
                                :key="product.name"
                                class="border-b border-slate-100 last:border-0"
                            >
                                <td class="py-2 text-slate-400">
                                    {{ index + 1 }}
                                </td>
                                <td class="py-2 font-medium text-slate-800">
                                    {{ product.name }}
                                </td>
                                <td class="py-2 text-right text-slate-600">
                                    {{ product.total_sold }}
                                </td>
                            </tr>
                            <tr v-if="topProducts.length === 0">
                                <td
                                    colspan="3"
                                    class="py-4 text-center text-slate-400"
                                >
                                    No sales data yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <PieChart
                        v-if="topProducts.length > 0"
                        :data="topProducts"
                        title="Top 5 Products"
                    />
                    <p v-else class="py-4 text-center text-sm text-slate-400">
                        No sales data yet.
                    </p>
                </div>
            </div>

            <!-- Top Services -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <h2 class="mb-3 text-base font-semibold text-slate-800">
                        Top 5 Services
                    </h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-200 text-left text-slate-500"
                            >
                                <th class="pb-2 font-medium">#</th>
                                <th class="pb-2 font-medium">Service</th>
                                <th class="pb-2 text-right font-medium">
                                    Times Sold
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(service, index) in topServices"
                                :key="service.name"
                                class="border-b border-slate-100 last:border-0"
                            >
                                <td class="py-2 text-slate-400">
                                    {{ index + 1 }}
                                </td>
                                <td class="py-2 font-medium text-slate-800">
                                    {{ service.name }}
                                </td>
                                <td class="py-2 text-right text-slate-600">
                                    {{ service.total_sold }}
                                </td>
                            </tr>
                            <tr v-if="topServices.length === 0">
                                <td
                                    colspan="3"
                                    class="py-4 text-center text-slate-400"
                                >
                                    No sales data yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <PieChart
                        v-if="topServices.length > 0"
                        :data="topServices"
                        title="Top 5 Services"
                    />
                    <p v-else class="py-4 text-center text-sm text-slate-400">
                        No sales data yet.
                    </p>
                </div>
            </div>

            <!-- Top Clients -->
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <h2 class="mb-3 text-base font-semibold text-slate-800">
                    Top 5 Clients by Sales Value
                </h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="border-b border-slate-200 text-left text-slate-500"
                        >
                            <th class="pb-2 font-medium">#</th>
                            <th class="pb-2 font-medium">Client</th>
                            <th class="pb-2 text-right font-medium">
                                Total Sales Value
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(client, index) in topClients"
                            :key="client.name"
                            class="border-b border-slate-100 last:border-0"
                        >
                            <td class="py-2 text-slate-400">{{ index + 1 }}</td>
                            <td class="py-2 font-medium text-slate-800">
                                {{ client.name }}
                            </td>
                            <td class="py-2 text-right text-slate-600">
                                {{
                                    new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD',
                                    }).format(client.total_sales_value)
                                }}
                            </td>
                        </tr>
                        <tr v-if="topClients.length === 0">
                            <td colspan="3" class="py-4 text-center text-slate-400">
                                No sales data yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </InternalBaseLayout>
</template>
