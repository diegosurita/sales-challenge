import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ClientActions from './ClientActions.vue';

describe('ClientActions', () => {
    it('renders edit link and calls onDelete', async () => {
        const onDelete = vi.fn();
        const wrapper = mount(ClientActions, {
            props: {
                params: {
                    data: { id: 10 },
                    context: { onDelete },
                },
            },
        });

        expect(wrapper.get('a').attributes('href')).toBe('/clients/10/edit');

        await wrapper.get('button').trigger('click');
        expect(onDelete).toHaveBeenCalledWith(10);
    });
});
