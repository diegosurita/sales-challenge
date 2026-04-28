import { shallowMount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import Login from './Login.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
}));

describe('Login page', () => {
    it('renders login wrapper and form component', () => {
        const wrapper = shallowMount(Login, {
            global: {
                stubs: {
                    LoginForm: { template: '<form data-testid="login-form" />' },
                },
            },
        });

        expect(wrapper.text()).toContain('Welcome');
        expect(wrapper.find('[data-testid="login-form"]').exists()).toBe(true);
    });
});
