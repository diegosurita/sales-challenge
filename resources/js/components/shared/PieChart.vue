<script setup lang="ts">
import type { AgChartOptions } from 'ag-charts-community';
import { AgCharts } from 'ag-charts-vue3';
import { computed } from 'vue';

const props = defineProps<{
    data: Array<{ name: string; total_sold: number }>;
    title: string;
}>();

const options = computed<AgChartOptions>(() => ({
    title: {
        text: props.title,
    },
    data: props.data,
    series: [
        {
            type: 'pie',
            calloutLabelKey: 'name',
            angleKey: 'total_sold',
            sectorLabel: {
                enabled: true,
                key: 'total_sold',
            },
        },
    ],
    legend: {
        enabled: true,
    },
}));
</script>

<template>
    <AgCharts :options="options" class="h-64 w-full" />
</template>
