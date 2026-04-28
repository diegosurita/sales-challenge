import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import TopMenuBar from './TopMenuBar.vue';

const { postMock, closeCallbacks } = vi.hoisted(() => ({
    postMock: vi.fn(),
    closeCallbacks: [] as Array<() => void>,
}));

vi.mock('@inertiajs/vue3', () => ({
    router: {
        post: postMock,
    },
}));

vi.mock('@vueuse/core', () => ({
    onClickOutside: (_target: unknown, callback: () => void) => {
        closeCallbacks.push(callback);
    },
    onKeyStroke: (_key: string, callback: () => void) => {
        closeCallbacks.push(callback);
    },
}));

vi.mock('../../actions/Module/Auth/Interface/Controllers/AuthenticationController', () => ({
    logout: {
        url: () => '/auth/logout',
    },
}));

describe('TopMenuBar', () => {
    it('toggles dropdown, closes via callbacks, and logs out', async () => {
        const wrapper = mount(TopMenuBar, {
            props: { title: 'Dashboard' },
        });

        const trigger = wrapper.get('button[aria-haspopup="menu"]');
        await trigger.trigger('click');

        expect(trigger.attributes('aria-expanded')).toBe('true');

        closeCallbacks.forEach((callback) => callback());
        await wrapper.vm.$nextTick();
        expect(trigger.attributes('aria-expanded')).toBe('false');

        await trigger.trigger('click');
        await wrapper.get('button[role="menuitem"]').trigger('click');
        expect(postMock).toHaveBeenCalledWith('/auth/logout');
    });
});
