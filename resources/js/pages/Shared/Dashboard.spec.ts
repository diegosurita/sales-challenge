import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import Dashboard from './Dashboard.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

vi.mock('ag-charts-vue3', () => ({
    AgCharts: {
        template: '<div data-testid="revenue-chart" />',
    },
}));

describe('Dashboard page', () => {
    it('renders current revenue and empty state message', () => {
        const wrapper = mount(Dashboard, {
            props: {
                currentMonthRevenue: 1200,
                monthlyRevenue: [{ month: 'Jan', revenue: 1200 }],
                topClients: [],
                topProducts: [],
                topServices: [],
            },
            global: {
                stubs: {
                    InternalBaseLayout: {
                        props: ['title'],
                        template: '<section><slot /></section>',
                    },
                    PieChart: {
                        template: '<div data-testid="pie-chart" />',
                    },
                },
            },
        });

        expect(wrapper.text()).toContain('$1,200.00');
        expect(wrapper.text()).toContain('No sales data yet.');
        expect(wrapper.find('[data-testid="revenue-chart"]').exists()).toBe(true);
    });
});
