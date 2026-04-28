import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ServiceActions from './ServiceActions.vue';

describe('ServiceActions', () => {
    it('renders edit link and calls onDelete', async () => {
        const onDelete = vi.fn();
        const wrapper = mount(ServiceActions, {
            props: {
                params: {
                    data: { id: 9 },
                    context: { onDelete },
                },
            },
        });

        expect(wrapper.get('a').attributes('href')).toBe('/services/9/edit');

        await wrapper.get('button').trigger('click');
        expect(onDelete).toHaveBeenCalledWith(9);
    });
});
