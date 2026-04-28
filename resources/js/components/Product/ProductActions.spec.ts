import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ProductActions from './ProductActions.vue';

describe('ProductActions', () => {
    it('renders edit link and calls onDelete', async () => {
        const onDelete = vi.fn();
        const wrapper = mount(ProductActions, {
            props: {
                params: {
                    data: { id: 12 },
                    context: { onDelete },
                },
            },
        });

        expect(wrapper.get('a').attributes('href')).toBe('/products/12/edit');

        await wrapper.get('button').trigger('click');
        expect(onDelete).toHaveBeenCalledWith(12);
    });
});
