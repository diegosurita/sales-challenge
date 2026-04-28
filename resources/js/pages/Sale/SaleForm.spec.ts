import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import SaleForm from './SaleForm.vue';

const { visitMock, postMock } = vi.hoisted(() => ({
    visitMock: vi.fn(),
    postMock: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
    router: {
        visit: visitMock,
    },
    useForm: () => ({
        client_id: null,
        products: [],
        services: [],
        errors: {},
        processing: false,
        post: postMock,
    }),
}));

describe('SaleForm page', () => {
    it('renders core sections and save action', () => {
        const wrapper = mount(SaleForm, {
            props: {
                clients: [{ id: 1, name: 'Alice' }],
                products: [{ id: 1, name: 'Pencil', price: 1.5, stock_count: 10 }],
                services: [
                    {
                        id: 1,
                        name: 'Delivery',
                        price: 5,
                        available: true,
                        product: null,
                    },
                ],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<button><slot /></button>' },
                    ComboboxInput: { template: '<input />' },
                    ComboboxOptions: { template: '<ul><slot /></ul>' },
                    ComboboxOption: { template: '<li><slot /></li>' },
                },
            },
        });

        expect(wrapper.text()).toContain('Products');
        expect(wrapper.text()).toContain('Services');
    });

    it('navigates back on cancel', async () => {
        const wrapper = mount(SaleForm, {
            props: {
                clients: [],
                products: [],
                services: [],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<button><slot /></button>' },
                    ComboboxInput: { template: '<input />' },
                    ComboboxOptions: { template: '<ul><slot /></ul>' },
                    ComboboxOption: { template: '<li><slot /></li>' },
                },
            },
        });

        const cancelButton = wrapper
            .findAll('button')
            .find((buttonWrapper) => buttonWrapper.text().includes('Cancel'));

        if (cancelButton === undefined) {
            throw new Error('Cancel button not found');
        }

        await cancelButton.trigger('click');
        expect(visitMock).toHaveBeenCalledWith('/sales');
    });
});
