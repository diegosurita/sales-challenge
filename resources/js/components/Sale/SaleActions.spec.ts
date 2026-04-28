import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import SaleActions from './SaleActions.vue';

describe('SaleActions', () => {
    it('renders view link', () => {
        const wrapper = mount(SaleActions, {
            props: {
                params: {
                    data: { id: 7 },
                },
            },
        });

        expect(wrapper.get('a').attributes('href')).toBe('/sales/7');
    });
});
