import { config, shallowMount } from '@vue/test-utils';
import { SortMode } from '@/enum/SortMode';
import DatatableSortMode from '@/components/datatable/DatatableSortMode.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(DatatableSortMode, {
    propsData: {
      ...props
    }
  });
};

describe('DatatableSortMode.vue', () => {
  afterEach(() => {
    localStorage.clear();
  });

  it('is Vue instance', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of DatatableSortMode', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.props().mode).toBe(SortMode.multi);
  });

  it('set mode to single', () => {
    const wrapper: any = factory({ tableId: 'tests', mode: SortMode.single });
    expect(wrapper.props().mode).toBe(SortMode.single);
  });

  it('define uuid', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('not emit event on creation without local storage entry', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.emitted('datatable:sortModeChanged')).toBeFalsy();
  });

  it('emit events on creation with local storage entry', () => {
    const obj: any = { 'sortMode': JSON.stringify(SortMode.single) };
    localStorage.setItem('datatable_tests', JSON.stringify(obj));
    const wrapper: any = factory({ tableId: 'tests' });

    expect(wrapper.emitted('update:mode').length).toBe(1);
    expect(wrapper.emitted('update:mode')).toBeTruthy();
    expect(wrapper.emitted('update:mode')[0]).toEqual(['single']);
    expect(wrapper.emitted('datatable:sortModeChanged').length).toBe(1);
    expect(wrapper.emitted('datatable:sortModeChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:sortModeChanged')[0]).toEqual(['single']);
  });

  it('not update when already selected', async () => {
    const wrapper: any = factory({ tableId: 'tests', mode: SortMode.single });
    const selector = wrapper.find('#datatable_sortMode-single-' + wrapper.vm.uuid);

    selector.trigger('click');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('update:mode')).toBeFalsy();
    expect(wrapper.emitted('datatable:sortModeChanged')).toBeFalsy();
  });

  it('change mode to single', async () => {
    const wrapper: any = factory({ tableId: 'tests' });
    const selector = wrapper.find('#datatable_sortMode-single-' + wrapper.vm.uuid);

    selector.trigger('click');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('update:mode')).toBeTruthy();
    expect(wrapper.emitted('update:mode')[0]).toEqual(['single']);
    expect(wrapper.emitted('datatable:sortModeChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:sortModeChanged')[0]).toEqual(['single']);

    const storage: any = JSON.parse(localStorage.getItem('datatable_tests')!)['sortMode'];
    expect(storage).toBe(JSON.stringify(SortMode.single));
  });
});
