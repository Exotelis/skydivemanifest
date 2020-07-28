import { config, shallowMount } from '@vue/test-utils';
import Pagination from '@/components/ui/Pagination.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(Pagination, {
    propsData: {
      ...props
    }
  });
};

describe('Pagination.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of the form group', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    expect(wrapper.props().hideRecords).toBeFalsy();
  });

  it('define uuid', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('change page and fire an event', async () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    wrapper.vm.changePage(3);

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('pagination:changed')).toBeTruthy();
    expect(wrapper.emitted('pagination:changed')[0]).toEqual([3]);
  });

  it('change page and fire events when select box has been changed', async () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    let select = wrapper.find('.pagination .selection').element;
    select.value = 3;
    wrapper.find('.pagination .selection').trigger('change');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('pagination:changed')).toBeTruthy();
    expect(wrapper.emitted('pagination:changed')[0]).toEqual([3]);
    expect(wrapper.emitted('update:current')).toBeTruthy();
    expect(wrapper.emitted('update:current')[0]).toEqual([3]);
  });

  it('check if record text has been rendered', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });

    expect(wrapper.find('.pagination-records').exists()).toBeTruthy();
    expect(wrapper.find('.pagination-records').text()).toBe('component.pagination.records');
  });

  it('check if record text hasn\'t been rendered', () => {
    const wrapper: any = factory({ current: 2, from: 11, hideRecords: true, last: 5, to: 20, total: 55 });

    expect(wrapper.find('.pagination-records').exists()).toBeFalsy();
  });

  it('check if \'last\' watcher updated current prop', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    wrapper.vm.onLastUpdate(1);

    expect(wrapper.emitted('update:current')).toBeTruthy();
    expect(wrapper.emitted('update:current')[0]).toEqual([1]);
  });

  it('check if \'last\' watcher didn\'t update current prop', () => {
    const wrapper: any = factory({ current: 2, from: 11, last: 5, to: 20, total: 55 });
    wrapper.vm.onLastUpdate(7);

    expect(wrapper.emitted('update:current')).toBeFalsy();
  });
});
