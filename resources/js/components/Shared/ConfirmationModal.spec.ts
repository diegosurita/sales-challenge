import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { defineComponent, h } from 'vue';
import ConfirmationModal from './ConfirmationModal.vue';

vi.mock('@headlessui/vue', () => ({
    Dialog: defineComponent({
        props: ['open'],
        emits: ['close'],
        setup(_, { slots }) {
            return () => h('div', {}, slots.default?.());
        },
    }),
    DialogPanel: defineComponent({
        setup(_, { slots }) {
            return () => h('div', {}, slots.default?.());
        },
    }),
    DialogTitle: defineComponent({
        setup(_, { slots }) {
            return () => h('h2', {}, slots.default?.());
        },
    }),
}));

describe('ConfirmationModal', () => {
    it('emits close and confirm', async () => {
        const wrapper = mount(ConfirmationModal, {
            props: {
                isOpen: true,
                title: 'Delete item',
                message: 'Confirm?',
            },
        });

        const buttons = wrapper.findAll('button');
        await buttons[0].trigger('click');
        await buttons[1].trigger('click');

        expect(wrapper.emitted('close')).toBeTruthy();
        expect(wrapper.emitted('confirm')).toBeTruthy();
    });
});
