import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import ServiceForm from './ServiceForm.vue';

const { visitMock } = vi.hoisted(() => ({
    visitMock: vi.fn(),
}));

const pageMock = reactive({ props: { errors: {} as Record<string, string> } });

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
    router: {
        visit: visitMock,
    },
    usePage: () => pageMock,
}));

describe('ServiceForm page', () => {
    it('renders combobox and submit controls', () => {
        const wrapper = mount(ServiceForm, {
            props: {
                products: [{ id: 1, name: 'Pencil' }],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<button><slot /></button>' },
                    ComboboxInput: { template: '<input />' },
                    ComboboxOptions: { template: '<ul><slot /></ul>' },
                    ComboboxOption: { template: '<li><slot /></li>' },
                },
            },
        });

        expect(wrapper.text()).toContain('Dependent On Product');
        expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
    });

    it('navigates back on cancel', async () => {
        const wrapper = mount(ServiceForm, {
            props: {
                products: [],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    Combobox: { template: '<div><slot /></div>' },
                    ComboboxButton: { template: '<button><slot /></button>' },
                    ComboboxInput: { template: '<input />' },
                    ComboboxOptions: { template: '<ul><slot /></ul>' },
                    ComboboxOption: { template: '<li><slot /></li>' },
                },
            },
        });

        await wrapper.get('button[type="button"]').trigger('click');
        expect(visitMock).toHaveBeenCalledWith('/services');
    });
});
