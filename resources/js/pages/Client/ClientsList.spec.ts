import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ClientsList from './ClientsList.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

describe('ClientsList page', () => {
    it('renders page shell and create link', () => {
        const wrapper = mount(ClientsList, {
            props: {
                clients: [{ id: 1, name: 'Alice' }],
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<div><slot /></div>' },
                    Toast: { template: '<div data-testid="toast" />' },
                    ConfirmationModal: {
                        props: ['isOpen'],
                        template: '<div data-testid="modal" />',
                    },
                    AgGridVue: { template: '<div data-testid="grid" />' },
                },
            },
        });

        expect(wrapper.find('a[href="/clients/create"]').exists()).toBe(true);
        expect(wrapper.find('[data-testid="grid"]').exists()).toBe(true);
    });
});
