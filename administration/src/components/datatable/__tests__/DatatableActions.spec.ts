import { config, shallowMount } from '@vue/test-utils';
import { ActionMode } from '@/enum/ActionMode';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import DatatableActions from '@/components/datatable/DatatableActions.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(DatatableActions, {
    propsData: {
      ...props
    }
  });
};

describe('DatatableActions.vue', () => {
  let actions!: Array<DatatableActionModel>;
  let items!: object|Array<object>;

  beforeEach(() => {
    actions = [
      { label: 'Show', eventId: 'show', icon: 'mdi-eye' },
      { label: 'Edit', eventId: 'edit', icon: 'mdi-pencil' },
      { label: 'Delete', eventId: 'delete', critical: true, icon: 'mdi-delete' }
    ];
    items = [
      { id: 0, roleName: 'Admin' },
      { id: 1, roleName: 'User' },
      { id: 2, roleName: 'Guest' }
    ];
  });

  it('is Vue instance', () => {
    const wrapper: any = factory({ actions: actions, items: items });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of DatatableActions', () => {
    const wrapper: any = factory({ actions: actions, items: items });
    expect(wrapper.props().mode).toBe(ActionMode.single);
  });

  it('set mode to bulk', () => {
    const wrapper: any = factory({ actions: actions, items: items, mode: ActionMode.bulk });
    expect(wrapper.props().mode).toBe(ActionMode.bulk);
  });

  it('define uuid', () => {
    const wrapper: any = factory({ actions: actions, items: items });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('render the single mode', () => {
    const wrapper: any = factory({ actions: actions, items: items });

    expect(wrapper.find('.dropdown').exists()).toBeTruthy();
    expect(wrapper.find('.btn-group').exists()).toBeFalsy();
  });

  it('render the bulk mode', () => {
    const wrapper: any = factory({ actions: actions, items: items, mode: ActionMode.bulk });

    expect(wrapper.find('.dropdown').exists()).toBeFalsy();
    expect(wrapper.find('.btn-group').exists()).toBeTruthy();
  });

  it('disable bulk action button, when no item is selected', () => {
    const wrapper: any = factory({ actions: actions, items: [], mode: ActionMode.bulk });

    expect(wrapper.find('.btn:not(.dropdown-toggle)').attributes().disabled).toBeTruthy();
    expect(wrapper.find('.dropdown-toggle').attributes().disabled).toBeTruthy();
  });

  it('not render dropdown-toggle when only one bulk action is defined', () => {
    const wrapper: any = factory({ actions: actions.slice(0, 1), items: items, mode: ActionMode.bulk });

    expect(wrapper.find('.dropdown-toggle').exists()).toBeFalsy();
  });

  it('call \'onActionClick\'', async () => {
    const wrapper: any = factory({ actions: actions, items: { id: 0, roleName: 'Admin' } });
    const spy = jest.spyOn(wrapper.vm, 'onActionClick');

    wrapper.find('.dropdown-menu a:nth-child(1)').trigger('click');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });
});
