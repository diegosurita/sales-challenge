import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import ProductForm from './ProductForm.vue';

const { visitMock } = vi.hoisted(() => ({
    visitMock: vi.fn(),
}));

const pageMock = reactive({
    props: {
        errors: {
            price: 'Invalid price',
        } as Record<string, string>,
    },
});

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
    router: {
        visit: visitMock,
    },
    usePage: () => pageMock,
}));

describe('ProductForm page', () => {
    it('renders edit mode stock area', () => {
        const wrapper = mount(ProductForm, {
            props: {
                product: {
                    id: 5,
                    name: 'Pencil',
                    price: 2,
                    stock_count: 7,
                },
                stockLedgerEntries: [],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    AgGridVue: { template: '<div data-testid="ledger-grid" />' },
                    RegisterStockModal: { template: '<div data-testid="stock-modal" />' },
                },
            },
        });

        expect(wrapper.find('input[name="_method"]').exists()).toBe(true);
        expect(wrapper.find('[data-testid="ledger-grid"]').exists()).toBe(true);
    });

    it('navigates back on cancel', async () => {
        const wrapper = mount(ProductForm, {
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    AgGridVue: { template: '<div />' },
                    RegisterStockModal: { template: '<div />' },
                },
            },
        });

        await wrapper.get('button[type="button"]').trigger('click');
        expect(visitMock).toHaveBeenCalledWith('/products');
    });
});
