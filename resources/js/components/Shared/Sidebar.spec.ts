import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import Sidebar from './Sidebar.vue';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        url: '/clients',
    }),
}));

describe('Sidebar', () => {
    it('highlights active route and renders nav links', () => {
        const wrapper = mount(Sidebar);

        const clientsLink = wrapper.get('a[href="/clients"]');
        expect(clientsLink.classes()).toContain('bg-slate-800');
        expect(wrapper.findAll('a').length).toBe(5);
    });
});
