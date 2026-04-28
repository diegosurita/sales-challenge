import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import ClientForm from './ClientForm.vue';

const { visitMock } = vi.hoisted(() => ({
    visitMock: vi.fn(),
}));

const pageMock = reactive({
    props: {
        errors: {
            name: 'Name required',
        } as Record<string, string>,
    },
});

vi.mock('@inertiajs/vue3', () => ({
    Head: { template: '<div />' },
    router: {
        visit: visitMock,
    },
    usePage: () => pageMock,
}));

describe('ClientForm page', () => {
    it('renders edit mode and clears field errors', async () => {
        const wrapper = mount(ClientForm, {
            props: {
                client: {
                    id: 3,
                    name: 'Bob',
                },
            },
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                },
            },
        });

        expect(wrapper.get('input[name="_method"]').attributes('value')).toBe('PUT');
        await wrapper.get('input#name').setValue('Bobby');
        expect(pageMock.props.errors.name).toBeUndefined();
    });

    it('navigates back on cancel', async () => {
        const wrapper = mount(ClientForm, {
            global: {
                stubs: {
                    InternalBaseLayout: { template: '<section><slot /></section>' },
                },
            },
        });

        await wrapper.get('button[type="button"]').trigger('click');
        expect(visitMock).toHaveBeenCalledWith('/clients');
    });
});
