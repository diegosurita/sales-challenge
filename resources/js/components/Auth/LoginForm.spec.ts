import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import LoginForm from './LoginForm.vue';

const pageMock = reactive({
    props: {
        errors: {
            email: 'Invalid email',
            failed: 'Wrong credentials',
        } as Record<string, string>,
    },
});

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => pageMock,
}));

describe('LoginForm', () => {
    it('renders errors and clears field/global errors on input', async () => {
        const wrapper = mount(LoginForm);

        expect(wrapper.text()).toContain('Wrong credentials');
        expect(wrapper.text()).toContain('Invalid email');

        await wrapper.get('input#email').setValue('user@example.com');
        expect(pageMock.props.errors.email).toBeUndefined();
        expect(pageMock.props.errors.failed).toBeUndefined();
    });

    it('submits valid form', async () => {
        const wrapper = mount(LoginForm);
        const formElement = wrapper.get('form').element as HTMLFormElement;
        const submitSpy = vi.fn();

        formElement.checkValidity = () => true;
        formElement.submit = submitSpy;

        await wrapper.get('form').trigger('submit.prevent');
        expect(submitSpy).toHaveBeenCalled();
    });
});
