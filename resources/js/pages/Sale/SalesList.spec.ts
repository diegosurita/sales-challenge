import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import SalesList from './SalesList.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

describe('SalesList page', () => {
    it('renders create link and grid shell', () => {
        const wrapper = mount(SalesList, {
            props: {
                sales: [
                    {
                        id: 1,
                        client_name: 'Alice',
                        created_at: '2026-01-01T10:00:00Z',
                    },
                ],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    Toast: { template: '<div data-testid="toast" />' },
                    ConfirmationModal: { template: '<div data-testid="modal" />' },
                    AgGridVue: { template: '<div data-testid="grid" />' },
                },
            },
        });

        expect(wrapper.find('a[href="/sales/create"]').exists()).toBe(true);
        expect(wrapper.find('[data-testid="grid"]').exists()).toBe(true);
    });
});
