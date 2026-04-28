import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import PieChart from './PieChart.vue';

const agChartsSpy = vi.fn();

vi.mock('ag-charts-vue3', () => ({
    AgCharts: {
        props: ['options'],
        template: '<div data-testid="ag-chart"></div>',
        mounted(this: { options: unknown }) {
            agChartsSpy(this.options);
        },
    },
}));

describe('PieChart', () => {
    it('maps title and data into chart options', () => {
        mount(PieChart, {
            props: {
                title: 'Top Products',
                data: [
                    { name: 'Item A', total_sold: 5 },
                    { name: 'Item B', total_sold: 2 },
                ],
            },
        });

        expect(agChartsSpy).toHaveBeenCalled();
        const options = agChartsSpy.mock.calls[0][0];
        expect(options.title.text).toBe('Top Products');
        expect(options.data).toHaveLength(2);
    });
});
