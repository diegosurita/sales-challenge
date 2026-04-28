import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import InternalBaseLayout from './InternalBaseLayout.vue';

describe('InternalBaseLayout', () => {
    it('renders title and slot content through layout children', () => {
        const wrapper = mount(InternalBaseLayout, {
            props: { title: 'Clients' },
            slots: {
                default: '<p data-testid="slot-content">Body</p>',
            },
            global: {
                stubs: {
                    Sidebar: { template: '<aside data-testid="sidebar"></aside>' },
                    TopMenuBar: {
                        props: ['title'],
                        template:
                            '<header data-testid="top-menu">{{ title }}</header>',
                    },
                },
            },
        });

        expect(wrapper.find('[data-testid="sidebar"]').exists()).toBe(true);
        expect(wrapper.get('[data-testid="top-menu"]').text()).toContain('Clients');
        expect(wrapper.get('[data-testid="slot-content"]').text()).toBe('Body');
    });
});
