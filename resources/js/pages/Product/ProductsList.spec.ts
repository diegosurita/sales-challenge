import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ProductsList from './ProductsList.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

describe('ProductsList page', () => {
    it('renders create link and data grid shell', () => {
        const wrapper = mount(ProductsList, {
            props: {
                products: [{ id: 1, name: 'Pencil', price: 1.5, stock_count: 10 }],
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

        expect(wrapper.find('a[href="/products/create"]').exists()).toBe(true);
        expect(wrapper.find('[data-testid="grid"]').exists()).toBe(true);
    });
});
