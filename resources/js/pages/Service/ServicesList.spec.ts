import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ServicesList from './ServicesList.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

describe('ServicesList page', () => {
    it('renders create link and grid shell', () => {
        const wrapper = mount(ServicesList, {
            props: {
                services: [
                    {
                        id: 2,
                        name: 'Delivery',
                        price: 10,
                        available: true,
                        product: null,
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

        expect(wrapper.find('a[href="/services/create"]').exists()).toBe(true);
        expect(wrapper.find('[data-testid="grid"]').exists()).toBe(true);
    });
});
