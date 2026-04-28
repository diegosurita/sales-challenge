import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import RegisterStockModal from './RegisterStockModal.vue';

const { postMock } = vi.hoisted(() => ({
    postMock: vi.fn(),
}));

const pageMock = reactive({ props: { errors: {} as Record<string, string> } });

vi.mock('@inertiajs/vue3', () => ({
    router: {
        post: postMock,
    },
    usePage: () => pageMock,
}));

describe('RegisterStockModal', () => {
    it('shows validation message when reason is missing', async () => {
        const wrapper = mount(RegisterStockModal, {
            props: {
                isOpen: true,
                productId: 11,
            },
            global: {
                stubs: {
                    Dialog: { template: '<div><slot /></div>' },
                    DialogPanel: { template: '<div><slot /></div>' },
                    DialogTitle: { template: '<h2><slot /></h2>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<span><slot /></span>' },
                    ComboboxInput: {
                        template: '<input />',
                    },
                    ComboboxOptions: { template: '<ul><slot /></ul>' },
                    ComboboxOption: { template: '<li><slot /></li>' },
                },
            },
        });

        await wrapper.get('form').trigger('submit.prevent');
        expect(wrapper.text()).toContain('Please select a reason.');
    });

    it('emits close when cancel is clicked', async () => {
        const wrapper = mount(RegisterStockModal, {
            props: {
                isOpen: true,
                productId: 11,
            },
            global: {
                stubs: {
                    Dialog: { template: '<div><slot /></div>' },
                    DialogPanel: { template: '<div><slot /></div>' },
                    DialogTitle: { template: '<h2><slot /></h2>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<span><slot /></span>' },
                    ComboboxInput: {
                        template: '<input />',
                    },
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
        expect(wrapper.emitted('close')).toBeTruthy();
    });
});
