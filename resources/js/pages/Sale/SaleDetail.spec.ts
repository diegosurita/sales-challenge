import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import SaleDetail from './SaleDetail.vue';

const { visitMock } = vi.hoisted(() => ({
    visitMock: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
    router: {
        visit: visitMock,
    },
}));

describe('SaleDetail page', () => {
    it('renders totals and daily purchase label', () => {
        const wrapper = mount(SaleDetail, {
            props: {
                sale: {
                    id: 10,
                    client_id: 2,
                    client_name: 'Alice',
                    created_at: '2026-01-01T10:00:00Z',
                    updated_at: '2026-01-01T10:00:00Z',
                    client_daily_purchase_number: 2,
                    products: [
                        {
                            product_id: 1,
                            name: 'Item A',
                            price: 10,
                            quantity: 2,
                        },
                    ],
                    services: [
                        {
                            service_id: 4,
                            name: 'Delivery',
                            price: 5,
                        },
                    ],
                },
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                },
            },
        });

        expect(wrapper.text()).toContain('2nd purchase of the day');
        expect(wrapper.text()).toContain('$25.00');
    });

    it('navigates back to sales', async () => {
        const wrapper = mount(SaleDetail, {
            props: {
                sale: {
                    id: 11,
                    client_id: 2,
                    client_name: 'Alice',
                    created_at: '2026-01-01T10:00:00Z',
                    updated_at: '2026-01-01T10:00:00Z',
                    client_daily_purchase_number: 1,
                    products: [],
                    services: [],
                },
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                },
            },
        });

        await wrapper.get('button[type="button"]').trigger('click');
        expect(visitMock).toHaveBeenCalledWith('/sales');
    });
});
