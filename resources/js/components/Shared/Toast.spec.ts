import { mount } from '@vue/test-utils';
import { afterEach, describe, expect, it, vi } from 'vitest';
import Toast from './Toast.vue';

describe('Toast', () => {
    afterEach(() => {
        vi.useRealTimers();
    });

    it('shows message and auto-dismisses', async () => {
        vi.useFakeTimers();

        const wrapper = mount(Toast, {
            props: {
                message: 'Saved successfully',
                type: 'success',
                dismissTime: 100,
            },
        });

        expect(wrapper.text()).toContain('Saved successfully');
        expect(wrapper.find('[role="alert"]').exists()).toBe(true);

        vi.advanceTimersByTime(110);
        await wrapper.vm.$nextTick();

        expect(wrapper.find('[role="alert"]').exists()).toBe(false);
    });
});
